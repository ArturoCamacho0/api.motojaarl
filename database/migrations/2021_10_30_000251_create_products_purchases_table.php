<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsPurchasesTable extends Migration
{
	public function up()
	{
		Schema::create('products_purchases', function (Blueprint $table) {
			$table->string('product_key')->nullable();
			$table->foreign('product_key')
				->references('key')
				->on('products')
				->onDelete('set null')
				->onUpdate('cascade');

			$table->foreignId('purchase_id')
				->nullable()
				->constrained('purchases')
				->onDelete('cascade');

			$table->mediumInteger('quantity');
			$table->float('total');
			$table->tinyInteger('returned')->default(0);
		});
	}

	public function down()
	{
		Schema::dropIfExists('products_purchases');
	}
}
