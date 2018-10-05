<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PortalFeeds extends Model
{
	protected $table = 'portal_feeds';

	public function usesTimestamps()
	{
		return false;
	}
}