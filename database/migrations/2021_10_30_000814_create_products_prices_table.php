<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsPricesTable extends Migration
{
	public function up()
	{
		Schema::create('products_prices', function (Blueprint $table) {
			$table->string('product_key')->nullable();
			$table->foreign('product_key')
				->references('key')
				->on('products')
				->onDelete('set null')
				->onUpdate('cascade');

			$table->foreignId('price_id')->nullable()
				->constrained('prices')
				->onDelete('cascade');


			$table->float('price');
		});
	}

	public function down()
	{
		Schema::dropIfExists('products_prices');
	}
}
