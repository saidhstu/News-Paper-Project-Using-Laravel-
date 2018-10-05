<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Portals extends Migration
{

	public function up()
	{
		Schema::create('portal', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
		});
		Schema::create('portal_feeds', function(Blueprint $table) {
			$table->unsignedInteger('portal_id');
			$table->unsignedInteger('feed_id');
			$table->foreign('portal_id')->references('id')->on('portal')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('feed_id')->references('id')->on('feeds')->onUpdate('cascade')->onDelete('cascade');
		});
	}

	public function down()
	{
		Schema::dropIfExists('portal_feeds');
		Schema::dropIfExists('portal');
	}
}
