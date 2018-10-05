<?php

namespace App\Http\Controllers;

use Config;
use App\FeedItems;
use App\FeedItemsMeta;
use App\ItemKeyType;
use App\Utils\Utils;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

final class IntegrationController extends AbstractController
{
	protected function getActiveMenuLink(): ?string
	{
		return 'account';
	}

	public function BizzMail()
	{
		
		if (Auth::check()) {
			return view('account.integrationBizzMail', ['integration' => Utils::integrations()]);
		} else {
			return redirect()->to('/');
		}
	}

	public function BizzMailSubmit()
	{
		if (Auth::check()) {
			if ($token = Input::get('token')) {
				$integrations = Utils::integrations();
				$integrations->bizzmail = $token;
				if ($integrations->save()) {
					return redirect()->to('/account/integration');
				} else {
					return redirect()->back()->withInput();
				}
			} else {
				return redirect()->back()->withInput();
			}
		} else {
			return redirect()->to('/');
		}
	}

	public function BizzMailSearchSubmit()
	{

		$bizz_url=Config::get('bizznews.bizzmail_url');	
		
		
		@session_start();
		if (isset($_SESSION['bizzmailCode'])) {
			foreach (Input::get('item') as $itemId => $unused) {
				if ($item = FeedItems::find($itemId)) {
					$imageMeta = FeedItemsMeta::where('item_id', $itemId)->where('keytype_id', ItemKeyType::ARTICLE_IMAGE)->first();
					$context = stream_context_create([
						'http' => [
							'method' => 'POST',
							'header' => ['Content-Type: application/json'],
							'content' => json_encode([
								'external_id' => $item->id,
								'external_feed_id' => $item->feed_id,
								'pubdate' => $item->pubDate->toDateTimeString(),
								'title' => $item->title,
								'link' => $item->link,
								'summary' => $item->description,
								'content' => [
									'image' => $imageMeta ? $imageMeta->value : null
								]
							])
						],
					]);
					$result = @file_get_contents($bizz_url.'/v1/article/usertoken/' . $_SESSION['bizzmailCode'], false, $context);
					if (!$result) {
						Utils::flashError(trans('index.bizzmail.error'));
					}
				}
			}
			// unset($_SESSION['bizzmailCode']);
			// TODO: flash message success
			Utils::flashSuccess(trans('index.bizzmail.success'));
		}
		return redirect()->to('/');
	}
}