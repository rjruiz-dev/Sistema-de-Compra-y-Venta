<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// paginacion
// use Illuminate\Support\Facades\DB;
// importar modelo
use App\Categoria;

class CategoriaController extends Controller
{
    // en la accion index se listan todos los registros de la tabla categoria
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // determina si la peticion que se realizo es una peticion diferente  ajax, entoces redirije a la ruta raiz
        if (!$request->ajax()) return redirect('/');
        // se declara un array llamado categorias, y este array se almacena todo los que nos devuelva
        // el metodo all de nuestra clase categoria que extiende al modelo
       
        // Con query builder, paginacion de esta manera evita el srcoll, llamar a la tabla como en la db
        // $categorias = DB::table('categorias')->paginate(2);
        // declara var php y en la var buscar voy alamcenar lo q recibo en el param buscar a traves de ajax     
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            // Con eloquent 
            $categorias = Categoria::orderBy('id','desc')->paginate(3);
        }  
        // si la var buscar es diferente de vacio
        else{
            // eel arreglo cat es igual a las categorias, pero la condicion where
            // la condicion a buscar puede estar contenido ya sea al inicio, intermedio o al final del campo criterio.
            // despues ordena los registros de manera descendiente
            // por ultimo pagina de 2 en 2 los resultados
            $categorias = Categoria::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id','desc')->paginate(3);
        }    
        
        //cada ves que se haga referencia a esta funcion index se retorna el array categorias
        return [
            'pagination' => [
                'total'         => $categorias->total(),
                'current_page'  => $categorias->currentPage(),
                'per_page'      => $categorias->perPage(),
                'last_page'     => $categorias->lastPage(),
                'from'          => $categorias->firstItem(),
                'to'            => $categorias->lastItem(),                
            ],
            'categorias' => $categorias
        ];
    }

    // lista todas las categorias que esten activas
    public function selectCategoria(Request $request){
        if (!$request->ajax()) return redirect('/');
        $categorias = Categoria::where('condicion','=','1')
        ->select('id','nombre')->orderBy('nombre','asc')->get();
        // retorna en un objeto llamado categorias, todo la variable categoria todo el listado de la var categorias cuya condicion sea igual a 1
        return ['categorias' => $categorias];
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
        // se declara un objeto que instancie a la clase categroia (modelo categoria)
        $categoria = new Categoria();
        // al objeto categoria la propiedad nombre le vamos a enviar lo q estamos recibiendo en nuestro objeto request 
        // todo este objeto lo recibimos del formulario 
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        // 1 es igual a categoria activa
        $categoria->condicion = '1';
        $categoria->save();

    }

    // Es muy similar al metodo store antes de guardar se comprueban los datos
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        // id lo recibimos desde el formulario de su vista respectiva
        // busco la categoria que ya se encuentra registrada en la bd mediante el id q recibo del formulario
        $categoria = Categoria::findorFail($request->id);
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->condicion = '1';
        $categoria->save();

    }

    // cuando llamemos a la funcion desactivar vamos a obtener con la funcion findorfail mediante el id q recibmos del objeto request
    // cual es el objeto que queremos desactivar
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categoria = Categoria::findOrFail($request->id);
        $categoria->condicion = '0';
        $categoria->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        //se vuelve a activar el campo condicion
        $categoria = Categoria::findorFail($request->id);
        $categoria->condicion = '1';
        $categoria->save();
    }

    
}
