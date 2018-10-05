<?php

namespace App\Jobs;

use App\FeedItems;
use App\FeedItemsMeta;
use App\FeedMapping;
use App\Feeds;
use App\ItemKeyType;
use App\Utils\FeedParser;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FeedScanner implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/** @var Feeds */
	private $feed;
	/** @var FeedParser */
	private $parser;
	/** @var int[] */
	private $checkedMappings = [];

	public function __construct(Feeds $feed)
	{
		$this->feed = $feed;
	}

	public function handle()
	{
		try {
			$this->parser = new FeedParser($this->feed->rss);
			if ($this->parser->parse(false)) {
				echo('PARSED: ' . $this->parser->getRss() . "\n");
				$itemsPath = $this->parser->findItemsPath();
				$itemContainer = $this->parser->resolvePath($itemsPath);
				echo("\tProcessing items\n");
				foreach ($itemContainer['children']['item'] as $item) {
					if ($item['path'] === $itemsPath . FeedParser::SEPARATOR . 'item') {
						$itemId = $this->setFeedItem($item);
						if ($itemId !== -1) {
							$this->dumpItemMeta($itemId, $item);
						}
					}
					$this->checkedMappings = [];
				}
				echo("\tProcessing items complete\n");
				echo("\tUpdating feed\n");
				if ($date = $this->parser->resolvePath('rss/channel/lastbuilddate')) {
					$this->feed->pubDate = Carbon::parse($date['text']);
				} else {
					$this->feed->pubDate = Carbon::parse($this->parser->resolvePath('rss/channel/pubdate')['text']);
				}
				$this->feed->date_updated = Carbon::now();
				if ($this->feed->save()) {
					echo("\tUpdating complete!\n");
				} else {
					echo("\tUpdating failed!\n");
				}
			} else {
				error_log('[' . Carbon::now() . '] ERROR Cannot parse RSS: ' . $this->parser->getRss() . "\n");
			}
		} catch (\Exception $e) {
			error_log('[' . Carbon::now() . '] ERROR: Cannot parse RSS: ' . $this->parser->getRss() . "\n");
			error_log($e);
		}
	}

	/**
	 * Create a tuple in the feed_items table
	 * @param array $item
	 * @return int item->id
	 */
	private function setFeedItem(array $item): int
	{
		try {
			//Find a valid title
			$title = $this->getKeyTypeValue($item, ItemKeyType::find(ItemKeyType::ARTICLE_TITLE));
			$hasRealTitle = $title !== null;
			if (!$hasRealTitle) {
				$title = $this->getKeyTypeValue($item, ItemKeyType::find(ItemKeyType::ARTICLE_DESCRIPTION));
			}
			if ($title === null) {
				echo("\t\tSkipping item without valid title\n");
				return -1;
			} else {
				//Find a valid guid
				if (isset($item['children']['guid'])) {
					$guid = $item['children']['guid'][0]['text'];
				} else {
					$guid = $title;
				}
				echo("\t\tFound item: $guid\n");
				if (FeedItems::where('guid', $guid)->where('feed_id', $this->feed->id)->first() === null) {
					echo("\t\t\tNot yet registered\n");
					$feedItem = new FeedItems();
					$feedItem->feed_id = $this->feed->id;
					$feedItem->title = $title;
					if ($hasRealTitle) {
						$feedItem->description = $this->getKeyTypeValue($item, ItemKeyType::find(ItemKeyType::ARTICLE_DESCRIPTION));
					}
					$feedItem->guid = $guid;
					$feedItem->link = $this->getKeyTypeValue($item, ItemKeyType::find(ItemKeyType::ARTICLE_LINK));
					$feedItem->author = $this->getKeyTypeValue($item, ItemKeyType::find(ItemKeyType::ARTICLE_AUTHOR));
					$pubDate = $this->getKeyTypeValue($item, ItemKeyType::find(ItemKeyType::ARTICLE_DATE));
					if ($pubDate !== null) {
						$feedItem->pubDate = Carbon::parse($pubDate);
					} else {
						$feedItem->pubDate = Carbon::now();
					}
					$feedItem->date_added = Carbon::now();
					if ($feedItem->save()) {
						echo("\t\t\tItem registered successfully\n");
						return $feedItem->id;
					} else {
						echo("\t\t\tItem registration failed!\n");
						return -1;
					}
				} else {
					echo("\t\t\tAlready registered: skipping\n");
					return -1;
				}
			}
		} catch (\Exception $e) {
			echo("\t\t\tItem registration crashed!\n");
			return -1;
		}
	}

	/**
	 * Get the value of an ItemKeyType
	 * Will use the RSS spec path if there's one defined and the item contains the spec path
	 * If the RSS spec is not valid for use, the mapping will be used
	 * Will return null if there is no valid mapping for the requested ItemKeyType
	 * @param array $item
	 * @param ItemKeyType $keytype
	 * @return null|mixed
	 */
	private function getKeyTypeValue(array $item, ItemKeyType $keytype)
	{
		$this->checkedMappings[] = $keytype->id;
		if ($keytype->spec_path !== null) {
			$path = str_replace('[item]/', '', $keytype->spec_path);
			if (str_contains($path, ':')) {
				$path = explode(':', $path);
				$attribute = $path[1];
			}
			$path = explode('/', is_array($path) ? $path[0] : $path);
			if ($pathData = $this->getChildFromPathParts($item, $path)) {
				return isset($attribute) ? $pathData['attributes'][$attribute] : $pathData['text'];
			}
		}
		$mapping = FeedMapping::where('feed_id', $this->feed->id)->where('keytype_id', $keytype->id)->first();
		if ($mapping) {
			$path = str_replace($this->parser->findItemsPath() . '/item/', '', $mapping->path);
			if (str_contains($path, ':') && $mapping->is_attribute) {
				$path = explode(':', $path);
				$attribute = $path[1];
			}
			$path = explode('/', is_array($path) ? $path[0] : $path);
			if ($pathData = $this->getChildFromPathParts($item, $path)) {
				return isset($attribute) ? $pathData['attributes'][$attribute] : $pathData['text'];
			}
		}
		return null;
	}

	private function getChildFromPathParts(array $item, array $pathPieces)
	{
		$result = $item;
		foreach ($pathPieces as $piece) {
			if (!isset($result['children'][$piece])) {
				return null;
			} else {
				$result = $result['children'][$piece][0];
			}
		}
		return $result;
	}

	/**
	 * Dump all mapping data that was left into the feed_items_meta table
	 * @param int $itemId
	 * @param array $item
	 */
	private function dumpItemMeta(int $itemId, array $item)
	{
		$mappings = FeedMapping::where('feed_id', $this->feed->id)
			->whereNotIn('keytype_id', $this->checkedMappings)
			->get();
		foreach ($mappings as $mapping) {
			$path = str_replace($this->parser->findItemsPath() . '/item/', '', $mapping->path);
			if (str_contains($path, ':') && $mapping->is_attribute) {
				$path = explode(':', $path);
				$attribute = $path[1];
			}
			$path = explode('/', is_array($path) ? $path[0] : $path);
			if ($pathData = $this->getChildFromPathParts($item, $path)) {
				$meta = new FeedItemsMeta();
				$meta->item_id = $itemId;
				$meta->keytype_id = $mapping->keytype_id;
				$meta->value = isset($attribute) ? $pathData['attributes'][$attribute] : $pathData['text'];
				$meta->save();
			}
		}
	}
}