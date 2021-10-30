<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesProductsTable extends Migration
{
    public function up()
    {
        Schema::create('sales_products', function (Blueprint $table) {
            $table->foreignId('sale_id')->constrained('sales');
            $table->string('product_key');
            $table->foreign('product_key')->references('key')->on('products');

            $table->mediumInteger('quantity');
            $table->float('total');
            $table->tinyInteger('returned')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_products');
    }
}
