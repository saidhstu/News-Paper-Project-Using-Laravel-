<?php

namespace App\Jobs;

use App\Feeds;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FeedManager implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public function handle()
	{
		foreach (Feeds::all() as $feed) {
			if ($feed->scan) {
				try {
					FeedScanner::dispatch($feed);
				} catch (\Exception $e) {
				}
			}
		}
	}
}