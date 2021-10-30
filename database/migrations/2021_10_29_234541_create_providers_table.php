<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvidersTable extends Migration
{
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('email', 100);
            $table->timestamps();

            $table->foreignId('direction_id')->constrained('directions');
        });
    }

    public function down()
    {
        Schema::dropIfExists('providers');
    }
}
