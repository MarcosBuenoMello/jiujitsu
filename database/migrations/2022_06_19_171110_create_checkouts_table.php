<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkouts', function (Blueprint $table) {
            $table->id();

            $table->string('nome', 30);
            $table->string('sobre_nome', 30);
            $table->string('email', 80);
            $table->string('documento', 20);

            $table->unsignedBigInteger('aluno_id');
            $table->foreign('aluno_id')->references('id')
            ->on('alunos');

            $table->string('transacao_id', 30);
            $table->string('status', 30);
            $table->decimal('valor', 10, 2);
            $table->enum('forma_pagamento', ['pix', 'cartao']);
            $table->text('qr_code_base64');
            $table->text('qr_code');

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
        Schema::dropIfExists('checkouts');
    }
}
