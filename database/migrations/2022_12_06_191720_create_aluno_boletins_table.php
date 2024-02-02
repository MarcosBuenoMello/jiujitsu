<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlunoBoletinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aluno_boletins', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('aluno_id');
            $table->foreign('aluno_id')->references('id')
            ->on('alunos');

            $table->unsignedBigInteger('escola_id');
            $table->foreign('escola_id')->references('id')
            ->on('escolas');

            $table->text('observacao');
            $table->string('img', 30);

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
        Schema::dropIfExists('aluno_boletins');
    }
}
