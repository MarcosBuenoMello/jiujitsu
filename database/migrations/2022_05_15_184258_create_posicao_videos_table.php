<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosicaoVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posicao_videos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('posicao_id');
            $table->foreign('posicao_id')->references('id')
            ->on('posicaos');

            $table->string('url', 150);

            $table->enum('tipo', ['google_drive', 'youtube']);
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
        Schema::dropIfExists('posicao_videos');
    }
}
