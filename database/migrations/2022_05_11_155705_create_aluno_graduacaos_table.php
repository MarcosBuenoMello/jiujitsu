<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlunoGraduacaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aluno_graduacaos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('aluno_id');
            $table->foreign('aluno_id')->references('id')
            ->on('alunos');

            $table->unsignedBigInteger('faixa_id');
            $table->foreign('faixa_id')->references('id')
            ->on('faixas');

            $table->date('data');
            $table->integer('grau');
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
        Schema::dropIfExists('aluno_graduacaos');
    }
}
