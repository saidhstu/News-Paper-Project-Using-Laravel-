<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FeedReporting extends Migration
{
	public function up()
	{
		Schema::table('feeds', function(Blueprint $table) {
			$table->unsignedInteger('reported')->default(0);
		});
	}

	public function down()
	{
		Schema::table('feeds', function(Blueprint $table) {
			$table->dropColumn('reported');
		});
	}
}
