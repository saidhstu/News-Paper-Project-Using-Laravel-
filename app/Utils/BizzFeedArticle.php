<?php

namespace App\Utils;

final class BizzFeedArticle extends Article
{
	/** @var bool */
	public $hasContent;
	/** @var string */
	public $content;

	public function __construct(int $itemId, bool $hasContent)
	{
		parent::__construct($itemId);
		$this->hasContent = $hasContent;
		if ($hasContent) {
			if (!$this->dbItem->feed->scan) {
				$this->content = html_entity_decode($this->dbItem->article->content);
			}
		}
	}
}