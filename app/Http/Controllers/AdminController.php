<?php

namespace App\Http\Controllers;

use App\Category;
use App\Feeds;
use App\Portal;
use App\PortalFeeds;
use App\User;
use App\UsersRole;
use App\Utils\Utils;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

final class AdminController extends AbstractController
{
	protected function getActiveMenuLink(): ?string
	{
		return "admin";
	}

	public function index()
	{
		return view('admin.index');
	}

	public function category()
	{
		return Utils::isUserAdmin() ? view('admin.category', [
			'rootCategories' => Category::whereNull('parent_id')->get(),
			'categories' => Category::whereNotNull('parent_id')->get(),
		]) : redirect()->to('/');
	}

	public function categorySubmit()
	{
		if (Utils::isUserAdmin()) {
			foreach (Input::get('name') as $keyName => $name) {
				$parent = Input::get('parentCategory')[$keyName];
				$id = intval(substr($keyName, 4));
				$category = Category::find($id);
				$category->name = $name;
				$category->parent_id = $parent == -1 ? null : $parent;
				$category->save();
			}
			return redirect()->to('/admin/category');
		} else {
			return redirect()->to('/');
		}
	}

	public function categoryAdd()
	{
		return Utils::isUserAdmin() ? view('admin.categoryAdd') : redirect()->to('/');
	}

	public function categoryAddSubmit()
	{
		if (Utils::isUserAdmin()) {
			$category = new Category();
			$category->name = Input::get('name');
			$category->parent_id = Category::NEWS_ROOT;
			$category->save();
			return redirect()->to('/admin/category');
		} else {
			return redirect()->to('/');
		}
	}

	public function categoryInfo(int $categoryId)
	{
		if (Utils::isUserAdmin()) {
			return view('admin.categoryInfo', ['category' => Category::find($categoryId)]);
		} else {
			return redirect()->to('/');
		}
	}

	public function categoryDelete(int $categoryId)
	{
		if (Utils::isUserAdmin()) {
			Category::find($categoryId)->delete();
			return redirect()->to('/admin/category');
		} else {
			return redirect()->to('/');
		}
	}

	public function portal()
	{
		if (Utils::isUserAdmin()) {
			return view('admin.portal', ['portals' => Portal::all()]);
		} else {
			return redirect()->to('/');
		}
	}

	public function portalAdd()
	{
		if (Utils::isUserAdmin()) {
			return view('admin.portalAdd');
		} else {
			return redirect()->to('/');
		}
	}

	public function portalAddSubmit()
	{
		if (Utils::isUserAdmin()) {
			$portal = new Portal();
			$portal->name = Input::get('name');
			$portal->save();
			return redirect()->to('/admin/portal/info/' . $portal->id);
		} else {
			return redirect()->to('/');
		}
	}

	public function portalInfo(int $id)
	{
		if (Utils::isUserAdmin()) {
			return view('admin.portalInfo', ['portal' => Portal::find($id)]);
		} else {
			return redirect()->to('/');
		}
	}

	public function portalInfoAddFeed(int $portalId)
	{
		if (Utils::isUserAdmin()) {
			return view('admin.portalInfoAddFeed', [
				'portalId' => $portalId,
				'feeds' => Feeds::query()
					->leftJoin('portal_feeds', 'feeds.id', '=', 'portal_feeds.feed_id')
					->whereNull('portal_feeds.feed_id')
					->orWhere(function(Builder $query) use ($portalId) {
						$query->whereNull('portal_feeds.feed_id');
						$query->where('portal_feeds.portal_id', $portalId);
					})->get(),
			]);
		} else {
			return redirect()->to('/');
		}
	}

	public function portalInfoAddFeedSubmit(int $portalId)
	{
		if (Utils::isUserAdmin()) {
			if ($portal = Portal::find($portalId)) {
				foreach (Input::get('addFeeds') as $feed => $unused) {
					$portalFeed = new PortalFeeds();
					$portalFeed->portal_id = $portal->id;
					$portalFeed->feed_id = $feed;
					$portalFeed->save();
				}
				return redirect()->to('admin/portal/info/' . $portal->id);
			} else {
				return redirect()->to('admin/portal');
			}
		} else {
			return redirect()->to('/');
		}
	}

	public function portalInfoDeleteFeed(int $portalId, int $feedId)
	{
		if (Utils::isUserAdmin()) {
			if (Portal::find($portalId)) {
				if (Feeds::find($feedId)) {
					DB::statement("DELETE FROM `portal_feeds` WHERE `portal_id` = $portalId AND `feed_id` = $feedId");
				}
				return redirect()->to('admin/portal/info/' . $portalId);
			} else {
				return redirect()->to('admin/portal');
			}
		} else {
			return redirect()->to('/');
		}
	}

	public function users()
	{
		if (Utils::isUserAdmin()) {
			return view('admin.users', ['users' => User::all()]);
		} else {
			return redirect()->to('/');
		}
	}

	public function userInfo(int $userId)
	{
		if (Utils::isUserAdmin()) {
			if ($user = User::find($userId)) {
				return view('admin.user', [
					'user' => $user,
					'roles' => UsersRole::all()
				]);
			} else {
				return redirect()->to('/admin/users');
			}
		} else {
			return redirect()->to('/');
		}
	}

	public function userRoleChange(int $userId)
	{
		if (Utils::isUserAdmin()) {
			if ($user = User::find($userId)) {
				$user->role_id = Input::get('role');
				$user->save();
				return redirect()->to('/admin/users/' . $userId);
			} else {
				return redirect()->to('/admin/users');
			}
		} else {
			return redirect()->to('/');
		}
	}

	public function userDelete(int $userId)
	{
		if (Utils::isUserAdmin()) {
			if ($user = User::find($userId)) {
				$user->delete();
			}
			return redirect()->to('/admin/users');
		} else {
			return redirect()->to('/');
		}
	}
}