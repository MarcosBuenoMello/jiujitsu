<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutoGaleriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produto_galerias', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('produto_id');
            $table->foreign('produto_id')->references('id')
            ->on('produtos');

            $table->string('imagem', 30);

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
        Schema::dropIfExists('produto_galerias');
    }
}
