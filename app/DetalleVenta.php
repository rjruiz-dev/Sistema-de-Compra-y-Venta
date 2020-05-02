<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = 'detalle_ventas';
    protected $fillable = [
        // el campo id generalmete no se incluye
        'idventa',
        'idarticulo',
        'cantidad',
        'precio',
        'descuento'        
    ];

    public $timestamps = false;
}
