<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('pedido_id');
            $table->foreign('pedido_id')->references('id')
            ->on('pedidos');

            $table->unsignedBigInteger('produto_id');
            $table->foreign('produto_id')->references('id')
            ->on('produtos');

            $table->integer('quantidade');
            $table->decimal('valor', 10, 2);
            
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
        Schema::dropIfExists('pedido_items');
    }
}
