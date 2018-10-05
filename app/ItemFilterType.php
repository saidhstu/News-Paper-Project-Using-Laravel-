<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemFilterType extends Model
{
	public const IGNORE = 1;
	protected $table = 'item_filtertype';
}