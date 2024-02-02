<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExameFaixaPosicaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exame_faixa_posicaos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('exame_id');
            $table->foreign('exame_id')->references('id')
            ->on('exame_faixas');

            $table->unsignedBigInteger('posicao_id');
            $table->foreign('posicao_id')->references('id')
            ->on('posicaos');

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
        Schema::dropIfExists('exame_faixa_posicaos');
    }
}
