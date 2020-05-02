<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        // el campo id generalmete no se incluye
        'idcliente',
        'idusuario',
        'tipo_comprobante',
        'serie_comprobante',
        'num_comprobante',
        'fecha_hora',
        'impuesto',
        'total',
        'estado'
    ];
}
