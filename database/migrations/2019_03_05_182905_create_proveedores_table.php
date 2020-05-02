<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            // se cambia de increment a integer
            // el id que se guarda en la tabla proveedores es el mismo id q se guarda en la tabla persona
            $table->integer('id')->unsigned();
            $table->string('contacto', 50)->nullable();
            $table->string('telefono_contacto', 50)->nullable();
          
            // relacion tabla proveedores con la tabla personas
            // en la tabla proveedores el id es la llave foranea, y hace referencia a la columna id de la tabla personas
            // tipo de eliminacion en cascada
            $table->foreign('id')->references('id')->on('personas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proveedores');
    }
}
