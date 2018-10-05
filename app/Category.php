<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	public const NEWS_ROOT = 1;
	public const DEFAULT = 12;
	protected $table = 'category';

	public function usesTimestamps()
	{
		return false;
	}

	public function parent()
	{
		return $this->hasOne('App\Category', 'id', 'parent_id');
	}

	public static function fetchRootCategories(): Collection
	{
		return Category::where('parent_id', null)->get();
	}

	public static function fetchChildCategories(int $rootCategory): Collection
	{
		return Category::where('parent_id', $rootCategory)->get();
	}
}