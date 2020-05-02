<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Ventas
                        <!-- para las ventanas modal ya nose utiliza jquery sino vue por eso se elimino el data-terget -->
                        <!-- al boton se le agrega una directiva @click con el metodo abrirModal y los parametros persona. y regis. -->
                        <button type="button" @click="mostrarDetalle()" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>                    
                    <!-- listado -->
                    <template v-if="listado==1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <!-- Criterios de busqueda -->
                                      <option value="tipo_comprobante">Tipo Comprobante</option>
                                      <option value="num_comprobante">Numero Comprobante</option>
                                      <option value="fecha_hora">Fecha-Hora</option>                                      
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarVenta(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarVenta(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Usuario</th>
                                        <th>Cliente</th>
                                        <th>Tipo Comprobante</th>
                                        <th>Serie Comprobante</th>
                                        <th>Número Comprobante</th>
                                        <th>Fecha Hora</th>
                                        <th>Total</th>
                                        <th>Impuesto</th>
                                        <th>Estado</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <!-- objeto persona -->
                                    <tr v-for="venta in arrayVenta" :Key="venta.id">
                                        <td>
                                            <!-- para actualizar se envia el objeto (persona) sin apostrofos -->
                                            <button type="button" @click="verVenta(venta.id)" class="btn btn-success btn-sm">
                                            <i class="icon-eye"></i>
                                            </button>&nbsp;
                                            <button type="button" @click="pdfVenta(venta.id)" class="btn btn-info btn-sm">
                                            <i class="icon-doc"></i>
                                            </button>&nbsp; 
                                            <template v-if="venta.estado=='Registrado'">
                                                <!-- con el boton se desactiva la categoria -->
                                                <button type="button" class="btn btn-danger btn-sm" @click="desactivarVenta(venta.id)">
                                                    <!-- si la categoria esta activa muestra lo icono  -->
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template>
                                            <!-- si la condicion almacenada en el obj categoria es 0 (desactivada), voy a mostrar un boton haciendo referencia al metodo descativarCategoria -->                                            
                                        </td>
                                        <td v-text="venta.usuario"></td>
                                        <td v-text="venta.nombre"></td>
                                        <td v-text="venta.tipo_comprobante"></td>
                                        <td v-text="venta.serie_comprobante"></td>
                                        <td v-text="venta.num_comprobante"></td>
                                        <td v-text="venta.fecha_hora"></td>
                                        <td v-text="venta.total"></td>
                                        <td v-text="venta.impuesto"></td>          
                                        <td v-text="venta.estado"></td>          
                                                                
                                    </tr>                                
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>                                
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    </template>
                    <!-- fin listado -->
                    <!-- detalle -->
                    <template v-else-if="listado==0">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-grup">
                                    <label for="">Cliente(*)</label>
                                    <v-select
                                        :on-search="selectCliente"
                                        label="nombre"
                                        :options="arrayCliente"
                                        placeholder="Buscar Clientes..."
                                        :onChange="getDatosCliente"
                                    >

                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">Impuesto(*)</label>
                                <input type="text" class="form-control" v-model="impuesto">
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Comprobante(*)</label>
                                    <select class="form-control" v-model="tipo_comprobante">
                                        <option value="0">Seleccione</option>
                                        <option value="BOLETA">Boleta</option>
                                        <option value="FACTURA">Factura</option>
                                        <option value="TICKET">Ticket</option>
                                    </select>
                                </div>                                
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie Comprobante</label>
                                    <input type="text" class="form-control" v-model="serie_comprobante" placeholder="000x">                                    
                                </div>                                
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Numero Comprobante(*)</label>
                                    <input type="text" class="form-control" v-model="num_comprobante" placeholder="000xx">                                    
                                </div>                                
                            </div>
                            <div class="col-md-12">
                                <div v-show="errorVenta" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <!-- iterar dentro de nuestro objeto error -->
                                        <div v-for="error in errorMostrarMsjVenta" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Articulo <span style="color:red;" v-show="idarticulo==0">(*Seleccione)</span></label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="codigo" @keyup.enter="buscarArticulo()" placeholder="Ingrese articulo">
                                        <button @click="abrirModal()" class="btn btn-primary">...</button>
                                        <input type="text" readonly class="form-control" v-model="articulo">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Precio  <span style="color:red;" v-show="precio==0">(*Ingrese)</span></label>
                                    <input type="number" value="0" step="any" class="form-control" v-model="precio">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cantidad  <span style="color:red;" v-show="cantidad==0">(*Ingrese)</span></label>
                                    <input type="number" value="0" class="form-control" v-model="cantidad">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Descuento</label>
                                    <input type="number" value="0" class="form-control" v-model="descuento">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                                </div>
                            </div>

                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Articulo</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Descuento</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                            <td>
                                                <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td v-text="detalle.articulo">                                              
                                            </td>
                                            <td>
                                                <input v-model="detalle.precio" type="number" class="form-control">
                                            </td>
                                            <td>
                                                <span style="color:red;" v-show="detalle.cantidad>detalle.stock">Stock: {{detalle.stock}}</span>
                                                <input v-model="detalle.cantidad" type="number" class="form-control">
                                            </td>
                                             <td>
                                                 <span style="color:red;" v-show="detalle.descuento>detalle.precio*detalle.cantidad">Descuento superior</span>
                                                <input v-model="detalle.descuento" type="number" class="form-control">
                                            </td>
                                            <td>
                                                {{ detalle.precio*detalle.cantidad-detalle.descuento }}
                                            </td>
                                        </tr>
                                        
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Total Parcial:</strong></td>
                                            <td>$ {{ totalParcial=(total-totalImpuesto).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Total Impuesto:</strong></td>
                                            <td>$ {{ totalImpuesto=((total*impuesto)/(1+impuesto)).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="5" align="right"><strong>Total Neto:</strong></td>
                                            <td>$ {{ total=calcularTotal}}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="6">
                                                No hay articulos agregados
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registrarVenta()">Registrar Venta</button>
                            </div>

                        </div>
                    </div>
                    </template>
                    <!-- fin detalle -->
                    <!-- ver ingreso -->
                     <template v-else-if="listado==2">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-grup">
                                    <label for="">Cliente</label>
                                    <p v-text="cliente"></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">Impuesto</label>
                                <p v-text="impuesto"></p>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Comprobante</label>
                                    <p v-text="tipo_comprobante"></p>
                                </div>                                
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie Comprobante</label>
                                    <p v-text="serie_comprobante"></p>                                       
                                </div>                                
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Numero Comprobante</label>
                                    <p v-text="num_comprobante"></p>
                                </div>                                
                            </div>
                          
                        </div>
                       
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Articulo</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Descuento</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                          
                                            <td v-text="detalle.articulo">                                              
                                            </td>
                                            <td v-text="detalle.precio">                                                
                                            </td>
                                            <td v-text="detalle.cantidad">                                                
                                            </td>
                                             <td v-text="detalle.descuento">
                                            </td>
                                            <td>
                                                {{ detalle.precio*detalle.cantidad-detalle.descuento }}
                                            </td>
                                        </tr>
                                        
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Parcial:</strong></td>
                                            <td>$ {{ totalParcial=(total-totalImpuesto).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Impuesto:</strong></td>
                                            <td>$ {{ totalImpuesto=((total*impuesto)).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Neto:</strong></td>
                                            <td>$ {{total}}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="5">
                                                No hay articulos agregados
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                               
                            </div>

                        </div>
                    </div>
                    </template>
                    <!-- fin ver ingreso -->
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <!-- se visualiza la ventana modal cuando la variable sea igual a 1 se le asigna la clase mostrar con :class -->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterioA">
                                        <option value="nombre">Nombre</option>
                                        <option value="descripcion">Descripción</option>
                                        <option value="codigo">Codigo</option>
                                        </select>
                                        <input type="text" v-model="buscarA" @keyup.enter="listarArticulo(buscarA,criterioA)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarArticulo(buscarA,criterioA)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                           <div class="table-responsive">
                               <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Codigo</th>
                                            <th>Nombre</th>
                                            <th>Categoria</th>
                                            <th>Precio Venta</th>
                                            <th>Stock</th>
                                            
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- objeto categoria -->
                                        <tr v-for="articulo in arrayArticulo" :Key="articulo.id">
                                            <td>
                                                <!-- para actualizar se envia el objeto (categoria) sin apostrofos -->
                                                <button type="button" @click="agregarDetalleModal(articulo)" class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                                </button>
                                                <!-- si la condicion almacenada en el obj categoria es 0 (desactivada), voy a mostrar un boton haciendo referencia al metodo descativarCategoria -->
                                                
                                            </td>
                                            <td v-text="articulo.codigo"></td>
                                            <td v-text="articulo.nombre"></td>
                                            <td v-text="articulo.nombre_categoria"></td>
                                            <td v-text="articulo.precio_venta"></td>
                                            <td v-text="articulo.stock"></td>
                                           
                                            <td>
                                                <div v-if="articulo.condicion">
                                                    <span class="badge badge-success">Activo</span>
                                                </div>
                                                <div v-else>
                                                    <span class="badge badge-danger">Desactivado</span>
                                                </div>
                                            </td>
                                        </tr>                                
                                    </tbody>
                                </table>
                           </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPersona()">Guardar</button>
                             <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPersona()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->                       
        </main>
</template>

<script>
    import vSelect from 'vue-select';
    export default {
        data(){
            return {  
                // corresponde al id de la categoria q quiero actuliazar   
                // se declaran las variables q se utilizan
                // la data que reciba nuestro objeto categoria se almacene en este array
                // var modal inicializa en 0 indica si se muestra o se oculta la ventana
                // var titulo para mostra el titulo correspondiente
                // var tipoAccion muestra el boton correspondiente segun sea act o regis
                venta_id : 0,
                idcliente : 0,
                cliente: '',
                tipo_comprobante : 'BOLETA',
                serie_comprobante : '',
                num_comprobante : '',
                impuesto : 0.21,
                total : 0.0,
                totalImpuesto : 0.0,
                totalParcial : 0.0,
                arrayVenta : [],
                arrayCliente : [],
                arrayDetalle : [], 
                listado : 1,            
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorVenta : 0,
                errorMostrarMsjVenta : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,     
                    'last_page' : 0,     
                    'from' : 0,
                    'to' : 0,     
                },
                offset : 3,
                criterio : 'num_comprobante',
                buscar : '',
                criterioA: 'nombre',
                buscarA: '',
                arrayArticulo : [],
                idarticulo : 0,
                codigo : '',
                articulo : '',
                precio : 0,
                cantidad : 0,
                descuento : 0,
                stock : 0
            }
        },
        components: {
            vSelect
        },
        computed:{
            // funcion 
            isActived: function(){               
                return this.pagination.current_page;
            },
            // calcula los elementos de la paginacion
            pagesNumber: function(){
                 // si la pagina es diferente del ultimo elemento de la pag actual, retorna un array vacio 
                if(!this.pagination.to){
                    return [];
                }
                // almacena la resta de la pagina actual(pagintion.currente), menos el offset y lo almacena en la variable from
                var from = this.pagination.current_page - this.offset;
                if(from < 1) {
                    from = 1;
                }
                // to almacena la suma de la variable from mas el doble de lo almacenado en la var offset
                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    // establece el valor de esa ultima pagina a la variable to
                    to = this.pagination.last_page;
                }
                // declara una var pagesArray
                var pagesArray = [];
                // desde la pag actual sea menor igual a la ultima pag
                while(from <= to) {
                    // se valla agregando al from a traves del metodo push al arreglo pagesArray
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },
            calcularTotal: function(){
                var resultado=0.0;
                for (var i=0;i<this.arrayDetalle.length;i++){
                    resultado=resultado+(this.arrayDetalle[i].precio*this.arrayDetalle[i].cantidad-this.arrayDetalle[i].descuento)
                } 
                return resultado;           
                
            }
        },
        // desclara la propiedad metodos
        methods : {
            // crear un metodo que retorna listado de registros
            listarVenta (page,buscar,criterio){
                let me=this;
                var url= '/venta?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                // mediante el metodo axios con el verbo get obtengo todo lo que devuelve la uri /categoria (metodo index del controlador)
                axios.get(url).then(function (response) {
                  var respuesta = response.data;  
                // esto es para almacenar nuestro contendo respone en nuestro array
                  me.arrayVenta = respuesta.ventas.data;
                  me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
            },
            selectCliente(search,loading){
                let me=this;
                loading(true);

                var url= '/cliente/selectCliente?filtro='+search;
                // mediante el metodo axios con el verbo get obtengo todo lo que devuelve la uri /categoria (metodo index del controlador)
                axios.get(url).then(function (response) {
                  var respuesta = response.data;  
                // esto es para almacenar nuestro contendo respone en nuestro array
                  q: search    
                  me.arrayCliente = respuesta.clientes;
                  loading(false)         
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
            },
            getDatosCliente(val1){
               let me = this;
               me.loading = true;
               me.idcliente = val1.id; 
            },
            buscarArticulo(){
                let me=this;
                var url='/articulo/buscarArticuloVenta?filtro=' + me.codigo;

                axios.get(url).then(function (response){
                    var respuesta = response.data;
                    me.arrayArticulo = respuesta.articulos;

                    if(me.arrayArticulo.length>0){
                         me.articulo = me.arrayArticulo[0]['nombre'];
                         me.idarticulo = me.arrayArticulo[0]['id'];
                         me.precio = me.arrayArticulo[0]['precio_venta'];
                         me.stock = me.arrayArticulo[0]['stock'];   
                    }
                    else{
                         me.articulo ='No existe articulo';
                         me.idarticulo = 0;    
                    }
                })
                .catch(function (error){
                    // en caso contrario se muestran los errores por consola
                    console.log(error);
                });
            },
            pdfVenta(id){
                window.open('http://localhost:8000/venta/pdf/'+ id + ',' + '_blank');
            },
            // parm page recibe el num de la pag a mostrar
            cambiarPagina(page,buscar,criterio){
                let me = this;
                // actualiza la pag actual 
                // se indica q al atributo current_page, le asignamos el valor del param recibido
                me.pagination.current_page = page;
                me.listarVenta(page,buscar,criterio);
            },
            encuentra(id){
                var sw=0;
                // recorre el array detalle
                for(var i=0;i<this.arrayDetalle.length;i++){
                    // se recorre todos los indices del array 
                    // comprueba el indice del array en la propiedad idarticulo si es igual al parametro id que recibe el metodo
                    if(this.arrayDetalle[i].idarticulo==id){
                        sw=true;
                    }
                }
                return sw;

            },
            eliminarDetalle(index){
                let me = this;
                me.arrayDetalle.splice(index,1);
            },
            agregarDetalle(){
                let me=this;
                if (me.idarticulo==0 || me.cantidad==0 || me.precio==0){                    
                }
                else{
                    // si el mismo articulo ya se encuetra agregado en el detalle 
                    // mostramos una aletra
                    if(me.encuentra(me.idarticulo)){

                        const swalWithBootstrapButtons = Swal.mixin({
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        buttonsStyling: false,
                        })
                        
                        swalWithBootstrapButtons.fire({
                            type: 'error',
                            title: 'Error...',
                            text: 'Ese articulo ya se encuantra agregado!',
                        })
                    }
                    else{
                        if(me.cantidad>me.stock){
                            const swalWithBootstrapButtons = Swal.mixin({
                            confirmButtonClass: 'btn btn-success',
                            cancelButtonClass: 'btn btn-danger',
                            buttonsStyling: false,
                            })
                            
                            swalWithBootstrapButtons.fire({
                                type: 'error',
                                title: 'Error...',
                                text: 'No hay stock disponible!',
                            })
                        }
                        else{
                            me.arrayDetalle.push({
                                idarticulo: me.idarticulo,
                                articulo: me.articulo,
                                cantidad: me.cantidad,
                                precio: me.precio,
                                descuento: me.descuento,
                                stock: me.stock
                            });
                            me.codigo="";
                            me.idarticulo=0;
                            me.articulo="";
                            me.cantidad=0;
                            me.precio=0;
                            me.descuento=0;
                            me.stock=0;
                        }
                    }                  
                }                
            },
            agregarDetalleModal(data =[]){
                let me=this;
                if(me.encuentra(data['id'])){

                    const swalWithBootstrapButtons = Swal.mixin({
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    })
                    
                    swalWithBootstrapButtons.fire({
                        type: 'error',
                        title: 'Error...',
                        text: 'Ese articulo ya se encuantra agregado!',
                    })
                }
                else{
                    me.arrayDetalle.push({
                        idarticulo: data['id'],
                        articulo: data['nombre'],
                        cantidad: 1,
                        precio: data['precio_venta'],
                        descuento: 0,
                        stock: data['stock'] 
                    });                   
                } 
            },
            listarArticulo (buscar,criterio){
                let me=this;
                var url= '/articulo/listarArticuloVenta?buscar='+ buscar + '&criterio=' + criterio;
                // mediante el metodo axios con el verbo get obtengo todo lo que devuelve la uri /categoria (metodo index del controlador)
                axios.get(url).then(function (response) {
                  var respuesta = response.data;  
                // esto es para almacenar nuestro contendo respone en nuestro array
                  me.arrayArticulo = respuesta.articulos.data;
                 
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
            },
            registrarVenta (){
                if (this.validarVenta()){
                    return;
                }
                // hace refernecia a este mismo archivo
                let me = this; 
                // axios con el verbo post con la ruta indicada, 2°param los valores a registrar
                // indicando las variables de la propiedad data
                axios.post('/venta/registrar',{
                    'idcliente': this.idcliente,
                    'tipo_comprobante' : this.tipo_comprobante,
                    'serie_comprobante': this.serie_comprobante,
                    'num_comprobante' : this.num_comprobante,
                    'impuesto': this.impuesto,
                    'total': this.total,
                    'data': this.arrayDetalle
                    
                }).then(function (response){
                     
                    // si todo esta correcto se ejecutan los sig metodos
                    me.listado=1;
                    me.listarVenta(1,'','num_comprobante'); 
                    me.idcliente=0;
                    me.tipo_comprobante='BOLETA';
                    me.serie_comprobante='';
                    me.num_comprobante='';
                    me.impuesto=0.21;
                    me.total=0.0;
                    me.idarticulo=0;
                    me.articulo='';
                    me.cantidad=0;
                    me.precio=0; 
                    me.stock=0;
                    me.codigo='';
                    me.descuento=0;          
                    me.arrayDetalle=[];
                    window.open('http://localhost:8000/venta/pdf/'+ response.data.id + ',' + '_blank');
                    
                }).catch(function (error){
                    // en caso contrario se muestran los errores por consola
                    console.log(error);
                });
            },                      
            validarVenta(){
                let me=this;
                me.errorVenta = 0;
                // array vacio 
                me.errorMostrarMsjVenta = [];
                var art;

                me.arrayDetalle.map(function(x){
                    if (x.cantidad>x.stock){
                        art=x.articulo + " con stock insuficiente";
                        me.errorMostrarMsjVenta.push(art);
                    }
                });
                // se verifica si el nombre de la categoria esta vacio
                // si lo esta al arreglo se le inserta mediante el metodo push de js un msj 
                // indicando q no puede estar vacio debido a q en la db se indica q no puede ser null
                if (me.idcliente==0) me.errorMostrarMsjVenta.push("Seleccione un cliente.");
                if (me.tipo_comprobante==0) me.errorMostrarMsjVenta.push("Seleccione el comprobante.");
                if (!me.num_comprobante) me.errorMostrarMsjVenta.push("Ingrese el numero de comprobante.");
                if (!me.impuesto) me.errorMostrarMsjVenta.push("Ingrese el impuesto de compra.");
                if (me.arrayDetalle.length<=0) me.errorMostrarMsjVenta.push("Ingrese detalles.");

                if (me.errorMostrarMsjVenta.length) me.errorVenta = 1;

                return me.errorVenta;
            },
            mostrarDetalle(){
                let me=this;
                me.listado=0;
                me.idproveedor=0;
                me.tipo_comprobante='BOLETA';
                me.serie_comprobante='';
                me.num_comprobante='';
                me.impuesto=0.21;
                me.total=0.0;
                me.idarticulo=0;
                me.articulo='';
                me.cantidad=0;
                me.precio=0;           
                me.arrayDetalle=[];
            },
            ocultarDetalle(){
                this.listado=1;
            },
            verVenta(id){
                let me=this;
                this.listado=2;

                //Obtener los datos del ingreso
                var arrayVentaT=[];
                var url= '/venta/obtenerCabecera?id=' + id;
                // mediante el metodo axios con el verbo get obtengo todo lo que devuelve la uri /categoria (metodo index del controlador)
                axios.get(url).then(function (response) {
                  var respuesta = response.data;  
                // esto es para almacenar nuestro contendo respone en nuestro array
                  arrayVentaT = respuesta.venta;

                  me.cliente = arrayVentaT[0]['nombre'];
                  me.tipo_comprobante = arrayVentaT[0]['tipo_comprobante'];
                  me.serie_comprobante = arrayVentaT[0]['serie_comprobante'];
                  me.num_comprobante = arrayVentaT[0]['num_comprobante'];
                  me.impuesto = arrayVentaT[0]['impuesto'];
                  me.total = arrayVentaT[0]['total'];
                
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });

                //Obtener los datos de los detalles
                var urld= '/venta/obtenerDetalles?id=' + id;
                // mediante el metodo axios con el verbo get obtengo todo lo que devuelve la uri /categoria (metodo index del controlador)
                axios.get(urld).then(function (response) {
                  console.log(response);
                  var respuesta = response.data;  
                // esto es para almacenar nuestro contendo respone en nuestro array
                  me.arrayDetalle = respuesta.detalles;               
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
            },
            cerrarModal (){
                this.modal=0;
                this.tituloModal='';
                // this.nombre='';
                // this.tipo_documento='DNI';
                // this.num_documento='';
                // this.direccion='';
                // this.telefono='';
                // this.email='';
                // this.usuario='';
                // this.password='';
                // this.idrol=0;
                // this.errorPersona=0;               
            },
            // espera 3 param 1-es el nombre del modelo, 2-accion(registrar o actulizar) 3-el objeto correspondiente a una fila
            abrirModal (){
                // this.selectRol();
                // //selectiva mult evalua el 1°param si el texto almacenado el var modelo es igual a categoria
                // switch (modelo) {
                //     case "persona":
                //     {
                //         // se ejecuta el sig codigo evaluando si es reg o act
                //         switch (accion) {
                //             case 'registrar':
                //             {
                                // nuestra variable modal = 1
                                this.arrayArticulo=[];
                                this.modal = 1;
                                this.tituloModal = 'Seleccione uno o varios Articulos';
                //                 this.nombre='';
                //                 this.tipo_documento='DNI';
                //                 this.num_documento='';
                //                 this.direccion='';
                //                 this.telefono='';
                //                 this.email='';
                //                 this.usuario='';
                //                 this.password='';
                //                 this.idrol=0;
                //                 this.tipoAccion = '1';
                //                 break;
                //             }
                //             case 'actualizar':
                //             {
                //                 this.modal = 1;
                //                 this.tituloModal = 'Actualizar Usuario';
                //                 this.tipoAccion = 2;
                //                 this.persona_id = data ['id'];
                //                 this.nombre = data['nombre'];
                //                 this.tipo_documento = data ['tipo_documento'];
                //                 this.num_documento = data['num_documento'];
                //                 this.direccion = data ['direccion'];
                //                 this.telefono = data['telefono'];
                //                 this.email = data ['email'];
                //                 this.usuario = data ['usuario'];
                //                 this.password = data ['password'];
                //                 this.idrol = data ['idrol'];
                //                 break;
                //             }
                //         }
                //     }
                // }
            },
            desactivarVenta(id){
                const swalWithBootstrapButtons = Swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: 'Estas seguro de anular esta venta?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {

                    let me = this; 
                    // axios con el verbo post con la ruta indicada, 2°param los valores a registrar
                    // indicando las variables de la propiedad data
                    axios.put('/venta/desactivar',{
                        'id' : id
                    }).then(function (response){
                        // si todo esta correcto se ejecutan los sig metodos
                        me.listarVenta(1,'','num_comprobante');   
                        swalWithBootstrapButtons.fire(
                        'Anulado!',
                        'La venta ha sido anulado con exito.',
                        'success'
                        )                            
                    }).catch(function (error){
                        // en caso contrario se muestran los errores por consola
                        console.log(error);
                    });

                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    // swalWithBootstrapButtons.fire(
                   
                    // )
                }
                })
            },
        },
        mounted() {
            // hacer referencia al metodo listar categoria
            this.listarVenta(1,this.buscar,this.criterio);
        }
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    @media (min-width: 600px) {
        .btnagregar {
            margin-top: 2rem;
        }
    }
</style>


