<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('cidade_id');
            $table->foreign('cidade_id')->references('id')
            ->on('cidades');

            $table->unsignedBigInteger('modalidade_id');
            $table->foreign('modalidade_id')->references('id')
            ->on('modalidades');

            $table->string('horario', 5);
            $table->string('dia_semana', 10);
            $table->string('sexo', 1);

            $table->boolean('status');
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
        Schema::dropIfExists('agendas');
    }
}
