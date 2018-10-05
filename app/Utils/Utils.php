<?php

namespace App\Utils;

use App\UserIntegration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

final class Utils
{
	public static function isArrayAssoc(array $array)
	{
		if ($array === array()) {
			return false;
		} else {
			return array_keys($array) !== range(0, count($array) - 1);
		}
	}

	public static function stringEndsWith(string $haystack, string $needle)
	{
		$length = strlen($needle);
		return $length === 0 || (substr($haystack, -$length) === $needle);
	}

	public static function isUserAdmin()
	{
		return Auth::check() && Auth::user()->role->id === 3;
	}

	public static function integrations()
	{
		if (Auth::check() && !Auth::user()->integration) {
			$integration = new UserIntegration();
			$integration->user_id = Auth::id();
			$integration->save();
			Auth::user()->integration = $integration;
		}
		return Auth::user()->integration;
	}

	public static function flashError(string $message)
	{
		self::flashMessage('Error', $message);
	}

	public static function flashWarning(string $message)
	{
		self::flashMessage('Warning', $message);
	}

	public static function flashSuccess(string $message)
	{
		self::flashMessage('Success', $message);
	}

	public static function flashInfo(string $message)
	{
		self::flashMessage('Info', $message);
	}

	private static function flashMessage(string $type, string $message)
	{
		Session::put("msg$type", $message);
	}

	public static function flashReset()
	{
		Session::forget('msgError');
		Session::forget('msgWarning');
		Session::forget('msgSuccess');
		Session::forget('msgInfo');
	}
}