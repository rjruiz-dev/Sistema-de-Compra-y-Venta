<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleIngreso extends Model
{
    protected $table = 'detalle__ingresos';
    protected $fillable = [
        'idingreso',
        'idarticulo',
        'cantidad',
        'precio',        
    ];

    public $timestamps = false;
}
