<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogEquipamentoAutoEletricasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_equipamento_auto_eletricas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->string('acao');
            $table->string('tipo');
            $table->string('user_name');
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
        Schema::dropIfExists('log_equipamento_auto_eletricas');
    }
}
