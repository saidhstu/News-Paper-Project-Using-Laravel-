<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemKeyType extends Model
{
	public const IGNORE = 1;
	public const ARTICLE_TITLE = 2;
	public const ARTICLE_LINK = 3;
	public const ARTICLE_DESCRIPTION = 4;
	public const ARTICLE_DATE = 5;
	public const ARTICLE_IMAGE = 6;
	public const ARTICLE_CATEGORY = 7;
	public const ARTICLE_AUTHOR = 8;
	public const ARTICLE_PRICE = 9;
	public const ARTICLE_EXTERNAL_ID = 10;
	public const ARTICLE_COMPANY = 11;
	public const ARTICLE_LANGUAGE = 12;
	public const ARTICLE_LOCATION = 13;
	protected $table = 'item_keytype';
}