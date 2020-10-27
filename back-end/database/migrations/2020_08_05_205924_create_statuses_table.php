<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('info');
            $table->boolean('flag')->default(false);
            $table->bigInteger('equipamento_id')->unsigned();
            $table->foreign('equipamento_id')->references('id')->on('equipamentos')->onDelete('cascade');
            $table->bigInteger('status_enum_id')->unsigned();
            $table->foreign('status_enum_id')->references('id')->on('status_enums')->onDelete('cascade');
            $table->string('user_name_created');
            $table->string('user_name_updated')->default('');
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
        Schema::dropIfExists('statuses');
    }
}
