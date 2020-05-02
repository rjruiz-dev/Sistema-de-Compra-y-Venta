<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Venta;
use App\DetalleVenta;
use App\User;
use App\Notifications\NotifyAdmin;

class VentaController extends Controller
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
            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
            ->join('users','ventas.idusuario','=','users.id')
            ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
            'ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto',
            'ventas.total','ventas.estado','personas.nombre','users.usuario')
            ->orderBy('ventas.id','desc')->paginate(3);
        }  
        // si la var buscar es diferente de vacio
        else{
            // eel arreglo cat es igual a las personas, pero la condicion where
            // la condicion a buscar puede estar contenido ya sea al inicio, intermedio o al final del campo criterio.
            // despues ordena los registros de manera descendiente
            // por ultimo pagina de 2 en 2 los resultados
            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
            ->join('users','ventas.idusuario','=','users.id')
            ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
            'ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto',
            'ventas.total','ventas.estado','personas.nombre','users.usuario')
            ->where('ventas.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('ventas.id','desc')->paginate(3);
        }    
        
        //cada ves que se haga referencia a esta funcion index se retorna el array personas
        return [
            'pagination' => [
                'total'         => $ventas->total(),
                'current_page'  => $ventas->currentPage(),
                'per_page'      => $ventas->perPage(),
                'last_page'     => $ventas->lastPage(),
                'from'          => $ventas->firstItem(),
                'to'            => $ventas->lastItem(),                
            ],
            'ventas' => $ventas
        ];
    }

    public function obtenerCabecera(Request $request)
    {        
        if (!$request->ajax()) return redirect('/');
        
        $id = $request->id;    
        $venta = Venta::join('personas','ventas.idcliente','=','personas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
        'ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto',
        'ventas.total','ventas.estado','personas.nombre','users.usuario')
        ->where('ventas.id','=',$id)
        ->orderBy('ventas.id','desc')->take(1)->get();
       
        return ['venta' => $venta];

    }

    public function obtenerDetalles(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        
        $id = $request->id;    
        $detalles = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')       
        ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento',
        'articulos.nombre as articulo')
        ->where('detalle_ventas.idventa','=',$id)
        ->orderBy('detalle_ventas.id','desc')->get();
       
        return ['detalles' => $detalles];        
    }

    public function pdf(Request $request, $id)
    {
        $venta = Venta::join('personas','ventas.idcliente','=','personas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
        'ventas.num_comprobante','ventas.created_at','ventas.impuesto',
        'ventas.total','ventas.estado','personas.nombre','personas.tipo_documento','personas.num_documento',
        'personas.direccion','personas.email',
        'personas.telefono','users.usuario')
        ->where('ventas.id','=',$id)
        ->orderBy('ventas.id','desc')->take(1)->get();

        $detalles = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')       
        ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento',
        'articulos.nombre as articulo')
        ->where('detalle_ventas.idventa','=',$id)
        ->orderBy('detalle_ventas.id','desc')->get();

        // tambien es necesario un numero de venta
        $numventa=Venta::select('num_comprobante')->where('id',$id)->get();

        $pdf = \PDF::loadView('pdf.venta',['venta' => $venta,'detalles' => $detalles]);
                
        return $pdf->download('venta-'.$numventa[0]->num_comprobante.'.pdf');
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
            $venta = new Venta();
            // al objeto ingreso la propiedad nombre le vamos a enviar lo q estamos recibiendo en nuestro objeto request 
            // todo este objeto lo recibimos del formulario
            $venta->idcliente = $request->idcliente;
            // envia el id del usuario autenticado 
            $venta->idusuario = \Auth::user()->id;
            $venta->tipo_comprobante = $request->tipo_comprobante;
            $venta->serie_comprobante = $request->serie_comprobante;
            $venta->num_comprobante = $request->num_comprobante;
            $venta->fecha_hora = $mytime->toDateString();
            $venta->impuesto = $request->impuesto;
            $venta->total = $request->total;
            $venta->estado = 'Registrado';            
            $venta->save();

            // Array de detalles
            $detalles = $request->data; 

            foreach($detalles as $ep=>$det)
            {
                $detalle = new DetalleVenta();
                $detalle->idventa = $venta->id;
                $detalle->idarticulo = $det['idarticulo'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $det['precio'];
                $detalle->descuento = $det['descuento'];
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
            return[
                'id' => $venta->id
            ];
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
        $venta = Venta::findOrFail($request->id);
        $venta->estado = 'Anulado';
        $venta->save();
    }
}
