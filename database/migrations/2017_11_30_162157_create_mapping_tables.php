<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMappingTables extends Migration
{
	public function up()
	{
		Schema::create('item_keytype', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('spec_path')->nullable()->default(null);
		});
		Schema::create('item_filtertype', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
		});
		Schema::create('feed_mapping', function(Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('feed_id');
			$table->string('path')->unique();
			$table->unsignedInteger('keytype_id');
			$table->unsignedInteger('filtertype_id');
			$table->foreign('feed_id')->references('id')->on('feeds')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('keytype_id')->references('id')->on('item_keytype')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('filtertype_id')->references('id')->on('item_filtertype')->onUpdate('cascade')->onDelete('cascade');
		});
		Schema::table('feed_items_meta', function(Blueprint $table) {
			$table->foreign('keytype_id')->references('id')->on('item_keytype')->onUpdate('cascade')->onDelete('cascade');
		});
	}

	public function down()
	{
		Schema::dropIfExists('feed_mapping');
		Schema::dropIfExists('item_filtertype');
		Schema::dropIfExists('item_keytype');
		Schema::table('feed_items_meta', function(Blueprint $table) {
			$table->dropForeign('feed_items_meta_keytype_id_foreign');
		});
	}
}