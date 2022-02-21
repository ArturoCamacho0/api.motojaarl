<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhonesProvidersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('phones_providers', function (Blueprint $table) {
			$table->foreignId('provider_id')
				->constrained('providers')
				->onDelete('cascade');
			$table->foreignId('phone_id')->nullable()
				->constrained('phones')
				->onDelete('set null');
			$table->string('phone_number', 12);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('phones_providers');
	}
}
