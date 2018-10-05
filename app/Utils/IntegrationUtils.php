<?php

namespace App\Utils;

use Illuminate\Support\Facades\Input;

final class IntegrationUtils
{
	public static function BizzMailPackageValid()
	{
		if (Input::has('bizzmailtoken')) {
			@session_start();
			$_SESSION['bizzmailCode'] = Input::get('bizzmailtoken');
			return true;
		} else {
			@session_start();
			return isset($_SESSION['bizzmailCode']);
		}
	}
}