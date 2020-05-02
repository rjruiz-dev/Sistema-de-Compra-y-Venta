<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Proveedor;
use App\Persona;

class ProveedorController extends Controller
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
            $personas = Proveedor::join('personas','proveedores.id','=','personas.id')
            ->select('personas.id','personas.nombre','personas.tipo_documento',
            'personas.num_documento','personas.direccion','personas.telefono',
            'personas.email','proveedores.contacto','proveedores.telefono_contacto')
            ->orderBy('personas.id','desc')->paginate(3);
        }  
        // si la var buscar es diferente de vacio
        else{
            // eel arreglo cat es igual a las personas, pero la condicion where
            // la condicion a buscar puede estar contenido ya sea al inicio, intermedio o al final del campo criterio.
            // despues ordena los registros de manera descendiente
            // por ultimo pagina de 2 en 2 los resultados
            $personas = Proveedor::join('personas','proveedores.id','=','personas.id')
            ->select('personas.id','personas.nombre','personas.tipo_documento',
            'personas.num_documento','personas.direccion','personas.telefono',
            'personas.email','proveedores.contacto','proveedores.telefono_contacto')
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

    public function selectProveedor(Request $request){
        if(!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $proveedores = Proveedor::join('personas', 'proveedores.id','=','personas.id')
        ->where('personas.nombre', 'like', '%'. $filtro . '%')
        ->orWhere('personas.num_documento', 'like', '%'. $filtro . '%')
        ->select('personas.id','personas.nombre','personas.num_documento')
        ->orderBy('personas.nombre', 'asc')->get();

        return ['proveedores' => $proveedores];
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
            $proveedor = new Proveedor();
            $proveedor->contacto = $request->contacto;
            $proveedor->telefono_contacto = $request->telefono_contacto;
            // voy a obtener de mi obj persona el id que se registro, para que ese id de manera automatica sea la llave foranea
            // del registro en la tabla proveedor 
            // voy almacenar lo que se almaceno en la columna id de la tabla persona
            $proveedor->id = $persona->id;
            $proveedor->save();
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
            $proveedor = Proveedor::findOrFail($request->id);

            // Como ya se encontro el id del proveedor, voy a realizar una busqueda con ese id de proveedor
            // ya que el id del proveedor es el mismo que el id de la tabla persona
            $persona = Persona::findOrFail($proveedor->id);

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

            $proveedor->contacto = $request->contacto;
            $proveedor->telefono_contacto = $request->telefono_contacto;
            $proveedor->save();
            // si ubiera un error
            DB::commit();
            // muestra el error obtenido
        } catch (Exception $e) {
            // anula la transacion
            DB::rollBack();
        }
    }   
}
