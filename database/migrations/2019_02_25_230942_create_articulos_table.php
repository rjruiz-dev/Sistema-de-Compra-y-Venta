<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->increments('id');
            // hace referencia al campo categoria unsigned indica q es llave foranea
            $table->integer('idcategoria')->unsigned();
            $table->string('codigo', 50)->nullable();
            // unique es campo unico
            $table->string('nombre', 100)->unique();
            // precision de 11 digitos y una escala de 2 cifras
            $table->decimal('precio_venta', 11, 2);
            $table->integer('stock');
            $table->string('descripcion', 256)->nullable();
            // por defecto va a tener el valor de 1
            $table->boolean('condicion')->default(1);
            $table->timestamps();

            // la llave foranea va ser el campo idcategoria y hace referencia al campo id de la tabla categorias
            // creando una relacion entre la tabla art y la tabla cat
            $table->foreign('idcategoria')->references('id')->on('categorias');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articulos');
    }
}
