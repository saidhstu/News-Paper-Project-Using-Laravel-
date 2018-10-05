<?php

namespace App\Http\Controllers;

use App\Category;
use App\FeedItems;
use App\FeedMapping;
use App\Feeds;
use App\ItemFilterType;
use App\ItemKeyType;
use App\Jobs\FeedScanner;
use App\User;
use App\Utils\FeedParser;
use App\Utils\MappingHelper;
use App\Utils\Utils;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

final class FeedsController extends AbstractController
{
	protected function getActiveMenuLink(): ?string
	{
		return 'feeds';
	}

	public function index()
	{
		return view('feeds.index');
	}

	public function addFeed()
	{
		return view('feeds.add', ['error' => 0]);
	}

	public function addFeedSubmit()
	{
		if (Auth::check()) {
			$validator = $this->validator(Input::all());
			if (!$validator->fails()) {
				return redirect()->to('/feeds/add/parsed/' . str_replace('/', '_', base64_encode(Input::get('address'))));
			} else {
				return redirect()->back()->withErrors($validator)->withInput();
			}
		} else {
			return redirect()->back();
		}
	}

	private function validator(array $data): \Illuminate\Validation\Validator
	{
		return Validator::make($data, [
			'address' => 'required|url|max:255',
		]);
	}

	public function addFeedParsed(string $address)
	{
		if (Auth::check()) {
			$address = base64_decode(str_replace('_', '/', $address));
			$validator = $this->validator(['address' => $address]);
			if (!$validator->fails()) {
				$parser = new FeedParser($address);
				if (!$parser->parse()) {
					return view('feeds.add', ['address' => $address, 'error' => 2]);
				} else {
					$helper = new MappingHelper($parser);
					$helper->constructArray();
					$specpaths = [
						'[item]/title',
						'[item]/link',
						'[item]/description',
						'[item]/pubdate',
						'[item]/enclose',
						'[item]/author'
					];
					$specpaths = array_map(function($value) use ($parser) {
						return str_replace('[item]', $parser->findItemsPath() . '/item', $value);
					}, $specpaths);
					return view('feeds.addParsed', [
						'rss' => $parser->getRss(),
						'categories' => Category::query()->whereNotNull('parent_id')->get(),
						'items' => $helper->getArray(),
						'keytypes' => ItemKeyType::all(),
						'filtertypes' => ItemFilterType::all(),
						'users' => User::all(),
						'itempath' => $parser->findItemsPath(),
						'specpaths' => $specpaths
					]);
				}
			}
		}
		return redirect()->to('/');
	}

	public function addFeedParsedSubmit()
	{
		if (Auth::check()) {
			$rss = base64_decode(str_replace('_', '/', Input::get('rss')));
			$public = Input::get('public', false);
			if (Utils::isUserAdmin() || (!Utils::isUserAdmin() && !$public)) {
				if ($public && Feeds::where('rss', $rss)->where('public')->limit(1)->get()->isNotEmpty()) {
					return view('feeds.add', ['address' => $rss, 'error' => 1]);
				} else {
					foreach (Feeds::where('rss', $rss)->get() as $feed) {
						if ($public) {
							$feed->disabled = true;
							$feed->save();
						}
					}
					unset($feed);
					$parser = new FeedParser($rss);
					$parser->parse();
					$rootPath = $parser->resolvePath(FeedParser::SEPARATOR);
					$feed = new Feeds();
					$feed->category_id = Input::get('category');
					$feed->user_id = Input::get('owner');
					$feed->rss = $parser->getRss();
					$feed->name = Input::get('title');
					if (isset($rootPath['attributes']['version'])) {
						$feed->version = $rootPath['attributes']['version'];
					}
					if ($link = $parser->resolvePath('rss' . FeedParser::SEPARATOR . 'channel' . FeedParser::SEPARATOR . 'link')) {
						$feed->url = $link['text'];
						$feed->url = $link['text'];
					}
					if ($copyright = $parser->resolvePath('rss' . FeedParser::SEPARATOR . 'channel' . FeedParser::SEPARATOR . 'copyright')) {
						$feed->copyright = $copyright['text'];
					}
					if ($language = $parser->resolvePath('rss' . FeedParser::SEPARATOR . 'channel' . FeedParser::SEPARATOR . 'language')) {
						$feed->language = $language['text'];
					}
					$feed->description = Input::get('description');
					if ($date = $parser->resolvePath('rss/channel/lastbuilddate')) {
						$feed->pubDate = Carbon::parse($date['text']);
					} else {
						$feed->pubDate = Carbon::parse($parser->resolvePath('rss/channel/pubdate')['text']);
					}
					$feed->date_added = Carbon::now();
					$feed->date_updated = Carbon::now();
					if ($public && Utils::isUserAdmin()) {
						$feed->public = true;
					} else {
						$feed->public = false;
					}
					$feed->save();
					for ($i = 0; $i < sizeof(Input::get('keytype')); ++$i) {
						$path = array_keys(Input::get('keytype'))[$i];
						$keytype = Input::get('keytype')[$path];
						$filtertype = Input::get('filtertype')[$path];
						if ($keytype != ItemKeyType::IGNORE) {
							$mapping = new FeedMapping();
							$mapping->feed_id = $feed->id;
							$mapping->path = $path;
							$mapping->keytype_id = $keytype;
							$mapping->filtertype_id = $filtertype;
							$mapping->is_attribute = isset(Input::get(['attributes'])[$path]);
							$mapping->save();
						}
					}
					FeedScanner::dispatch($feed);
				}
			}
		}
		return redirect()->to('/feeds/add');
	}

	public function feedList()
	{
		if (Utils::isUserAdmin()) {
			return view('feeds.list', ['feeds' => Feeds::all()]);
		} else {
			return view('feeds.list', ['feeds' => Feeds::where('user_id', Auth::id())->orWhere('public', true)->get()]);
		}
	}

	public function feedInfo(int $id)
	{
		$feed = Feeds::find($id);
		if ($feed->public || Utils::isUserAdmin() || (Auth::check() && $feed->user_id === Auth::id())) {
			return view('feeds.info', [
				'feed' => $feed,
				'articles' => FeedItems::where('feed_id', $feed->id)->count(),
			]);
		} else {
			return redirect()->to('/feeds/list');
		}
	}


	public function feedSetPublic(int $id)
	{
		if (Utils::isUserAdmin()) {
			if ($feed = Feeds::find($id)) {
				$feed->public = $feed->public ? false : true;
				$feed->save();
			} else {
				return redirect()->to('/feeds');
			}
		}
		return redirect()->back();
	}

	public function deleteFeed(int $id)
	{
		if (Auth::check()) {
			$feed = Feeds::where('id', $id)->where('user_id', Auth::id())->first();
			if ($feed != null) {
				$feed->delete();
				return redirect()->to('/feeds');
			}
		}
		return redirect()->to('/');
	}

	public function modifyFeed(int $feedId)
	{
		if (Auth::check()) {
			if ($feed = Feeds::find($feedId)) {
				$parser = new FeedParser($feed->rss);
				if (!$parser->parse()) {
					Utils::flashError(trans(''));
					// TODO return error
					// return view('feeds.add', ['address' => $f, 'error' => 2]);
				} else {
					$helper = new MappingHelper($parser);
					$helper->constructArray();
					return view('feeds.modifyFeed', [
						'feed' => $feed,
						'currentMappings' => FeedMapping::where('feed_id', $feed->id)->get(),
						'rss' => $parser->getRss(),
						'categories' => Category::query()->whereNotNull('parent_id')->get(),
						'items' => $helper->getArray(),
						'keytypes' => ItemKeyType::all(),
						'filtertypes' => ItemFilterType::all(),
					]);
				}
			}
		}
		return redirect()->to('/');
	}

	public function modifyFeedSubmit(int $feedId)
	{
		if (Auth::check()) {
			$feed = Feeds::find($feedId);
			$feed->category_id = Input::get('category');
			$feed->name = Input::get('title');
			$feed->description = Input::get('description');
			$feed->date_updated = Carbon::now();
			$feed->save();
			for ($i = 0; $i < sizeof(Input::get('currentKeytype')); ++$i) {
				$path = array_keys(Input::get('currentKeytype'))[$i];
				$mapping = FeedMapping::where('path', $path)->limit(1)->get()->first();
				$keytype = Input::get('currentKeytype')[$path];
				if ($keytype == ItemKeyType::IGNORE) {
					$mapping->delete();
				} else {
					$mapping->keytype_id = $keytype;
					$mapping->filtertype_id = Input::get('currentFiltertype')[$path];
					$mapping->save();
				}
			}
			for ($i = 0; $i < sizeof(Input::get('keytype')); ++$i) {
				$path = array_keys(Input::get('keytype'))[$i];
				$keytype = Input::get('keytype')[$path];
				$filtertype = Input::get('filtertype')[$path];
				if ($keytype != ItemKeyType::IGNORE) {
					$mapping = new FeedMapping();
					$mapping->feed_id = $feed->id;
					$mapping->path = $path;
					$mapping->keytype_id = $keytype;
					$mapping->filtertype_id = $filtertype;
					$mapping->is_attribute = isset(Input::get(['attributes'])[$path]);
					$mapping->save();
				}
			}
			return redirect()->to('/feeds');
		}
		return redirect()->to('/');
	}

	public function reportFeed(int $feedId)
	{
		if (Auth::check()) {
			if ($feed = Feeds::find($feedId)) {
				++$feed->reported;
				$feed->save();
				return redirect()->back();
			}
		}
		return redirect()->to('/');
	}
}
