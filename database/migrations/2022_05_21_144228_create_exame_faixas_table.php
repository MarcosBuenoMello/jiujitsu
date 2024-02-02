<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExameFaixasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exame_faixas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('faixa_id');
            $table->foreign('faixa_id')->references('id')
            ->on('faixas');

            $table->string('descricao', 255);
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
        Schema::dropIfExists('exame_faixas');
    }
}
