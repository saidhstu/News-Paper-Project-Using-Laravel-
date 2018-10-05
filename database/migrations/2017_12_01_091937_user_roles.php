<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserRoles extends Migration
{
	public function up()
	{
		Schema::create('users_role', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name');
		});
		Schema::table('users', function(Blueprint $table) {
			$table->unsignedInteger('role_id')->default('2');
			$table->foreign('role_id')->references('id')->on('users_role');
		});
	}

	public function down()
	{
		Schema::table('users', function(Blueprint $table) {
			$table->dropForeign('users_role_id_foreign');
			$table->dropColumn('role_id');
		});
		Schema::dropIfExists('users_role');
	}
}
