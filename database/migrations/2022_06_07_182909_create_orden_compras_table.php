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
        Schema::create('orden_compras', function (Blueprint $table) {
            $table->string('folio')->primary();
            $table->string('comentarios', 90);
            $table->date('fecha_orden');
            $table->integer('id_proveedor');
            $table->string('estatus')->default('Pendiente');
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
        Schema::dropIfExists('orden_compras');
    }
};
