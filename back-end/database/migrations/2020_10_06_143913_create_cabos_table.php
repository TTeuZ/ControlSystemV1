<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCabosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cabos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->string('tipo');
            $table->boolean('situacao')->default(false);
            $table->string('user_name_created');
            $table->string('user_name_updated')->default('');
            $table->bigInteger('auto_eletrica_id')->unsigned();
            $table->foreign('auto_eletrica_id')->references('id')->on('auto_eletricas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cabos');
    }
}
