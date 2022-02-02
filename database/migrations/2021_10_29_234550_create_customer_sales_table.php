<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerSalesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customer_sales', function (Blueprint $table) {
			$table->id();
			$table->float('total');
			$table->float('discount');
			$table->timestamps();

			$table->foreignId('user_id')->constrained('users');
			$table->foreignId('customer_id')->constrained('customers');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('customer_sales');
	}
}
