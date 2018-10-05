<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserIntegration extends Model
{
	protected $table = 'user_integration';
	protected $primaryKey = 'user_id';

	public function usesTimestamps()
	{
		return false;
	}

	public function user()
	{
		return $this->hasOne('App\User', 'id', 'user_id');
	}
}