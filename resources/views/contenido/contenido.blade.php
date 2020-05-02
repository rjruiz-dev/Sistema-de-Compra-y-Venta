@extends('principal')
@section('contenido')

@if(Auth::check())
        @if(Auth::user()->idrol == 1)
        <!-- con la directiva include se incluye la vista sidebar.blade.php-->
        <!-- esta directiva permite incluir una vista blade desde otra vista -->
        <!-- y todas las variables disponibles en la vista padre se pondran a dispocicion de la vista incluida -->
        <!-- todo esto es para comprobar que cada ves q se seleccione un menu distinto  -->
        <!-- se actualice la vista pero sin recargar la pagina -->
        <!-- utilizando componentes -->
        <template v-if="menu==0">
            <dashboard><dashboard>
        </template>

        <template v-if="menu==1">
            <categoria></categoria>
        </template>

        <template v-if="menu==2">
            <articulo></articulo>
        </template>

        <template v-if="menu==3">
            <ingreso></ingreso>
        </template>

        <template v-if="menu==4">
            <proveedor></proveedor>
        </template>

        <template v-if="menu==5">
            <venta></venta>
        </template>

        <template v-if="menu==6">
            <cliente></cliente>
        </template>

        <template v-if="menu==7">
            <user></user>
        </template>

        <template v-if="menu==8">
            <rol></rol>
        </template>

        <template v-if="menu==9">
            <consultaingreso></consultaingreso>
        </template>

        <template v-if="menu==10">
            <consultaventa></consultaventa>
        </template>

        <template v-if="menu==11">
            <h1>Ayuda</h1>
        </template>

        <template v-if="menu==12">
            <h1>Acerca de</h1>
        </template>
        
        @elseif (Auth::user()->idrol == 2)
        <template v-if="menu==0">
            <dashboard><dashboard>
        </template>

        <template v-if="menu==5">
            <venta></venta>
        </template>

        <template v-if="menu==6">
            <cliente></cliente>
        </template> 

        <template v-if="menu==10">
            <consultaventa></consultaventa>
        </template>

        <template v-if="menu==11">
            <h1>Ayuda</h1>
        </template>

        <template v-if="menu==12">
            <h1>Acerca de</h1>
        </template>               
         @elseif (Auth::user()->idrol == 3)
         <template v-if="menu==0">
            <dashboard><dashboard>
        </template>

        <template v-if="menu==1">
            <categoria></categoria>
        </template>

        <template v-if="menu==2">
            <articulo></articulo>
        </template>

        <template v-if="menu==3">
            <ingreso></ingreso>
        </template>

        <template v-if="menu==4">
            <proveedor></proveedor>
        </template>

        <template v-if="menu==9">
            <consultaingreso></consultaingreso>
        </template>

        <template v-if="menu==11">
            <h1>Ayuda</h1>
        </template>

        <template v-if="menu==12">
            <h1>Acerca de</h1>
        </template>
            
        @else

        @endif

 @endif

@endsection