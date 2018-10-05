<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FakefeedsArticle extends Migration
{
	public function up()
	{
		Schema::create('fake_feed_articles', function(Blueprint $table) {
			$table->unsignedInteger('item_id');
			$table->mediumText('content');
			$table->string('image')->nullable();
			$table->boolean('image_local');
			$table->foreign('item_id')->references('id')->on('feed_items')->onUpdat('cascade')->onDelete('cascade');
		});
	}

	public function down()
	{
		Schema::dropIfExists('fake_feed_articles');
	}
}