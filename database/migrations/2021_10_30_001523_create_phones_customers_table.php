<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhonesCustomersTable extends Migration
{
	public function up()
	{
		Schema::create('phones_customers', function (Blueprint $table) {
			$table->foreignId('phone_id')->nullable()
				->constrained('phones')
				->onDelete('set null');

			$table->foreignId('customer_id')
				->constrained('customers')
				->onDelete('cascade');
			$table->string('phone_number');
		});
	}

	public function down()
	{
		Schema::dropIfExists('phones_customers');
	}
}
