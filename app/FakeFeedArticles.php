<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FakeFeedArticles extends Model
{
	protected $table = 'fake_feed_articles';
	protected $primaryKey = 'item_id';

	public function usesTimestamps()
	{
		return false;
	}
}