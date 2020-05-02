<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// importar el modelo articulo
use App\Articulo;


class ArticuloController extends Controller
{
    // en la accion index se listan todos los registros de la tabla categoria
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
            // metodo join 1°param une el modelo articulo con la tabla categorias
            // 2° param la columna id.cat de la tabla articulos y de esa columna va a ser igual a la columna id de la tabla cat...
            //  la llave foranea id.cat de la tabla articulos, debe ser igual a la llave primaria id de la tabla cat...
            $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
            ->orderBy('articulos.id','desc')->paginate(3);
        }  
        // si la var buscar es diferente de vacio
        else{
            $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
            ->where('articulos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('articulos.id','desc')->paginate(3);
                       
        }    
        
        //cada ves que se haga referencia a esta funcion index se retorna el array categorias
        return [
            'pagination' => [
                'total'         => $articulos->total(),
                'current_page'  => $articulos->currentPage(),
                'per_page'      => $articulos->perPage(),
                'last_page'     => $articulos->lastPage(),
                'from'          => $articulos->firstItem(),
                'to'            => $articulos->lastItem(),                
            ],
            'articulos' => $articulos
        ];
    }

    // en la accion index se listan todos los registros de la tabla categoria
    public function listarArticulo(Request $request)
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
            // metodo join 1°param une el modelo articulo con la tabla categorias
            // 2° param la columna id.cat de la tabla articulos y de esa columna va a ser igual a la columna id de la tabla cat...
            //  la llave foranea id.cat de la tabla articulos, debe ser igual a la llave primaria id de la tabla cat...
            $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
            ->orderBy('articulos.id','desc')->paginate(10);
        }  
        // si la var buscar es diferente de vacio
        else{
            $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
            ->where('articulos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('articulos.id','desc')->paginate(10);
                       
        }    
        
        //cada ves que se haga referencia a esta funcion index se retorna el array categorias
        return ['articulos' => $articulos];
    }

    public function listarArticuloVenta(Request $request)
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
            // metodo join 1°param une el modelo articulo con la tabla categorias
            // 2° param la columna id.cat de la tabla articulos y de esa columna va a ser igual a la columna id de la tabla cat...
            //  la llave foranea id.cat de la tabla articulos, debe ser igual a la llave primaria id de la tabla cat...
            $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
            ->where('articulos.stock','>','0')
            ->orderBy('articulos.id','desc')->paginate(10);
        }  
        // si la var buscar es diferente de vacio
        else{
            $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
            ->where('articulos.'.$criterio, 'like', '%'. $buscar . '%')
            ->where('articulos.stock','>','0')
            ->orderBy('articulos.id','desc')->paginate(10);
                       
        }    
        
        //cada ves que se haga referencia a esta funcion index se retorna el array categorias
        return ['articulos' => $articulos];
    }

    public function listarPdf()
    {   
        $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
        ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.nombre',
        'categorias.nombre as nombre_categoria','articulos.precio_venta','articulos.stock',
        'articulos.descripcion','articulos.condicion')
        ->orderBy('articulos.nombre','desc')->get();

        // la cantidad de art registrados
        $cont=Articulo::count();

        // loadview muestra el reporte en una vista llamada articulospdf, contenida en la carp pdf 
        // a la vista se le envian parametros llamado articulos, que va a ser igual a lo que contiene la var articulos, el otro param eso cont 
        $pdf = \PDF::loadView('pdf.articulospdf',['articulos' => $articulos,'cont' => $cont]);
        
        // retorna la vista y descar el reporte con el metodo download
        return $pdf->download('articulos.pdf');
    }


    public function buscarArticulo(Request $request){
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $articulos = Articulo::where('codigo','=', $filtro)
        ->select('id','nombre')->orderBy('nombre', 'asc')->take(1)->get();

        return ['articulos' => $articulos];
    }

    // se realizara la consulta de aquellos productos cuyo stock sea mayor a 0
    public function buscarArticuloVenta(Request $request){
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $articulos = Articulo::where('codigo','=', $filtro)
        ->select('id','nombre','stock','precio_venta')
        ->where('stock','>','0')
        ->orderBy('nombre', 'asc')->take(1)->get();

        return ['articulos' => $articulos];
    }

    // funcion almacenar
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        // se declara un objeto que instancie a la clase categroia (modelo categoria)
        $articulo = new Articulo();
        $articulo->idcategoria = $request->idcategoria;
        $articulo->codigo = $request->codigo;
        // al objeto categoria la propiedad nombre le vamos a enviar lo q estamos recibiendo en nuestro objeto request 
        // todo este objeto lo recibimos del formulario 
        $articulo->nombre = $request->nombre;
        $articulo->precio_venta = $request->precio_venta;
        $articulo->stock = $request->stock;
        $articulo->descripcion = $request->descripcion;
        // 1 es igual a articulo activa
        $articulo->condicion = '1';
        $articulo->save();

    }

    // Es muy similar al metodo store antes de guardar se comprueban los datos
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        // id lo recibimos desde el formulario de su vista respectiva
        // busco la categoria que ya se encuentra registrada en la bd mediante el id q recibo del formulario
        $articulo = Articulo::findOrFail($request->id);
        $articulo->idcategoria = $request->idcategoria;
        $articulo->codigo = $request->codigo;
        // al objeto categoria la propiedad nombre le vamos a enviar lo q estamos recibiendo en nuestro objeto request 
        // todo este objeto lo recibimos del formulario 
        $articulo->nombre = $request->nombre;
        $articulo->precio_venta = $request->precio_venta;
        $articulo->stock = $request->stock;
        $articulo->descripcion = $request->descripcion;
        // 1 es igual a articulo activa
        $articulo->condicion = '1';
        $articulo->save();

    }

    // cuando llamemos a la funcion desactivar vamos a obtener con la funcion findorfail mediante el id q recibmos del objeto request
    // cual es el objeto que queremos desactivar
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $articulo = Articulo::findorFail($request->id);
        $articulo->condicion = '0';
        $articulo->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        //se vuelve a activar el campo condicion
        $articulo = Articulo::findorFail($request->id);
        $articulo->condicion = '1';
        $articulo->save();
    }
}
