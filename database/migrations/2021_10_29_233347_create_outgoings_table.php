<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutgoingsTable extends Migration
{
    public function up()
    {
        Schema::create('outgoings', function (Blueprint $table) {
            $table->id();
            $table->string('concept');
            $table->timestamps();

            $table->foreignId('user_id')->constrained('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('outgoings');
    }
}
