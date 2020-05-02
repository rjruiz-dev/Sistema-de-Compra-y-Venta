<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    // se especifica la tabla a la que se hace referencia
    // segun laravel seria provedors
    // pasa a ser proveedores
    protected $table = 'proveedores';

    // se indican el nombre de  los campos de los cuales se envia y se optienen valores
    protected $fillable = [
        'id', 'contacto', 'telefono_contacto'
    ];

    // no tiene los campos de tipo timestamps
    public $timestamps = false;

    // la funcion se llama persona porque un proveedor pertenece a una persona
    public function persona()
    {
        // retorna al modelo persona
        return $this->belongsTo('App\Persona');
    }
    
}
