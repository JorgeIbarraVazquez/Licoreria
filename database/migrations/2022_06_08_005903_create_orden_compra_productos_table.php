<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_compra_productos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('folio_orden_compra');
            $table->integer('id_producto')->unsigned();
            $table->integer('cantidad');
            $table->decimal('precio_total');
            $table->timestamps();
            
            $table->foreign('folio_orden_compra')->references('folio')->on('orden_compras');
            $table->foreign('id_producto')->references('SKU')->on('productos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orden_compra_productos');
    }
};
