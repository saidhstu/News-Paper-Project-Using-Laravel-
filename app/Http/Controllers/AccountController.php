<?php

namespace App\Http\Controllers;

use App\Utils\IntegrationUtils;
use App\Utils\Utils;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

final class AccountController extends AbstractController
{
	protected function getActiveMenuLink(): ?string
	{
		return 'account';
	}

	public function index()
	{
		return view('account.index');
	}

	public function modify()
	{
		return view('account.modify', [
			'name' => Auth::user()->name,
			'email' => Auth::user()->email
		]);
	}

	public function modifySubmit()
	{
		if (Auth::check()) {
			$validator = $this->validator(Input::all());
			if (!$validator->fails()) {
				$user = Auth::user();
				$user->name = Input::get('name');
				$user->email = Input::get('email');
				$user->save();
				return redirect('/account');
			} else {
				return redirect()->back()->withErrors($validator)->withInput();
			}
		}
	}

	private function validator(array $data): \Illuminate\Contracts\Validation\Validator
	{
		return Validator::make($data, [
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|max:255'
		]);
	}

	public function modifyPassword()
	{
		return view('account.modifyPassword');
	}

	public function modifyPasswordSubmit()
	{
		if (Auth::check()) {
			$validator = $this->passwordValidator(Input::all());
			if (!$validator->fails()) {
				$user = Auth::user();
				if (Hash::check(Input::get('old_password'), $user->password)) {
					$user->password = Hash::make(Input::get('password'));
					$user->save();
					return redirect()->to('/account');
				}
			} else {
				return redirect()->back()->withErrors($validator)->withInput();
			}
		}
	}

	private function passwordValidator(array $data): \Illuminate\Contracts\Validation\Validator
	{
		return Validator::make($data, [
			'old_password' => 'required',
			'password' => 'required|string|min:6|confirmed'
		]);
	}

	public function integration()
	{
		if (Auth::check()) {
			return view('account.integration', [
				'integration' => Utils::integrations(),
				'BizzMailStatus' => IntegrationUtils::BizzMailPackageValid()
			]);
		} else {
			return redirect()->to('/');
		}
	}
}