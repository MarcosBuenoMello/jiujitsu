<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();

            $table->string('nome', 50);
            $table->string('tamanho', 10);
            $table->text('descricao');
            $table->decimal('valor', 10,2);
            $table->boolean('status');
            $table->boolean('destaque');

            $table->integer('estoque')->default(0);

            $table->unsignedBigInteger('categoria_id');
            $table->foreign('categoria_id')->references('id')
            ->on('categoria_produtos');
            $table->timestamps();

            // alter table produtos add column estoque integer default 0;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produtos');
    }
}
