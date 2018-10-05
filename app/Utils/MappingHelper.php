<?php

namespace App\Utils;

final class MappingHelper
{
	/** @var FeedParser */
	private $parser;
	/** @var array */
	private $mapping = [];

	public function __construct(FeedParser $parser)
	{
		$this->parser = $parser;
	}

	public function constructArray()
	{
		foreach ($this->parser->getSubPaths(FeedParser::SEPARATOR) as $path) {
			if (array_search($path, $this->parser->pathBlacklist) === false) {
				$item = array_merge([], $this->parser->resolvePath($path));
				if (!empty($item['text'])) {
					$item['display'] = $item['text'];
					$item['isAttribute'] = false;
					$this->mapping[] = $item;
				}
				foreach ($item['attributes'] as $key => $value) {
					$newItem = array_merge([], $item);
					$newItem['path'] .= ":$key";
					$newItem['display'] = $value;
					$newItem['isAttribute'] = true;
					$this->mapping[] = $newItem;
				}
			}
		}
	}

	public function getArray(): array
	{
		return $this->mapping;
	}
}