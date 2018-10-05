<?php

namespace App\Utils;

final class FeedParser
{
	public const SEPARATOR = '/';
	/** @var string */
	private $rss, $raw;
	/** @var array */
	private $parsed, $pathMapping;
	/** @var string[] */
	public $pathBlacklist = [
		'rss',
		'rss' . FeedParser::SEPARATOR . 'channel',
	];

	public function __construct(string $rssUrl)
	{
		$this->rss = $rssUrl;
	}

	/**
	 * @return string
	 */
	public function getRss(): string
	{
		return $this->rss;
	}

	/**
	 * Parse the feed into one giant array and map all paths to the fields
	 * @param bool $ignoreErrors
	 * @return bool
	 */
	public function parse(bool $ignoreErrors = true): bool
	{
		$context = stream_context_create(['http' => ['timeout' => 7]]);
		if ($ignoreErrors) {
			$this->raw = @file_get_contents($this->rss, false, $context);
		} else {
			$this->raw = file_get_contents($this->rss, false, $context);
		}
		if ($this->raw === false) {
			return false;
		} else {
			/**
			 * Some tags in xml have namespaces:
			 * <dc:rights>
			 * <dc:author>
			 * To fix overlapping tags after parsing and to make sure no tags will be overwritten
			 * this regex is used to change the tag names.
			 * For example:
			 * <dc:rights> will be renamed to <dc_rights>
			 */
			$parsedXml = preg_replace('/(<([\/]?\w+):(\w+)( ?))/i', '<${2}_${3}${4}', $this->raw);
			if ($ignoreErrors) {
				$xml = @simplexml_load_string($parsedXml);
			} else {
				$xml = simplexml_load_string($parsedXml);
			}
			$this->parsed = $xml === false ? false : $this->xml2phpArray($xml);
			//Add the item tag itself to the blacklist (not it's children!)
			if ($this->parsed !== false) {
				$this->pathBlacklist[] = $this->findItemsPath() . FeedParser::SEPARATOR . 'item';
				return true;
			} else {
				return false;
			}
		}
	}

	/**
	 * Find the path where all items (articles) are stored
	 * @return string
	 */
	public function findItemsPath(): string
	{
		return $this->getTagParent('item');
	}

	/**
	 * Find the parent tag-name of the given tag-name
	 * @param string $name
	 * @return string
	 */
	private function getTagParent(string $name): string
	{
		$path = $this->findTag($name);
		$result = str_replace_last($name, '', $path);
		if (Utils::stringEndsWith($result, FeedParser::SEPARATOR)) {
			return substr($result, 0, strlen($result) - 1);
		} else {
			return $result;
		}
	}

	/**
	 * Find the path of the given tag-name
	 * Returns null if the tag was not found
	 * @param string $name
	 * @param array|null $container
	 * @return null|string
	 */
	private function findTag(string $name, ?array $container = null): ?string
	{
		$array = $container === null ? $this->parsed : $container;
		if (Utils::isArrayAssoc($array)) {
			if (isset($array['path']) && Utils::stringEndsWith($array['path'], FeedParser::SEPARATOR . $name)) {
				return $array['path'];
			} else {
				foreach ($array as $key => $value) {
					if (is_array($value)) {
						$result = $this->findTag($name, $value);
						if ($result !== null) {
							return $result;
						}
					}
				}
				return null;
			}
		} else {
			for ($i = 0; $i < sizeof($array); ++$i) {
				$result = $this->findTag($name, $array[$i]);
				if ($result !== null) {
					return $result;
				}
			}
			return null;
		}
	}

	/**
	 * Load all the child paths from the given path
	 * @param string $path
	 * @return array
	 */
	public function getSubPaths(string $path): array
	{
		$result = [];
		//Make sure the path actually exists
		if ($array = $this->resolvePath($path)) {
			$this->getSubPathsFromArray($array, $result);
		}
		return $result;
	}

	/**
	 * Internal recursive method used by <code>getSubPaths</code>
	 * @param array $array
	 * @param array $result
	 */
	private function getSubPathsFromArray(array $array, array &$result)
	{
		foreach ($array['children'] as $name => $child) {
			for ($i = 0; $i < sizeof($child); ++$i) {
				$childItem = $child[$i];
				if (array_search($childItem['path'], $result) === false) {
					$result[] = $childItem['path'];
				}
				$this->getSubPathsFromArray($childItem, $result);
			}
		}
	}

	/**
	 * Get the array bound to the given path
	 * Returns null if the path doesn't exist
	 * @param string $path
	 * @return array|null
	 */
	public function resolvePath(string $path): ?array
	{
		if ($path === FeedParser::SEPARATOR) {
			return $this->parsed;
		} else {
			return isset($this->pathMapping[$path]) ? $this->pathMapping[$path] : null;
		}
	}

	/**
	 * Recursively convert a SimpleXML object to a PHP array
	 * @param \SimpleXMLElement $node
	 * @param string $pathPrefix
	 * @return array
	 */
	private function xml2phpArray(\SimpleXMLElement $node, string $pathPrefix = ''): array
	{
		//Load namespaces
		$namespace = $node->getDocNamespaces(true);
		$namespace[null] = null;
		//Create variables
		$children = [];
		$attributes = [];
		$name = strtolower($node->getName());
		$text = trim((string)$node);
		$path = $pathPrefix . (!empty($pathPrefix) ? FeedParser::SEPARATOR : '') . $name;
		//Load info
		if (is_object($node)) {
			foreach ($namespace as $ns => $url) {
				$objectAttribs = $node->attributes($ns, true);
				foreach ($objectAttribs as $attribName => $value) {
					$attribName = (!empty($ns) ? "$ns:" : '') . strtolower(trim($attribName));
					$value = trim($value);
					$attributes[$attribName] = $value;
				}
				//Children
				$objectChildren = $node->children($ns, true);
				foreach ($objectChildren as $childName => $child) {
					$childName = (!empty($ns) ? "$ns:" : '') . strtolower($childName);
					$children[$childName][] = $this->xml2phpArray($child, $path);
				}
			}
		}
		$result = [
			'name' => $name,
			'path' => $path,
			'text' => $text,
			'attributes' => $attributes,
			'children' => $children,
		];
		//Map the path to easily get items from paths
		$this->pathMapping[$path] = $result;
		return $result;
	}
}