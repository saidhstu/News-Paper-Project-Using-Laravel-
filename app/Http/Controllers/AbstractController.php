<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;

abstract class AbstractController extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public function __construct()
	{
		$activeLink = $this->getActiveMenuLink();
		View::share('activeLink', $activeLink === null ? '' : $activeLink);
	}

	protected function getActiveMenuLink(): ?string
	{
		return null;
	}
}