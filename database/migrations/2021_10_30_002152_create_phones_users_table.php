<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhonesUsersTable extends Migration
{
	public function up()
	{
		Schema::create('phones_users', function (Blueprint $table) {
			$table->foreignId('phone_id')->nullable()
				->constrained('phones')
				->onDelete('set null');

			$table->foreignId('user_id')
				->constrained('users')
				->onDelete('cascade');
			$table->string('phone_number', 12);
		});
	}

	public function down()
	{
		Schema::dropIfExists('phones_users');
	}
}
