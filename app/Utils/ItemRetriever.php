<?php

namespace App\Utils;

use App\Category;
use App\FeedItems;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

final class ItemRetriever
{
	/** @var Builder */
	private $query;
	/** @var int */
	private $limit = 0, $offset = 0;
	/** @var string|null */
	private $search = null;
	/** @var int */
	private $category = Category::DEFAULT;
	/** @var int|null */
	private $portal = null;
	/** @var bool */
	private $hasConstructed = false;

	public function __construct(bool $count = false)
	{
		$this->query = FeedItems::query();
		if (!$count) {
			$this->query->select('feed_items.id');
		} else {
			$this->query->selectRaw('COUNT(`feed_items`.`id`) as count');
		}
		$this->query->join('feeds', 'feed_items.feed_id', '=', 'feeds.id');
	}

	/**
	 * Set the offset and limit of the amount of items to retrieve
	 * Primarily used for pagination
	 * @param int $limit
	 * @param $offset
	 * @return ItemRetriever
	 */
	public function limit(int $limit, $offset = 0): ItemRetriever
	{
		$this->limit = $limit;
		$this->offset = $offset;
		return $this;
	}

	/**
	 * Set the search term for the items to retrieve
	 * @param string $search
	 * @return ItemRetriever
	 */
	public function search(?string $search): ItemRetriever
	{
		$this->search = $search;
		return $this;
	}

	/**
	 * Set the category of the items to retrieve
	 * @param int $category
	 * @return ItemRetriever
	 */
	public function category(int $category): ItemRetriever
	{
		$this->category = $category;
		return $this;
	}

	/**
	 * Set the portal to restrict the used feeds to
	 * @param int $portal
	 * @return ItemRetriever
	 */
	public function portal(int $portal): ItemRetriever
	{
		if ($portal !== -1) {
			$this->portal = $portal;
		}
		return $this;
	}

	/**
	 * @return Collection
	 */
	public function retrieve(): Collection
	{
		return $this->constructQuery()->get();
	}

	/**
	 * @return Article[]
	 */
	public function retrieveObjects(): array
	{
		$items = $this->retrieve();
		//This is needed to make sure the array contains the items in the correct sequence
		$result = array_fill(0, $items->count(), null);
		for ($i = 0; $i < sizeof($result); ++$i) {
			$result[$i] = new Article($items->get($i)->id);
		}
		return $result;
	}

	/**
	 * Get the item count (when the ItemRetreiver was created with count=true in the constructor)
	 * @return int
	 */
	public function realCount(): int
	{
		return $this->retrieve()->first()->count;
	}

	private function constructQuery(): Builder
	{
		if (!$this->hasConstructed) {
			$this->hasConstructed = true;
			$this->query
				->where(function(Builder $query) {
					$query->where('feeds.public', true);
					if (Auth::check()) {
						$query->orWhere('feeds.user_id', Auth::id());
					}
				});
			if ($this->portal !== null) {
				$this->query
					->join('portal_feeds', 'portal_feeds.feed_id', '=', 'feeds.id')
					->where('portal_feeds.portal_id', $this->portal);
			}
			if ($this->search !== null) {
				$this->query->where(function(Builder $query) {
					$query->where('feed_items.title', 'like', '%' . $this->search . '%')
						->orWhere('feed_items.description', 'like', '%' . $this->search . '%');
				});
			}
			if ($this->limit > 0) {
				$this->query->limit($this->limit)->offset($this->offset);
			}
			if ($this->category !== null) {
				$this->query->where('feeds.category_id', $this->category);
			}
			$this->query
				->where('feeds.disabled', false)
				->orderByDesc('feed_items.pubDate');
		}
		return $this->query;
	}
}