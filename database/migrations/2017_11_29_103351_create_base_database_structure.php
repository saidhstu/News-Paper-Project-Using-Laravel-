<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaseDatabaseStructure extends Migration
{
	public function up()
	{
		Schema::create('category', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->unsignedInteger('parent_id')->nullable();
			$table->foreign('parent_id')->references('id')->on('category')->onUpdate('cascade')->onDelete('cascade');
		});
		Schema::create('feeds', function(Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('category_id');
			$table->unsignedInteger('user_id');
			$table->string('rss');
			$table->string('url');
			$table->string('name');
			$table->string('version')->nullable();
			$table->string('copyright')->nullable();
			$table->string('language')->nullable();
			$table->mediumText('description');
			$table->dateTime('pubDate');
			$table->dateTime('date_added');
			$table->dateTime('date_updated');
			$table->boolean('public');
			$table->boolean('disabled')->default(false);
			$table->foreign('category_id')->references('id')->on('category');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
		});
		Schema::create('feed_items', function(Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('feed_id');
			$table->string('title')->nullable();
			$table->string('link')->nullable();
			$table->string('guid');
			$table->mediumText('description')->nullable();
			$table->string('author', 1536)->nullable();
			$table->dateTime('pubDate');
			$table->dateTime('date_added');
			$table->foreign('feed_id')->references('id')->on('feeds')->onUpdate('cascade')->onDelete('cascade');
		});
		Schema::create('feed_items_meta', function(Blueprint $table) {
			$table->unsignedInteger('item_id');
			$table->unsignedInteger('keytype_id');
			$table->mediumText('value');
			$table->foreign('item_id')->references('id')->on('feed_items')->onUpdate('cascade')->onDelete('cascade');
		});
	}

	public function down()
	{
		Schema::dropIfExists('feed_items_meta');
		Schema::dropIfExists('feed_items');
		Schema::dropIfExists('feeds');
		Schema::dropIfExists('category');
	}
}