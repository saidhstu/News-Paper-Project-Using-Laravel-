<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedMapping extends Model
{
	protected $table = 'feed_mapping';

	public function usesTimestamps()
	{
		return false;
	}
}