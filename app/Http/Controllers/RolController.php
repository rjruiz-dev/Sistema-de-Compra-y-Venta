<?php

namespace App\Http\Controllers;

use App\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    // en la accion index se listan todos los registros de la tabla rol
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // determina si la peticion que se realizo es una peticion diferente  ajax, entoces redirije a la ruta raiz
        // if (!$request->ajax()) return redirect('/');
        // se declara un array llamado roles, y este array se almacena todo los que nos devuelva
        // el metodo all de nuestra clase categoria que extiende al modelo
       
        // Con query builder, paginacion de esta manera evita el srcoll, llamar a la tabla como en la db
        // $roles = DB::table('roles')->paginate(2);
        // declara var php y en la var buscar voy alamcenar lo q recibo en el param buscar a traves de ajax     
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            // Con eloquent 
            $roles = Rol::orderBy('id','desc')->paginate(3);
        }  
        // si la var buscar es diferente de vacio
        else{
            // eel arreglo cat es igual a las roles, pero la condicion where
            // la condicion a buscar puede estar contenido ya sea al inicio, intermedio o al final del campo criterio.
            // despues ordena los registros de manera descendiente
            // por ultimo pagina de 2 en 2 los resultados
            $roles = Rol::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id','desc')->paginate(3);
        }    
        
        //cada ves que se haga referencia a esta funcion index se retorna el array roles
        return [
            'pagination' => [
                'total'         => $roles->total(),
                'current_page'  => $roles->currentPage(),
                'per_page'      => $roles->perPage(),
                'last_page'     => $roles->lastPage(),
                'from'          => $roles->firstItem(),
                'to'            => $roles->lastItem(),                
            ],
            'roles' => $roles
        ];
    }

    public function selectRol(Request $request)
    {
        $roles = Rol::where('condicion','=','1')
        ->select('id','nombre')
        ->orderBy('nombre','asc')->get();

        return['roles' => $roles];
    }

}
