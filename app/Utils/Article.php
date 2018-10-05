<?php

namespace App\Utils;

use App\FeedItems;
use App\FeedItemsMeta;
use App\ItemKeyType;

class Article
{
	/** @var FeedItems */
	protected $dbItem;
	/** @var int */
	public $id;
	/** @var string */
	public $title;
	/** @var string */
	public $link;
	/** @var string */
	public $guid;
	/** @var string */
	public $description;
	/** @var string */
	public $author;
	/** @var string */
	public $pubdate;
	/** @var string */
	public $image;
	/** @var string */
	public $feedName;
	/** @var bool */
	public $scan;

	public function __construct(int $itemId)
	{
		$this->dbItem = FeedItems::find($itemId);
		$this->id = $this->dbItem->id;
		$this->title = strip_tags($this->dbItem->title);
		$this->link = $this->dbItem->link;
		$this->guid = $this->dbItem->guid;
		$this->description = strip_tags($this->dbItem->description);
		$this->author = $this->dbItem->author;
		$this->pubdate = $this->dbItem->pubDate;
		$this->image = $this->getImage($itemId);
		$this->feedName = $this->dbItem->feed->name;
		$this->scan = $this->dbItem->feed->scan;
	}

	public function hasImage(): bool
	{
		return $this->image !== null;
	}

	private function getImage(int $itemId): ?string
	{
		if (!$this->dbItem->feed->scan) {
			return $this->dbItem->article ? $this->dbItem->article->image : null;
		} else {
			if ($meta = FeedItemsMeta::where('item_id', $itemId)->where('keytype_id', ItemKeyType::ARTICLE_IMAGE)->first()) {
				return $meta->value;
			} else {
				return null;
			}
		}
	}
}