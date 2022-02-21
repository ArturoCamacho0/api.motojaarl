<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
	public function up()
	{
		Schema::create('products', function (Blueprint $table) {
			$table->string('key', 30)->primary()->default('');
			$table->string('name', 100);
			$table->text('description');
			$table->mediumInteger('stock');
			$table->tinyInteger('minimum');
			$table->timestamps();

			$table->foreignId('category_id')->constrained('categories');
		});
	}

	public function down()
	{
		Schema::dropIfExists('products');
	}
}
