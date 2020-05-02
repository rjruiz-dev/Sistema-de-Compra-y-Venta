<?php

namespace App\Http\Controllers;

use App\User;
use App\Persona;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // determina si la peticion que se realizo es una peticion diferente  ajax, entoces redirije a la ruta raiz
        if (!$request->ajax()) return redirect('/');
        // se declara un array llamado personas, y este array se almacena todo los que nos devuelva
        // el metodo all de nuestra clase categoria que extiende al modelo
       
        // Con query builder, paginacion de esta manera evita el srcoll, llamar a la tabla como en la db
        // $personas = DB::table('personas')->paginate(2);
        // declara var php y en la var buscar voy alamcenar lo q recibo en el param buscar a traves de ajax     
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            // unir la tabla proveedores con la tabla personas usando el metodo join 
            $personas = User::join('personas','users.id','=','personas.id')
            ->join('roles','users.idrol','=','roles.id')
            ->select('personas.id','personas.nombre','personas.tipo_documento',
            'personas.num_documento','personas.direccion','personas.telefono',
            'personas.email','users.usuario','users.password','users.condicion','users.idrol','roles.nombre as rol')
            ->orderBy('personas.id','desc')->paginate(3);
        }  
        // si la var buscar es diferente de vacio
        else{
            // eel arreglo cat es igual a las personas, pero la condicion where
            // la condicion a buscar puede estar contenido ya sea al inicio, intermedio o al final del campo criterio.
            // despues ordena los registros de manera descendiente
            // por ultimo pagina de 2 en 2 los resultados
            $personas = User::join('personas','users.id','=','personas.id')
            ->join('roles','users.idrol','=','roles.id')
            ->select('personas.id','personas.nombre','personas.tipo_documento',
            'personas.num_documento','personas.direccion','personas.telefono',
            'personas.email','users.usuario','users.password','users.condicion','users.idrol','roles.nombre as rol')
            ->where('personas.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('personas.id','desc')->paginate(3);
        }    
        
        //cada ves que se haga referencia a esta funcion index se retorna el array personas
        return [
            'pagination' => [
                'total'         => $personas->total(),
                'current_page'  => $personas->currentPage(),
                'per_page'      => $personas->perPage(),
                'last_page'     => $personas->lastPage(),
                'from'          => $personas->firstItem(),
                'to'            => $personas->lastItem(),                
            ],
            'personas' => $personas
        ];
    }

    // funcion almacenar
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        // evalua si hay una exepcion de error
        try {
            // utiliza transacciones
            DB::beginTransaction();
            // se declara un objeto que instancia de la clase persona (modelo persona)
            $persona = new Persona();
            // al objeto persona la propiedad nombre le vamos a enviar lo q estamos recibiendo en nuestro objeto request 
            // todo este objeto lo recibimos del formulario 
            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;            
            $persona->save();

            // se crea un objeto del modelo proveedor y ese obj es una instancia del modelo proveedor
            $user = new User();
            $user->usuario = $request->usuario;
            $user->password = bcrypt($request->password);
            $user->condicion = '1';
            $user->idrol = $request->idrol;
            // voy a obtener de mi obj persona el id que se registro, para que ese id de manera automatica sea la llave foranea
            // del registro en la tabla proveedor 
            // voy almacenar lo que se almaceno en la columna id de la tabla persona
            $user->id = $persona->id;
            $user->save();
            // si ubiera un error
            DB::commit();
            // muestra el error obtenido
        } catch (Exception $e) {
            // anula la transacion
            DB::rollBack();
        }     

    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
      
        // evalua si hay una exepcion de error
        try {
            // utiliza transacciones
            DB::beginTransaction();

            // Busca primero el proveedor a modificar
            $user = User::findOrFail($request->id);

            // Como ya se encontro el id del proveedor, voy a realizar una busqueda con ese id de proveedor
            // ya que el id del proveedor es el mismo que el id de la tabla persona
            $persona = Persona::findOrFail($user->id);

            // se declara un objeto que instancia de la clase persona (modelo persona)
            // $persona = new Persona();
            // al objeto persona la propiedad nombre le vamos a enviar lo q estamos recibiendo en nuestro objeto request 
            // todo este objeto lo recibimos del formulario 
            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;            
            $persona->save();

            $user->usuario = $request->usuario;
            $user->password = bcrypt($request->password);
            $user->condicion = '1';
            $user->idrol = $request->idrol;
            $user->save();
            // si ubiera un error
            DB::commit();
            // muestra el error obtenido
        } catch (Exception $e) {
            // anula la transacion
            DB::rollBack();
        }
    }   

    // cuando llamemos a la funcion desactivar vamos a obtener con la funcion findorfail mediante el id q recibmos del objeto request
    // cual es el objeto que queremos desactivar
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condicion = '0';
        $user->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        //se vuelve a activar el campo condicion
        $user = User::findorFail($request->id);
        $user->condicion = '1';
        $user->save();
    }

}
