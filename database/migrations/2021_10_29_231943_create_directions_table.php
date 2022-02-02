<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectionsTable extends Migration
{
	public function up()
	{
		Schema::create('directions', function (Blueprint $table) {
			$table->id();
			$table->string('street', 50);
			$table->string('number', 30);
			$table->string('state', 50);
			$table->string('cp', 10);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('directions');
	}
}
