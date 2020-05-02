<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'usuario', 'password','condicion', 'idrol',
    ];

    // porque no existen estos campos en la tabla de db
    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // con la funcion se indica que se hace referencia al modelo rol
    public function rol()
    {
        // se indica q un usuario pertenece a un rol
        return $this->belongsTo('App\Rol');
    }    
    
    // con la funcion se indica que se hace referencia al modelo persona
    public function persona()
    {
        // se indica q un usuario hace referencia a una persona
        return $this->belongsTo('App\Persona');
    }
}
