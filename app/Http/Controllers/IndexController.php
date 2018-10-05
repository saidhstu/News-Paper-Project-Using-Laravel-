<?php

namespace App\Http\Controllers;

use App\Category;
use App\FeedItems;
use App\Feeds;
use App\Portal;
use App\Utils\Article;
use App\Utils\IntegrationUtils;
use App\Utils\ItemRetriever;
use App\Utils\Utils;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

final class IndexController extends AbstractController
{
	private const ITEMS_PER_PAGE = 15;

	public function index()
	{
		return $this->search(-1, 0);
	}

	public function search(int $portalId, int $page, int $categoryId = Category::DEFAULT, string $search = null)
	{
		$rootCategoryId = Category::find($categoryId)->parent_id;
		$minPage = max(0, $page - 1);
		$itemCount = (new ItemRetriever(true))->portal($portalId)->search($search)->category($categoryId)->realCount();
		$totalPages = (int)ceil($itemCount / IndexController::ITEMS_PER_PAGE);
		$maxPage = min($totalPages, $minPage + 3);
		$feeders = Feeds::query();
		if ($portalId !== -1) {
			$feeders
				->join('portal_feeds', 'portal_feeds.feed_id', '=', 'feeds.id')
				->where('portal_feeds.portal_id', $portalId);
		}
		$feeders
			->where(function(Builder $query) {
				$query->where('feeds.public', true);
				if (Auth::check()) {
					$query->orWhere('feeds.user_id', Auth::id());
				}
			})
			->where('category_id', $categoryId);
		return view('home', [
			'latestItems' => (new ItemRetriever())->portal($portalId)->limit(10)->retrieveObjects(),
			'paginateCurrent' => $page,
			'itemCount' => $itemCount,
			'paginateMin' => $minPage,
			'paginateMax' => $maxPage,
			'items' => (new ItemRetriever())->search($search)->portal($portalId)->category($categoryId)->limit(IndexController::ITEMS_PER_PAGE, $page * IndexController::ITEMS_PER_PAGE)->retrieveObjects(),
			'rootCategories' => Category::fetchRootCategories(),
			'rootCategoryId' => $rootCategoryId ? $rootCategoryId : $categoryId,
			'categories' => Category::fetchChildCategories($rootCategoryId ? $rootCategoryId : $categoryId),
			'categoryId' => $categoryId,
			'search' => $search,
			'portals' => Portal::all(),
			'portal' => $portalId,
			'feeders' => $feeders->get(),
			'hasBizzMail' => IntegrationUtils::BizzMailPackageValid(),
		]);
	}

	public function feeder(int $portalId, int $page, int $feedId)
	{
		if ($feed = Feeds::find($feedId)) {
			if (Utils::isUserAdmin() || $feed->public || $feed->user_id == Auth::id()) {
				$categoryId = $feed->category_id;
				$rootCategoryId = Category::find($categoryId)->parent_id;
				$minPage = max(0, $page - 1);
				$itemCount = FeedItems::query()->where('feed_id', $feedId)->count();
				$totalPages = (int)ceil($itemCount / IndexController::ITEMS_PER_PAGE);
				$maxPage = min($totalPages, $minPage + 3);
				$feeders = Feeds::query();
				if ($portalId !== -1) {
					$feeders
						->join('portal_feeds', 'portal_feeds.feed_id', '=', 'feeds.id')
						->where('portal_feeds.portal_id', $portalId);
				}
				$feeders
					->where(function(Builder $query) {
						$query->where('feeds.public', true);
						if (Auth::check()) {
							$query->orWhere('feeds.user_id', Auth::id());
						}
					})
					->where('category_id', $categoryId);
				$rawItems = FeedItems::query()->where('feed_id', $feedId)->orderByDesc('pubDate')->limit(IndexController::ITEMS_PER_PAGE, $page * IndexController::ITEMS_PER_PAGE)->get();
				$items = [];
				foreach ($rawItems as $rawItem) {
					$items[] = new Article($rawItem->id);
				}
				return view('feeder', [
					'latestItems' => (new ItemRetriever())->portal($portalId)->limit(10)->retrieveObjects(),
					'paginateCurrent' => $page,
					'itemCount' => $itemCount,
					'paginateMin' => $minPage,
					'paginateMax' => $maxPage,
					'items' => $items,
					'rootCategories' => Category::fetchRootCategories(),
					'rootCategoryId' => $rootCategoryId ? $rootCategoryId : $categoryId,
					'categories' => Category::fetchChildCategories($rootCategoryId ? $rootCategoryId : $categoryId),
					'categoryId' => $categoryId,
					'search' => null,
					'portals' => Portal::all(),
					'portal' => $portalId,
					'feeders' => $feeders->get(),
					'hasBizzMail' => IntegrationUtils::BizzMailPackageValid(),
					'feederId' => $feedId
				]);
			}
		}
		return redirect()->to('/');
	}
}