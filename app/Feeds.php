<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feeds extends Model
{
	protected $table = 'feeds';
	protected $dates = ['pubDate', 'date_added', 'date_updated'];

	public function usesTimestamps()
	{
		return false;
	}

	public function portals()
	{
		return $this->belongsToMany('App\Portals', 'portal_feeds', 'portal_id', 'feed_id');
	}

	public function user()
	{
		return $this->hasOne('App\User', 'id', 'user_id');
	}

	public function category()
	{
		return $this->hasOne('App\Category', 'id', 'category_id');
	}

	public function items()
	{
		return $this->hasMany('App\FeedItems', 'feed_id', 'id');
	}
}