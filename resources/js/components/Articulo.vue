<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <!-- una sola miga de pan y hace referencia a la raiz del proyecto -->
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Articulos
                        <!-- para las ventanas modal ya nose utiliza jquery sino vue por eso se elimino el data-terget -->
                        <!-- al boton se le agrega una directiva @click con el metodo abrirModal y los parametros cat. y regis. -->
                        <button type="button" @click="abrirModal('articulo', 'registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <button type="button" @click="cargarPdf()" class="btn btn-info">
                            <i class="icon-doc"></i>&nbsp;Reporte
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                      <option value="descripcion">Descripción</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarArticulo(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarArticulo(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Codigo</th>
                                    <th>Nombre</th>
                                    <th>Categoria</th>
                                    <th>Precio Venta</th>
                                    <th>Stock</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- objeto categoria -->
                                <tr v-for="articulo in arrayArticulo" :Key="articulo.id">
                                    <td>
                                        <!-- para actualizar se envia el objeto (categoria) sin apostrofos -->
                                        <button type="button" @click="abrirModal('articulo', 'actualizar', articulo)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <!-- si la condicion almacenada en el obj categoria es 1 (activa), voy a mostrar un boton haciendo referencia al metodo descativarCategoria -->
                                        <template v-if="articulo.condicion">
                                            <!-- con el boton se desactiva la categoria -->
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarArticulo(articulo.id)">
                                                <!-- si la categoria esta activa muestra lo icono  -->
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <!-- si la condicion almacenada en el obj categoria es 0 (desactivada), voy a mostrar un boton haciendo referencia al metodo descativarCategoria -->
                                        <template v-else>
                                            <!-- con el boton se activa la categoria -->
                                            <button type="button" class="btn btn-info btn-sm" @click="activarArticulo(articulo.id)">
                                                <!-- si la categoria esta activa muestra lo icono  -->
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="articulo.codigo"></td>
                                    <td v-text="articulo.nombre"></td>
                                    <td v-text="articulo.nombre_categoria"></td>
                                    <td v-text="articulo.precio_venta"></td>
                                    <td v-text="articulo.stock"></td>
                                    <td v-text="articulo.descripcion"></td>
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
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Categoria</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="idcategoria">
                                            <option value="0" disabled>Seleccione</option>
                                            <option v-for="categoria in arrayCategoria" :key="categoria.id" :value="categoria.id" v-text="categoria.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Codigo</label>
                                    <div class="col-md-9">
                                        <!-- v-model realiza una coexion bidireccional entre el input y la capa de datos la propiedad data del cod javascript -->
                                        <!-- "conecta" al fontend con la capas de  datos -->
                                        <input type="text" v-model="codigo" class="form-control" placeholder="Codigo de barras"> 
                                        <!-- VALUE porque  el valor se muestra en codigo -->
                                        <!-- option para especificar el formato -->
                                        <barcode :value="codigo" :options="{ format: 'EAN-13'}">
                                            <!-- si no se muestra nada se visualiza el texto siguiente -->
                                            Generando codigo de barras.
                                        </barcode>                                     
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <!-- v-model realiza una coexion bidireccional entre el input y la capa de datos la propiedad data del cod javascript -->
                                        <!-- "conecta" al fontend con la capas de  datos -->
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de articulo">                                      
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Precio Venta</label>
                                    <div class="col-md-9">
                                        <!-- v-model realiza una coexion bidireccional entre el input y la capa de datos la propiedad data del cod javascript -->
                                        <!-- "conecta" al fontend con la capas de  datos -->
                                        <input type="number" v-model="precio_venta" class="form-control" placeholder="">                                      
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Stock</label>
                                    <div class="col-md-9">
                                        <!-- v-model realiza una coexion bidireccional entre el input y la capa de datos la propiedad data del cod javascript -->
                                        <!-- "conecta" al fontend con la capas de  datos -->
                                        <input type="number" v-model="stock" class="form-control" placeholder="">                                      
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="descripcion" class="form-control" placeholder="Ingrese descripcion">
                                    </div>
                                </div>

                                <div v-show="errorArticulo" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <!-- iterar dentro de nuestro objeto error -->
                                        <div v-for="error in errorMostrarMsjArticulo" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarArticulo()">Guardar</button>
                             <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarArticulo()">Actualizar</button>
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
    import VueBarcode from 'vue-barcode';
    export default {
        data(){
            return {  
                // corresponde al id de la categoria q quiero actuliazar   
                // se declaran las variables q se utilizan
                // la data que reciba nuestro objeto categoria se almacene en este array
                // var modal inicializa en 0 indica si se muestra o se oculta la ventana
                // var titulo para mostra el titulo correspondiente
                // var tipoAccion muestra el boton correspondiente segun sea act o regis
               
               // alamacena la llave primaria del articulo
                articulo_id : 0,
                idcategoria : 0,
                nombre_categoria : '',
                codigo : '',
                nombre : '',
                precio_venta : 0,
                stock : 0,
                descripcion : '',
                arrayArticulo : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorArticulo : 0,
                errorMostrarMsjArticulo : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,     
                    'last_page' : 0,     
                    'from' : 0,
                    'to' : 0,     
                },
                offset : 3,
                criterio : 'nombre',
                buscar : '',
                arrayCategoria : []
            }
        },
         components: {
            'barcode': VueBarcode
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
            }
        },
        // desclara la propiedad metodos
        methods : {
            // crear un metodo que retorna listado de registros
            listarArticulo (page,buscar,criterio){
                let me=this;
                var url= '/articulo?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                // mediante el metodo axios con el verbo get obtengo todo lo que devuelve la uri /categoria (metodo index del controlador)
                axios.get(url).then(function (response) {
                  var respuesta = response.data;  
                // esto es para almacenar nuestro contendo respone en nuestro array
                  me.arrayArticulo = respuesta.articulos.data;
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
                me.listarArticulo(page,buscar,criterio);
            },
            cargarPdf(){
                window.open('http://localhost:8000/articulo/listarPdf','_blank');
            },
            selectCategoria(){
                let me=this;
                var url= '/categoria/selectCategoria';
                // mediante el metodo axios con el verbo get obtengo todo lo que devuelve la uri /categoria (metodo index del controlador)
                axios.get(url).then(function (response) {
                // console.log(response);
                  var respuesta = response.data;  
                // esto es para almacenar nuestro contendo respone en nuestro array
                  me.arrayCategoria = respuesta.categorias;
                 
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
            },
            registrarArticulo (){
                if (this.validarArticulo()){
                    return;
                }
                // hace refernecia a este mismo archivo
                let me = this; 
                // axios con el verbo post con la ruta indicada, 2°param los valores a registrar
                // indicando las variables de la propiedad data
                axios.post('/articulo/registrar',{
                    'idcategoria': this.idcategoria,
                    'codigo': this.codigo,
                    'nombre': this.nombre,
                    'stock': this.stock,
                    'precio_venta': this.precio_venta,
                    'descripcion' : this.descripcion
                }).then(function (response){
                    // si todo esta correcto se ejecutan los sig metodos
                    me.cerrarModal();
                    me.listarArticulo(1,'','nombre');                    
                }).catch(function (error){
                    // en caso contrario se muestran los errores por consola
                    console.log(error);
                });
            },
            actualizarArticulo (){
                if (this.validarArticulo()){
                    return;
                }
                // hace refernecia a este mismo archivo
                let me = this; 
                // axios con el verbo post con la ruta indicada, 2°param los valores a registrar
                // indicando las variables de la propiedad data
                axios.put('/articulo/actualizar',{
                    'idcategoria': this.idcategoria,
                    'codigo': this.codigo,
                    'nombre': this.nombre,
                    'stock': this.stock,
                    'precio_venta': this.precio_venta,
                    'descripcion' : this.descripcion,
                    'id': this.articulo_id
                }).then(function (response){
                    // si todo esta correcto se ejecutan los sig metodos
                    me.cerrarModal();
                    me.listarArticulo(1,'','nombre');                    
                }).catch(function (error){
                    // en caso contrario se muestran los errores por consola
                    console.log(error);
                });
            },
            desactivarArticulo(id){
                const swalWithBootstrapButtons = Swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: 'Estas seguro de desactivar esta articulo?',
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
                    axios.put('/articulo/desactivar',{
                        'id' : id
                    }).then(function (response){
                        // si todo esta correcto se ejecutan los sig metodos
                        me.listarArticulo(1,'','nombre');   
                        swalWithBootstrapButtons.fire(
                        'Desactivado!',
                        'El registro ha sido desactivado con exito.',
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
            activarArticulo(id){
                const swalWithBootstrapButtons = Swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: 'Estas seguro de activar esta articulo?',
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
                    axios.put('/articulo/activar',{
                        'id' : id
                    }).then(function (response){
                        // si todo esta correcto se ejecutan los sig metodos
                        me.listarArticulo(1,'','nombre');   
                        swalWithBootstrapButtons.fire(
                        'Activado!',
                        'El registro ha sido activado con exito.',
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
            validarArticulo (){
                this.errorArticulo = 0;
                // array vacio 
                this.errorMostrarMsjArticulo = [];
                // se verifica si el nombre de la categoria esta vacio
                // si lo esta al arreglo se le inserta mediante el metodo push de js un msj 
                // indicando q no puede estar vacio debido a q en la db se indica q no puede ser null
                if (this.idcategoria==0) this.errorMostrarMsjArticulo.push("Seleccione una categoria.");
                if (!this.nombre) this.errorMostrarMsjArticulo.push("El nombre del articulo no puede estar vacio.");
                if (!this.stock) this.errorMostrarMsjArticulo.push("El stock del articulo debe ser un numero y no puede estar vacio.");
                if (!this.precio_venta) this.errorMostrarMsjArticulo.push("El precio de venta del articulo debe ser un numero y no puede estar vacio.");
               
                if (this.errorMostrarMsjArticulo.length) this.errorArticulo = 1;

                return this.errorArticulo;
            },
            cerrarModal (){
                this.modal=0;
                this.tituloModal='';
                this.idcategoria=0;
                this.nombre_categoria='';
                this.codigo='';                
                this.nombre='';
                this.precio_venta=0;
                this.stock=0;
                this.descripcion='';
                this.errorArticulo=0;
            },
            // espera 3 param 1-es el nombre del modelo, 2-accion(registrar o actulizar) 3-el objeto correspondiente a una fila
            abrirModal (modelo, accion, data = []){
                //selectiva mult evalua el 1°param si el texto almacenado el var modelo es igual a categoria
                switch (modelo) {
                    case "articulo":
                    {
                        // se ejecuta el sig codigo evaluando si es reg o act
                        switch (accion) {
                            case 'registrar':
                            {
                                // nuestra variable modal = 1
                                this.modal = 1;
                                this.tituloModal = 'Registrar Articulo';
                                this.idcategoria=0;
                                this.nombre_categoria='';
                                this.codigo='';
                                this.nombre = '';
                                this.precio_venta=0;
                                this.stock=0;
                                this.descripcion = '';
                                this.tipoAccion = '1';
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Actualizar Articulo';
                                this.tipoAccion = 2;
                                this.articulo_id = data ['id'];
                                this.idcategoria = data ['idcategoria'];
                                this.codigo = data ['codigo'];
                                this.nombre = data ['nombre']; 
                                this.stock = data ['stock'];
                                this.precio_venta = data['precio_venta'];
                                this.descripcion = data ['descripcion'];
                                break;
                            }
                        }
                    }
                }
                this.selectCategoria();
            }

        },
        mounted() {
            // hacer referencia al metodo listar categoria
            this.listarArticulo(1,this.buscar,this.criterio);
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
</style>


