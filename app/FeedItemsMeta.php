<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedItemsMeta extends Model
{
	protected $table = 'feed_items_meta';

	public function usesTimestamps()
	{
		return false;
	}
}