<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ingreso;
use App\DetalleIngreso;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\User;
use App\Notifications\NotifyAdmin;

class IngresoController extends Controller
{
    public function index(Request $request)
    {
        // determina si la peticion que se realizo es una peticion diferente  ajax, entoces redirije a la ruta raiz
        if (!$request->ajax()) return redirect('/');
        // se declara un array llamado ingresos, y este array se almacena todo los que nos devuelva
        // el metodo all de nuestra clase categoria que extiende al modelo
       
        // Con query builder, paginacion de esta manera evita el srcoll, llamar a la tabla como en la db
        // $ingresos = DB::table('ingresos')->paginate(2);
        // declara var php y en la var buscar voy alamcenar lo q recibo en el param buscar a traves de ajax     
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            // unir la tabla proveedores con la tabla ingresos usando el metodo join 
            $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
            ->join('users','ingresos.idusuario','=','users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
            'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto',
            'ingresos.total','ingresos.estado','personas.nombre','users.usuario')
            ->orderBy('ingresos.id','desc')->paginate(3);
        }  
        // si la var buscar es diferente de vacio
        else{
            // eel arreglo cat es igual a las personas, pero la condicion where
            // la condicion a buscar puede estar contenido ya sea al inicio, intermedio o al final del campo criterio.
            // despues ordena los registros de manera descendiente
            // por ultimo pagina de 2 en 2 los resultados
            $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
            ->join('users','ingresos.idusuario','=','users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
            'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto',
            'ingresos.total','ingresos.estado','personas.nombre','users.usuario')
            ->where('ingresos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('personas.id','desc')->paginate(3);
        }    
        
        //cada ves que se haga referencia a esta funcion index se retorna el array personas
        return [
            'pagination' => [
                'total'         => $ingresos->total(),
                'current_page'  => $ingresos->currentPage(),
                'per_page'      => $ingresos->perPage(),
                'last_page'     => $ingresos->lastPage(),
                'from'          => $ingresos->firstItem(),
                'to'            => $ingresos->lastItem(),                
            ],
            'ingresos' => $ingresos
        ];
    }

    public function obtenerCabecera(Request $request)
    {        
        if (!$request->ajax()) return redirect('/');
        
        $id = $request->id;    
        $ingreso = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
        ->join('users','ingresos.idusuario','=','users.id')
        ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
        'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto',
        'ingresos.total','ingresos.estado','personas.nombre','users.usuario')
        ->where('ingresos.id','=',$id)
        ->orderBy('ingresos.id','desc')->take(1)->get();
       
        return ['ingreso' => $ingreso];

    }

    public function obtenerDetalles(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        
        $id = $request->id;    
        $detalles = DetalleIngreso::join('articulos','detalle__ingresos.idarticulo','=','articulos.id')       
        ->select('detalle__ingresos.cantidad','detalle__ingresos.precio','articulos.nombre as articulo')
        ->where('detalle__ingresos.idingreso','=',$id)
        ->orderBy('detalle__ingresos.id','desc')->get();
       
        return ['detalles' => $detalles];        
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

            // variable para captura de manera automatica fecha y hora
            $mytime = Carbon::now('America/Argentina/Buenos_Aires');
            // se declara un objeto que instancia de la clase ingreso (modelo ingreso)
            $ingreso = new Ingreso();
            // al objeto ingreso la propiedad nombre le vamos a enviar lo q estamos recibiendo en nuestro objeto request 
            // todo este objeto lo recibimos del formulario
            $ingreso->idproveedor = $request->idproveedor;
            // envia el id del usuario autenticado 
            $ingreso->idusuario = \Auth::user()->id;
            $ingreso->tipo_comprobante = $request->tipo_comprobante;
            $ingreso->serie_comprobante = $request->serie_comprobante;
            $ingreso->num_comprobante = $request->num_comprobante;
            $ingreso->fecha_hora = $mytime->toDateString();
            $ingreso->impuesto = $request->impuesto;
            $ingreso->total = $request->total;
            $ingreso->estado = 'Registrado';            
            $ingreso->save();

            // Array de detalles
            $detalles = $request->data; 

            foreach($detalles as $ep=>$det)
            {
                $detalle = new DetalleIngreso();
                $detalle->idingreso = $ingreso->id;
                $detalle->idarticulo = $det['idarticulo'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $det['precio'];
                $detalle->save();
            }

            // la cantidad dr ingresos y cantidad de ventas en ese dia especifico
            $fechaActual = date('Y-m-d');
            $numVentas = DB::table('ventas')->whereDate('created_at', $fechaActual)->count();
            $numIngresos = DB::table('ingresos')->whereDate('created_at', $fechaActual)->count();

            $arregloDatos = [
            'ventas' => [
                        'numero' => $numVentas,
                        'msj' => 'Ventas'
                    ],
            'ingresos' => [
                        'numero' => $numIngresos,
                        'msj' => 'Ingresos'
                    ]
            ];

            // para notificar a los usuarios
            $allUsers = User::all();

            foreach ($allUsers as $notificar) {
                User::findOrFail($notificar->id)->notify(new NotifyAdmin($arregloDatos));
            }

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
        $ingreso = Ingreso::findOrFail($request->id);
        $ingreso->estado = 'Anulado';
        $ingreso->save();
    }
}
