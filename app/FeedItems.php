<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedItems extends Model
{
	protected $table = 'feed_items';
	protected $dates = ['pubDate', 'date_added'];

	public function usesTimestamps()
	{
		return false;
	}

	public function feed()
	{
		return $this->hasOne('App\Feeds', 'id', 'feed_id');
	}

	public function article()
	{
		return $this->hasOne('App\FakeFeedArticles', 'item_id', 'id');
	}
}