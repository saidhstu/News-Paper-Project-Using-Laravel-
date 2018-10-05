<?php

namespace App\Http\Controllers;

use App\Category;
use App\FakeFeedArticles;
use App\FeedItems;
use App\Feeds;
use App\Utils\Utils;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

final class FakeController extends AbstractController
{
	protected function getActiveMenuLink(): ?string
	{
		return "admin";
	}

	public function add()
	{
		if (Utils::isUserAdmin()) {
			return view("fake.add", ['categories' => Category::query()->whereNotNull('parent_id')->get()]);
		} else {
			return redirect()->to('/');
		}
	}

	public function addSubmit()
	{
		if (Utils::isUserAdmin()) {
			$feed = new Feeds();
			$feed->rss = '';
			$feed->url = '';
			$feed->name = Input::get('name');
			$feed->description = Input::get('description');
			$feed->copyright = Input::get('copyright');
			$feed->pubDate = Carbon::now();
			$feed->date_added = Carbon::now();
			$feed->date_updated = Carbon::now();
			$feed->user_id = Auth::id();
			$feed->category_id = Input::get('category');
			$feed->scan = false;
			$feed->public = false;
			if ($feed->save()) {
				return redirect()->to('/feeds/my');
			} else {
				return redirect()->back()->withInput(Input::all());
			}
		} else {
			return redirect()->to('/');
		}
	}

	public function edit(int $feedId)
	{
		if (Utils::isUserAdmin()) {
			if ($feed = Feeds::find($feedId)) {
				return view('fake.edit', [
					'feed' => $feed,
					'categories' => Category::query()->whereNotNull('parent_id')->get()
				]);
			} else {
				return redirect()->to('/feeds/list');
			}
		} else {
			return redirect()->to('/');
		}
	}

	public function editSubmit(int $feedId)
	{
		if (Utils::isUserAdmin()) {
			if ($feed = Feeds::find($feedId)) {
				$feed->name = Input::get('name');
				$feed->description = Input::get('description');
				$feed->category_id = Input::get('category');
				$feed->copyright = Input::get('copyright');
				$feed->save();
				return redirect()->to('/feeds/list/info/' . $feedId);
			} else {
				return redirect()->to('/feeds/list');
			}
		} else {
			return redirect()->to('/');
		}
	}

	public function addArticle(int $feedId)
	{
		if (Utils::isUserAdmin()) {
			return view('fake.addArticle', ['theFeed' => $feedId]);
		} else {
			return redirect()->to('/');
		}
	}

	public function addArticleSubmit()
	{
		if (Utils::isUserAdmin()) {
			if ($feed = Feeds::find(Input::get('feed'))) {
				$item = new FeedItems();
				$item->feed_id = $feed->id;
				$item->title = Input::get('title');
				$item->guid = $guid = uniqid('', true);
				$item->link = route('fakeFeedArticle', $guid);
				$item->description = Input::get('description');
				$item->pubDate = Carbon::now();
				$item->author = Input::get('author');
				$item->date_added = Carbon::now();
				if ($item->save()) {
					$content = new FakeFeedArticles();
					$content->item_id = $item->id;
					$content->content = htmlentities(Input::get('article'));
					$content->image_local = false;
					if ($imageURL = Input::get('imageURL')) {
						$content->image = $imageURL;
					} elseif ($imageFile = request()->file('imageFile')) {
						if ($imageFile->isValid()) {
							Storage::disk('local')->putFileAs("public/", $imageFile, $guid . '.png');
							$content->image = asset("storage/$guid.png");
							$content->image_local = true;
						} else {
							$item->delete();
							return redirect()->back()->withInput();
						}
					}
					if ($content->save()) {
						return redirect()->to('/');
					} else {
						$item->delete();
						return redirect()->back()->withInput();
					}
				} else {
					return redirect()->back()->withInput();
				}
			} else {
				return redirect()->to('/admin/');
			}
		} else {
			return redirect()->to('/');
		}
	}

	public function article(string $guid)
	{
		if ($item = FeedItems::where('guid', $guid)->get()) {
			if ($item->isNotEmpty()) {
				$item = $item->first();
				if ($article = $item->article) {
					return view('fake.article', [
						'item' => $item,
						'article' => $article
					]);
				}
			}
		}
		return redirect()->to('/');
	}

	public function manage(?int $feedId = null)
	{
		if (Utils::isUserAdmin()) {
			$vars = ['feeds' => Feeds::where('scan', false)->get()];
			if ($feedId) {
				if ($feed = Feeds::find($feedId)) {
					$vars['theFeed'] = $feed->id;
					$vars['articles'] = $feed->items;
				}
			}
			return view('fake.manageArticles', $vars);
		} else {
			return redirect()->to('/');
		}
	}

	public function editArticle(int $articleId)
	{
		if (Utils::isUserAdmin()) {
			if ($item = FeedItems::find($articleId)) {
				return view('fake.editArticle', [
					'item' => $item,
					'article' => $item->article
				]);
			} else {
				redirect()->back();
			}
		} else {
			return redirect()->to('/');
		}
	}

	public function editArticleSubmit(int $articleId)
	{
		if (Utils::isUserAdmin()) {
			if ($item = FeedItems::find($articleId)) {
				$article = $item->article;
				$article->content = htmlentities(Input::get('article'));
				$article->save();
				$item->title = Input::get('title');
				$item->description = Input::get('description');
				$item->author = Input::get('author');
				$item->save();
				return redirect()->to('/article/' . $item->guid);
			} else {
				return redirect()->to('/');
			}
		}
	}

	public function deleteArticle($articleId)
	{
		if (Utils::isUserAdmin()) {
			if ($item = FeedItems::find($articleId)) {
				$feedId = $item->feed_id;
				$item->delete();
				return redirect()->to('/admin/fake/manageArticle/' . $feedId);
			} else {
				return redirect()->to('/');
			}
		}
	}

	public function editImage(int $articleId)
	{
		if (Utils::isUserAdmin()) {
			if ($item = FeedItems::find($articleId)) {
				$article = $item->article;
				return view('fake.editImage', [
					'articleId' => $articleId,
					'hasImage' => $article->image !== null,
					'imageUrl' => $article->image,
					'imageLocal' => $article->image_local
				]);
			} else {
				return redirect()->to('/');
			}
		}
	}

	public function editArticleImageSubmit(int $articleId)
	{
		if (Utils::isUserAdmin()) {
			if ($item = FeedItems::find($articleId)) {
				$article = $item->article;
				$guid = $item->guid;
				if ($article->image_local) {
					Storage::disk('local')->delete('public/' . $item->guid . '.png');
				}
				if ($imageURL = Input::get('imageURL')) {
					$article->image = $imageURL;
					$article->image_local = false;
				} elseif ($imageFile = request()->file('imageFile')) {
					if ($imageFile->isValid()) {
						Storage::disk('local')->putFileAs("public/", $imageFile, $guid . '.png');
						$article->image = asset("storage/$guid.png");
						$article->image_local = true;
					} else {
						$item->delete();
						return redirect()->back()->withInput();
					}
				}
				$article->save();
				return redirect()->to('/admin/fake/editArticle/' . $articleId);
			} else {
				return redirect()->to('/');
			}
		}
	}

	public function editArticleImageDelete(int $articleId)
	{
		if (Utils::isUserAdmin()) {
			if ($item = FeedItems::find($articleId)) {
				$article = $item->article;
				if ($article->image_local) {
					Storage::disk('local')->delete('public/' . $item->guid . '.png');
				}
				$article->image = null;
				$article->image_local = false;
				$article->save();
				return redirect()->to('/admin/fake/editArticle/' . $articleId);
			} else {
				return redirect()->to('/');
			}
		}
	}
}