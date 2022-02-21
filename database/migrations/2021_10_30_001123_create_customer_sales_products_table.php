<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerSalesProductsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customer_sales_products', function (Blueprint $table) {
			$table->foreignId('customer_sale_id')->nullable()
				->constrained('customer_sales')
				->onDelete('set null');

			$table->string('product_key')->nullable();
			$table->foreign('product_key')
				->references('key')
				->on('products')
				->onDelete('set null')
				->onUpdate('cascade');

			$table->mediumInteger('quantity');
			$table->float('total');
			$table->tinyInteger('returned')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('customer_sales_products');
	}
}
