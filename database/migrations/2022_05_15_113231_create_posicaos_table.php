<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosicaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posicaos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('categoria_id');
            $table->foreign('categoria_id')->references('id')
            ->on('categorias');

            $table->string('imagem', 30);
            $table->string('video_temp', 30);

            $table->string('nome', 50);
            $table->string('descricao');
            $table->unsignedBigInteger('faixa_id');
            $table->foreign('faixa_id')->references('id')
            ->on('faixas');

            $table->boolean('status');

            $table->unsignedBigInteger('aluno_id');
            $table->foreign('aluno_id')->references('id')
            ->on('alunos');
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
        Schema::dropIfExists('posicaos');
    }
}
