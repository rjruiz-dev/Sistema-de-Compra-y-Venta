<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $fillable = [
        'idcategoria','codigo','nombre','precio_venta','stock','descripcion','condicion'
    ];
    // relacion inversa devido a q la tabla articulos esta relacionada con la tabla categorias 
    public function categoria(){
        // haciendo referencia al modelo, (un articulo pertence a una categoria)
        return $this->belongsto('App\Categoria');
    }
}
