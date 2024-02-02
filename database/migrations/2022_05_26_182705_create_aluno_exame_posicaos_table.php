<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlunoExamePosicaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aluno_exame_posicaos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('aluno_exame_id');
            $table->foreign('aluno_exame_id')->references('id')
            ->on('aluno_exames');

            $table->unsignedBigInteger('posicao_id');
            $table->foreign('posicao_id')->references('id')
            ->on('posicaos');

            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('aluno_exame_posicaos');
    }
}
