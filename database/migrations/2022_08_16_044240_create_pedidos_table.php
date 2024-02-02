<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('aluno_id');
            $table->foreign('aluno_id')->references('id')
            ->on('alunos');

            $table->decimal('total');
            $table->string('observacao', 200);

            $table->text('qr_code_base64');
            $table->text('qr_code');
            $table->text('link_boleto');
            $table->string('transacao_id', 100);
            $table->string('status', 15);

            $table->enum('tipo_pagamento', ['pix', 'cartao', 'boleto']);

            $table->boolean('carrinho')->default(0);

            // alter table pedidos change tipo_pagamento tipo_pagamento enum('pix', 'cartao', 'boleto');

            // alter table pedidos add column carrinho boolean default false;
            // alter table pedidos add column link_boleto text;
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
        Schema::dropIfExists('pedidos');
    }
}
