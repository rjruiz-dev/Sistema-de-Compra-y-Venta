<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Persona;

class ClienteController extends Controller
{
    // en la accion index se listan todos los registros de la tabla persona
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
            // Con eloquent 
            $personas = Persona::orderBy('id','desc')->paginate(3);
        }  
        // si la var buscar es diferente de vacio
        else{
            // eel arreglo cat es igual a las personas, pero la condicion where
            // la condicion a buscar puede estar contenido ya sea al inicio, intermedio o al final del campo criterio.
            // despues ordena los registros de manera descendiente
            // por ultimo pagina de 2 en 2 los resultados
            $personas = Persona::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id','desc')->paginate(3);
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

    public function selectCliente(Request $request){
        if(!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $clientes = Persona::where('nombre', 'like', '%'. $filtro . '%')
        ->orWhere('num_documento', 'like', '%'. $filtro . '%')
        ->select('id','nombre','num_documento')
        ->orderBy('nombre', 'asc')->get();

        return ['clientes' => $clientes];
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

    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        // id lo recibimos desde el formulario de su vista respectiva
        // busco la persona que ya se encuentra registrada en la bd mediante el id q recibo del formulario
        $persona = Persona::findorFail($request->id);
        $persona->nombre = $request->nombre;
        $persona->tipo_documento = $request->tipo_documento;
        $persona->num_documento = $request->num_documento;
        $persona->direccion = $request->direccion;
        $persona->telefono = $request->telefono;
        $persona->email = $request->email;
        $persona->save();

    }
}
