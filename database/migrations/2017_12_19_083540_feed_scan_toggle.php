<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FeedScanToggle extends Migration
{

	public function up()
	{
		Schema::table('feeds', function(Blueprint $table) {
			$table->boolean('scan')->default(true);
		});
	}

	public function down()
	{
		Schema::table('feeds', function(Blueprint $table) {
			$table->dropColumn('scan');
		});
	}
}
