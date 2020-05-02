<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    // nombre de las columnas que van a recibir valores en la tabla personas de la base de datos
    protected $fillable = ['nombre', 'tipo_documento', 'num_documento', 'direccion', 'telefono', 'email'];

    public function proveedor()
    {
        // una persona esta relacionado de manera directa con un solo proveedor
        return $this->hasOne('App\Proveedor');
    }

    // se indica q una persona esta relacionada de manera directa  con un usuario
    public function user()
    {
        // se hace referencia al modelo user
        return $this->hasOne('App\User');
    }
}
