<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlunoExamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aluno_exames', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('aluno_id');
            $table->foreign('aluno_id')->references('id')
            ->on('alunos');

            $table->unsignedBigInteger('exame_id');
            $table->foreign('exame_id')->references('id')
            ->on('exame_faixas');

            $table->string('observacao', 200);
            $table->boolean('status')->default(false);
            $table->enum('resultado', ['aprovado', 'reprovado']);

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
        Schema::dropIfExists('aluno_exames');
    }
}
