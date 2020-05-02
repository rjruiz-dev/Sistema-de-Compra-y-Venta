<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';
    protected $fillable = ['nombre','descripcion','condicion'];
    public $timestamps = false;

    // un rol puede tener varios usuarios
    public function users()
    {
        // se hace referencia al modelo user
        return $this->hasMany('App\User');
    }
}



