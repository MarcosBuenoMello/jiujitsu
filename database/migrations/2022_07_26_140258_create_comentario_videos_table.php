<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComentarioVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comentario_videos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('posicao_id');
            $table->foreign('posicao_id')->references('id')
            ->on('posicaos');

            $table->text('comentario');
            $table->text('resposta');

            $table->unsignedBigInteger('aluno_id');
            $table->foreign('aluno_id')->references('id')
            ->on('alunos');

            $table->boolean('resposta_view')->default(false);

            // alter table comentario_videos add column resposta_view boolean default false;

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
        Schema::dropIfExists('comentario_videos');
    }
}
