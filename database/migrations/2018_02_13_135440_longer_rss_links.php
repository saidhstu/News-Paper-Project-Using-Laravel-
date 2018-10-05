<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LongerRssLinks extends Migration
{
	public function up()
	{
		Schema::table('feed_items', function(Blueprint $table) {
			$table->text('link')->change();
		});
	}

	public function down()
	{
		Schema::table('feed_items', function(Blueprint $table) {
			$table->string('link')->change();
		});
	}
}