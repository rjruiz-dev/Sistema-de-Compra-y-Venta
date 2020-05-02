<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // la ventaja es cuando registramos una ruta de un controlador en este caso dashboard
    // no se necesita especificar ningun metodo en la ruta por defecto asume q usa esta funcion invoke
    public function __invoke(Request $request)
    {
        $anio=date('Y');
        $ingresos=DB::table('ingresos as i')
        // metodo que nos permite obtener el mes de una fecha establecida
        // son las columnas del grafico
        ->select(DB::raw('MONTH(i.fecha_hora) as mes'),
        DB::raw('YEAR(i.fecha_hora) as anio'),
        DB::raw('SUM(i.total) as total'))
        // muestra los meses que transucrrieron durante ese año
        ->whereYear('i.fecha_hora',$anio)
        ->groupBy(DB::raw('MONTH(i.fecha_hora)'),DB::raw('YEAR(i.fecha_hora)'))
        ->get();

        $ventas=DB::table('ventas as v')
        ->select(DB::raw('MONTH(v.fecha_hora) as mes'),
        DB::raw('YEAR(v.fecha_hora) as anio'),
        DB::raw('SUM(v.total) as total'))      
        ->whereYear('v.fecha_hora',$anio)
        ->groupBy(DB::raw('MONTH(v.fecha_hora)'),DB::raw('YEAR(v.fecha_hora)'))
        ->get();

        return ['ingresos'=>$ingresos,'ventas'=>$ventas,'anio'=>$anio];       
    }
}
