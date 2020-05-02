<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {

            // Cuando se registra un usuario en el sistema inicialmente se registra en la tabla persona (datos comunes a un cliente,proveedor y usuarios)
            // los datos especificos se registran en esta tabla, por eso el campor integer (id)
            // unsigned() permite relacionar tablas
            $table->integer('id')->unsigned();
            // con foreign se crea la llave foranea, se relacionan los campos id de la tabla users con id de la tabla personas
            $table->foreign('id')->references('id')->on('personas')->onDelete('cascade');
            
            $table->string('usuario')->unique();
            $table->string('password');
            $table->boolean('condicion')->default(1);

            // con esto se hace referencia a la tabla rol y poder relacionarla
            $table->integer('idrol')->unsigned();
            // el campo idrol es una llave foranea q se referncia al campo id de la tabla roles
            $table->foreign('idrol')->references('id')->on('roles');

            $table->rememberToken();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
