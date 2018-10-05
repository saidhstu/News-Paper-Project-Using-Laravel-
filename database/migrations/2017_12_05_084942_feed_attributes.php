<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FeedAttributes extends Migration
{
	public function up()
	{
		Schema::table('feed_mapping', function(Blueprint $table) {
			$table->boolean('is_attribute')->default(false);
		});
	}

	public function down()
	{
		Schema::table('feed_mapping', function(Blueprint $table) {
			$table->dropColumn('is_attribute');
		});
	}
}
