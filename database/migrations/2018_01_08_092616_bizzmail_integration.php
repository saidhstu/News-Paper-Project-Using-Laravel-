<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BizzmailIntegration extends Migration
{
	public function up()
	{
		Schema::create('user_integration', function(BluePrint $table) {
			$table->unsignedInteger('user_id');
			$table->string('bizzmail')->nullable()->default(null);
			$table->foreign('user_id')->references('id')->on('users');
		});
	}

	public function down()
	{
		Schema::dropIfExists('user_integration');
	}
}
