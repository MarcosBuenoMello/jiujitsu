<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlunoAcessosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aluno_acessos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('aluno_id');
            $table->foreign('aluno_id')->references('id')
            ->on('alunos');

            $table->string('ip', 20);
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
        Schema::dropIfExists('aluno_acessos');
    }
}
