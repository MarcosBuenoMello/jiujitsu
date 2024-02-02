<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContribuicaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contribuicaos', function (Blueprint $table) {
            $table->id();

            $table->decimal('valor', 10, 2);
            $table->unsignedBigInteger('aluno_id');
            $table->foreign('aluno_id')->references('id')
            ->on('alunos');

            $table->string('transacao_id', 30);
            $table->string('status', 30);

            $table->string('cpf', 30);
            $table->string('email', 80);

            $table->text('qr_code_base64');
            $table->text('qr_code');
            $table->enum('forma_pagamento', ['pix', 'dinheiro', 'outros']);
            
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
        Schema::dropIfExists('contribuicaos');
    }
}
