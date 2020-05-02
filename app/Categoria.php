<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    // debido la convencion de nombres laravel asume automaticamente que la tabla se llama categorias
    // por lo tanto no se indica el nombre de la tabla ni su llave primaria
    // protected $table = 'categorias';
    // protected $primaryKey = 'id';

    // columnas de nuestra tabla a la cuales les vamos a enviar valores
    protected $fillable = ['nombre', 'descripcion', 'condicion'];
    
    // relacion de uno a muchos 
    public function articulos()
    {
        // hace referencia al modelo articulo (una categoria tiene varios articulos)
        return $this->hasMany('App\Articulo');
    }


}
