<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessTable extends Migration
{
	public function up()
	{
		Schema::create('business', function (Blueprint $table) {
			$table->id();
			$table->string('name', 100);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('business');
	}
}
