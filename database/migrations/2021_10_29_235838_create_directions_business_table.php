<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectionsBusinessTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('directions_business', function (Blueprint $table) {
			$table->foreignId('direction_id')->nullable()
				->constrained('directions')
				->onDelete('set null');

			$table->foreignId('business_id')->nullable()
				->constrained('business')
				->onDelete('set null');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('directions_business');
	}
}
