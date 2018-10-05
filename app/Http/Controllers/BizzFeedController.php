<?php

namespace App\Http\Controllers;

use Config;
use App\FeedItems;
use App\Feeds;
use App\Portal;
use App\Utils\BizzFeedArticle;
use App\Utils\Utils;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

final class BizzFeedController extends AbstractController
{
	protected function getActiveMenuLink(): ?string
	{
		return 'feeds';
	}

	public function Creator()
	{
	

		if (Auth::check()) {
			return view('feeds.bizzFeedCreator', [
				'portals' => Portal::all()
			]);
		} else {
			Utils::flashError(trans('global.notLoggedIn'));
			return redirect()->to('/feeds');
		}
	}

	public function creatorAction()
	{
		if (!Input::get('action')) {
			$this->ajaxError('No action specified');
		} else {
			switch (Input::get('action')) {
				case 'getPortals':
					$this->ajaxData(Portal::all()->toArray());
					break;
				case 'getFeeds':
					$query = Feeds::query();
					if (($portal = Input::get('portal')) != -1) {
						$query
							->join('portal_feeds', 'portal_feeds.feed_id', '=', 'feeds.id')
							->where('portal_feeds.portal_id', $portal)
							->where(function(Builder $query) {
								$query->where('feeds.public', true);
								if (Auth::check()) {
									$query->orWhere('feeds.user_id', Auth::id());
								}
							});
					}
					$this->ajaxData($query->get()->toArray());
					break;
				default:
					$this->ajaxError('Invalid action');
			}
		}
	}

	private function ajaxError(string $message)
	{
		die(json_encode(['error' => true, 'data' => ['msg' => $message]]));
	}

	private function ajaxData(array $data)
	{
		die(json_encode(['error' => false, 'data' => $data]));
	}

	public function getItems()
	{
		header('Access-Control-Allow-Origin: *');
		$token = Input::get("token");
		$feeds = Input::get("feeds");
		$full = Input::get("full");
		if (!$feeds || $feeds == -1) {
			$this->ajaxError('Invalid feed!');
		} else {
			$feeds = explode(":", $feeds);
			if (sizeof($feeds) === 0) {
				$this->ajaxError("Invalid feeds!");
			} else {
				$feedsObj = Feeds::query();
				for ($i = 0; $i < sizeof($feeds); ++$i) {
					if ($i == 0) {
						$feedsObj->where('id', $feeds[$i]);
					} else {
						$feedsObj->orWhere('id', $feeds[$i]);
					}
				}
				if ($full === "true") {
					$feedsObj = $feedsObj->get();
					foreach ($feedsObj as $feedObj) {
						if (!$feedObj->scan && !$this->validateToken($token)) {
							$this->ajaxError('Invalid token!');
						}
					}
					unset($feedsObj);
				}
				$items = FeedItems::query();
				for ($i = 0; $i < sizeof($feeds); ++$i) {
					if ($i == 0) {
						$items->where('feed_id', $feeds[$i]);
					} else {
						$items->orWhere('feed_id', $feeds[$i]);
					}
				}
				$items = $items->orderByDesc('id')->limit(25)->get();
				$result = [];
				foreach ($items as $item) {
					$result[] = new BizzFeedArticle($item->id, $full === "true");
				}
				$this->ajaxData($result);
			}
		}
	}

	private function validateToken(?string $token)
	{
		
		$bizz_url=Config::get('bizznews.bizzmail_url');
		
		$token = trim($token);
		if (!$token || strlen($token) == 0) {
			return false;
		} else {
			$result = file_get_contents($bizz_url.'/v1/external/account_by_token/' . $token, false, stream_context_create([
				'http' => ['ignore_errors' => true]
			]));
			if (isset($result)) {
				$json = json_decode($result, true);
				return strpos($http_response_header[0], '404') === false && $json['type'] == 'member';
			} else {
				return false;
			}
		}
	}
}