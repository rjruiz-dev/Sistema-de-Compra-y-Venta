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
                        <i class="fa fa-align-justify"></i> Ingresos                        
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
                                    <input type="text" v-model="buscar" @keyup.enter="listarIngreso(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarIngreso(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Usuario</th>
                                        <th>Proveedor</th>
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
                                    <tr v-for="ingreso in arrayIngreso" :Key="ingreso.id">
                                        <td>
                                            <!-- para actualizar se envia el objeto (persona) sin apostrofos -->
                                            <button type="button" @click="verIngreso(ingreso.id)" class="btn btn-success btn-sm">
                                            <i class="icon-eye"></i>
                                            </button>
                                            <!-- si la condicion almacenada en el obj categoria es 0 (desactivada), voy a mostrar un boton haciendo referencia al metodo descativarCategoria -->                                            
                                        </td>
                                        <td v-text="ingreso.usuario"></td>
                                        <td v-text="ingreso.nombre"></td>
                                        <td v-text="ingreso.tipo_comprobante"></td>
                                        <td v-text="ingreso.serie_comprobante"></td>
                                        <td v-text="ingreso.num_comprobante"></td>
                                        <td v-text="ingreso.fecha_hora"></td>
                                        <td v-text="ingreso.total"></td>
                                        <td v-text="ingreso.impuesto"></td>          
                                        <td v-text="ingreso.estado"></td>          
                                                                
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
                  
                    <!-- ver ingreso -->
                    <template v-else-if="listado==2">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-grup">
                                    <label for="">Proveedor</label>
                                    <p v-text="proveedor"></p>
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
                                            <td>
                                                {{ detalle.precio*detalle.cantidad }}
                                            </td>
                                        </tr>
                                        
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="3" align="right"><strong>Total Parcial:</strong></td>
                                            <td>$ {{ totalParcial=(total-totalImpuesto).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="3" align="right"><strong>Total Impuesto:</strong></td>
                                            <td>$ {{ totalImpuesto=((total*impuesto)).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="3" align="right"><strong>Total Neto:</strong></td>
                                            <td>$ {{total}}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="4">
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
                ingreso_id : 0,
                idproveedor : '',
                proveedor: '',
                tipo_comprobante : 'BOLETA',
                serie_comprobante : '',
                num_comprobante : '',
                impuesto : 0.21,
                total : 0.0,
                totalImpuesto : 0.0,
                totalParcial : 0.0,
                arrayIngreso : [],
                arrayProveedor : [],
                arrayDetalle : [], 
                listado : 1,            
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorIngreso : 0,
                errorMostrarMsjIngreso : [],
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
                cantidad : 0
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
                    resultado=resultado+(this.arrayDetalle[i].precio*this.arrayDetalle[i].cantidad)
                } 
                return resultado;           
                
            }
        },
        // desclara la propiedad metodos
        methods : {
            // crear un metodo que retorna listado de registros
            listarIngreso (page,buscar,criterio){
                let me=this;
                var url= '/ingreso?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                // mediante el metodo axios con el verbo get obtengo todo lo que devuelve la uri /categoria (metodo index del controlador)
                axios.get(url).then(function (response) {
                  var respuesta = response.data;  
                // esto es para almacenar nuestro contendo respone en nuestro array
                  me.arrayIngreso = respuesta.ingresos.data;
                  me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
            },           
            // parm page recibe el num de la pag a mostrar
            cambiarPagina(page,buscar,criterio){
                let me = this;
                // actualiza la pag actual 
                // se indica q al atributo current_page, le asignamos el valor del param recibido
                me.pagination.current_page = page;
                me.listarIngreso(page,buscar,criterio);
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
            verIngreso(id){
                let me=this;
                this.listado=2;

                //Obtener los datos del ingreso
                var arrayIngresoT=[];
                var url= '/ingreso/obtenerCabecera?id=' + id;
                // mediante el metodo axios con el verbo get obtengo todo lo que devuelve la uri /categoria (metodo index del controlador)
                axios.get(url).then(function (response) {
                  var respuesta = response.data;  
                // esto es para almacenar nuestro contendo respone en nuestro array
                  arrayIngresoT = respuesta.ingreso;

                  me.proveedor = arrayIngresoT[0]['nombre'];
                  me.tipo_comprobante = arrayIngresoT[0]['tipo_comprobante'];
                  me.serie_comprobante = arrayIngresoT[0]['serie_comprobante'];
                  me.num_comprobante = arrayIngresoT[0]['num_comprobante'];
                  me.impuesto = arrayIngresoT[0]['impuesto'];
                  me.total = arrayIngresoT[0]['total'];
                
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });

                //Obtener los datos de los detalles
                var urld= '/ingreso/obtenerDetalles?id=' + id;
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
            }          
        },
        mounted() {
            // hacer referencia al metodo listar categoria
            this.listarIngreso(1,this.buscar,this.criterio);
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


