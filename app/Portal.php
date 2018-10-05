<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Portal extends Model
{
	protected $table = 'portal';

	public function feeds()
	{
		return $this->belongsToMany('App\Feeds', 'portal_feeds', 'portal_id', 'feed_id');
	}

	public function usesTimestamps()
	{
		return false;
	}
}