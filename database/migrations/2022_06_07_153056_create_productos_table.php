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
        Schema::create('productos', function (Blueprint $table) {
            $table->increments('SKU');
            $table->string('descripcion', 90);
            $table->integer('cantidad')->default(0);
            $table->float("precio", 8, 2);
            $table->integer('id_proveedor')->unsigned();
            $table->timestamps();

            $table->foreign('id_proveedor')->references('id')->on('proveedors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
};
