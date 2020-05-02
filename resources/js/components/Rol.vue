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
                        <i class="fa fa-align-justify"></i> Roles
                        <!-- para las ventanas modal ya nose utiliza jquery sino vue por eso se elimino el data-terget -->
                        <!-- al boton se le agrega una directiva @click con el metodo abrirModal y los parametros cat. y regis. -->
                        <!-- <button type="button" @click="abrirModal('categoria', 'registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button> -->
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                      <option value="descripcion">Descripción</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarRol(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarRol(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <!-- <th>Opciones</th> -->
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- objeto categoria -->
                                <tr v-for="rol in arrayRol" :Key="rol.id">
                                    <!-- <td> -->
                                        <!-- para actualizar se envia el objeto (categoria) sin apostrofos -->
                                        <!-- <button type="button" @click="abrirModal('categoria', 'actualizar', categoria)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp; -->
                                        <!-- si la condicion almacenada en el obj categoria es 1 (activa), voy a mostrar un boton haciendo referencia al metodo descativarCategoria -->
                                        <!-- <template v-if="categoria.condicion"> -->
                                            <!-- con el boton se desactiva la categoria -->
                                            <!-- <button type="button" class="btn btn-danger btn-sm" @click="desactivarCategoria(categoria.id)"> -->
                                                <!-- si la categoria esta activa muestra lo icono  -->
                                                <!-- <i class="icon-trash"></i>
                                            </button>
                                        </template> -->
                                        <!-- si la condicion almacenada en el obj categoria es 0 (desactivada), voy a mostrar un boton haciendo referencia al metodo descativarCategoria -->
                                        <!-- <template v-else> -->
                                            <!-- con el boton se activa la categoria -->
                                            <!-- <button type="button" class="btn btn-info btn-sm" @click="activarCategoria(categoria.id)"> -->
                                                <!-- si la categoria esta activa muestra lo icono  -->
                                                <!-- <i class="icon-check"></i>
                                            </button>
                                        </template> -->
                                    <!-- </td> -->
                                    <td v-text="rol.nombre"></td>
                                    <td v-text="rol.descripcion"></td>
                                    <td>
                                        <div v-if="rol.condicion">
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
            <!-- Inicio del modal agregar/actualizar -->
            <!-- se visualiza la ventana modal cuando la variable sea igual a 1 se le asigna la clase mostrar con :class -->
            <!-- <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                         v-model realiza una coexion bidireccional entre el input y la capa de datos la propiedad data del cod javascript -->
                                        <!-- "conecta" al fontend con la capas de  datos -->
                                        <!-- <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de categoría"> -->
                                      
                                    <!-- </div>
                                </div> -->
                                <!-- <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="descripcion" class="form-control" placeholder="Ingrese descripcion">
                                    </div>
                                </div> -->

                                <!-- <div v-show="errorCategoria" class="form-group row div-error">
                                    <div class="text-center text-error">  -->
                                        <!-- iterar dentro de nuestro objeto error -->
                                        <!-- <div v-for="error in errorMostrarMsjCategoria" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarCategoria()">Guardar</button>
                             <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarCategoria()">Actualizar</button>
                        </div>
                    </div> --> 
                    <!-- /.modal-content -->
                <!-- </div> -->
                <!-- /.modal-dialog -->
            <!-- </div>  -->
            <!--Fin del modal-->                       
        </main>
</template>

<script>
    export default {
        data(){
            return {  
                // corresponde al id de la categoria q quiero actuliazar   
                // se declaran las variables q se utilizan
                // la data que reciba nuestro objeto categoria se almacene en este array
                // var modal inicializa en 0 indica si se muestra o se oculta la ventana
                // var titulo para mostra el titulo correspondiente
                // var tipoAccion muestra el boton correspondiente segun sea act o regis
                rol_id : 0,
                nombre : '',
                descripcion : '',
                arrayRol : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
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
                buscar : ''
            }
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
            listarRol (page,buscar,criterio){
                let me=this;
                var url= '/rol?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                // mediante el metodo axios con el verbo get obtengo todo lo que devuelve la uri /categoria (metodo index del controlador)
                axios.get(url).then(function (response) {
                  var respuesta = response.data;  
                // esto es para almacenar nuestro contendo respone en nuestro array
                  me.arrayRol = respuesta.roles.data;
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
                me.listarRol(page,buscar,criterio);
            },
        //     registrarCategoria (){
        //         if (this.validarCategoria()){
        //             return;
        //         }
        //         // hace refernecia a este mismo archivo
        //         let me = this; 
        //         // axios con el verbo post con la ruta indicada, 2°param los valores a registrar
        //         // indicando las variables de la propiedad data
        //         axios.post('/categoria/registrar',{
        //             'nombre': this.nombre,
        //             'descripcion' : this.descripcion
        //         }).then(function (response){
        //             // si todo esta correcto se ejecutan los sig metodos
        //             me.cerrarModal();
        //             me.listarCategoria(1,'','nombre');                    
        //         }).catch(function (error){
        //             // en caso contrario se muestran los errores por consola
        //             console.log(error);
        //         });
        //     },
        //     actualizarCategoria (){
        //         if (this.validarCategoria()){
        //             return;
        //         }
        //         // hace refernecia a este mismo archivo
        //         let me = this; 
        //         // axios con el verbo post con la ruta indicada, 2°param los valores a registrar
        //         // indicando las variables de la propiedad data
        //         axios.put('/categoria/actualizar',{
        //             'nombre': this.nombre,
        //             'descripcion' : this.descripcion,
        //             'id' : this.categoria_id
        //         }).then(function (response){
        //             // si todo esta correcto se ejecutan los sig metodos
        //             me.cerrarModal();
        //             me.listarCategoria(1,'','nombre');                    
        //         }).catch(function (error){
        //             // en caso contrario se muestran los errores por consola
        //             console.log(error);
        //         });
        //     },
        //     desactivarCategoria(id){
        //         const swalWithBootstrapButtons = Swal.mixin({
        //         confirmButtonClass: 'btn btn-success',
        //         cancelButtonClass: 'btn btn-danger',
        //         buttonsStyling: false,
        //         })

        //         swalWithBootstrapButtons.fire({
        //         title: 'Estas seguro de desactivar esta categoria?',
        //         type: 'warning',
        //         showCancelButton: true,
        //         confirmButtonText: 'Aceptar!',
        //         cancelButtonText: 'Cancelar',
        //         reverseButtons: true
        //         }).then((result) => {
        //         if (result.value) {

        //             let me = this; 
        //             // axios con el verbo post con la ruta indicada, 2°param los valores a registrar
        //             // indicando las variables de la propiedad data
        //             axios.put('/categoria/desactivar',{
        //                 'id' : id
        //             }).then(function (response){
        //                 // si todo esta correcto se ejecutan los sig metodos
        //                 me.listarCategoria(1,'','nombre');   
        //                 swalWithBootstrapButtons.fire(
        //                 'Desactivado!',
        //                 'El registro ha sido desactivado con exito.',
        //                 'success'
        //                 )                            
        //             }).catch(function (error){
        //                 // en caso contrario se muestran los errores por consola
        //                 console.log(error);
        //             });

                    
        //         } else if (
        //             // Read more about handling dismissals
        //             result.dismiss === Swal.DismissReason.cancel
        //         ) {
        //             // swalWithBootstrapButtons.fire(
                   
        //             // )
        //         }
        //         })
        //     },
        //     activarCategoria(id){
        //         const swalWithBootstrapButtons = Swal.mixin({
        //         confirmButtonClass: 'btn btn-success',
        //         cancelButtonClass: 'btn btn-danger',
        //         buttonsStyling: false,
        //         })

        //         swalWithBootstrapButtons.fire({
        //         title: 'Estas seguro de activar esta categoria?',
        //         type: 'warning',
        //         showCancelButton: true,
        //         confirmButtonText: 'Aceptar!',
        //         cancelButtonText: 'Cancelar',
        //         reverseButtons: true
        //         }).then((result) => {
        //         if (result.value) {

        //             let me = this; 
        //             // axios con el verbo post con la ruta indicada, 2°param los valores a registrar
        //             // indicando las variables de la propiedad data
        //             axios.put('/categoria/activar',{
        //                 'id' : id
        //             }).then(function (response){
        //                 // si todo esta correcto se ejecutan los sig metodos
        //                 me.listarCategoria(1,'','nombre');   
        //                 swalWithBootstrapButtons.fire(
        //                 'Activado!',
        //                 'El registro ha sido activado con exito.',
        //                 'success'
        //                 )                            
        //             }).catch(function (error){
        //                 // en caso contrario se muestran los errores por consola
        //                 console.log(error);
        //             });

                    
        //         } else if (
        //             // Read more about handling dismissals
        //             result.dismiss === Swal.DismissReason.cancel
        //         ) {
        //             // swalWithBootstrapButtons.fire(
                   
        //             // )
        //         }
        //         })
        //     },
        //     validarCategoria (){
        //         this.errorCategoria = 0;
        //         // array vacio 
        //         this.errorMostrarMsjCategoria = [];
        //         // se verifica si el nombre de la categoria esta vacio
        //         // si lo esta al arreglo se le inserta mediante el metodo push de js un msj 
        //         // indicando q no puede estar vacio debido a q en la db se indica q no puede ser null
        //         if (!this.nombre) this.errorMostrarMsjCategoria.push("El nombre de la categoria no puede estar vacio.");

        //         if (this.errorMostrarMsjCategoria.length) this.errorCategoria = 1;

        //         return this.errorCategoria;
        //     },
        //     cerrarModal (){
        //         this.modal=0;
        //         this.tituloModal='';
        //         this.nombre='';
        //         this.descripcion='';
        //     },
        //     // espera 3 param 1-es el nombre del modelo, 2-accion(registrar o actulizar) 3-el objeto correspondiente a una fila
        //     abrirModal (modelo, accion, data = []){
        //         //selectiva mult evalua el 1°param si el texto almacenado el var modelo es igual a categoria
        //         switch (modelo) {
        //             case "categoria":
        //             {
        //                 // se ejecuta el sig codigo evaluando si es reg o act
        //                 switch (accion) {
        //                     case 'registrar':
        //                     {
        //                         // nuestra variable modal = 1
        //                         this.modal = 1;
        //                         this.tituloModal = 'Registrar Categoria';
        //                         this.nombre = '';
        //                         this.descripcion = '';
        //                         this.tipoAccion = '1';
        //                         break;
        //                     }
        //                     case 'actualizar':
        //                     {
        //                         this.modal = 1;
        //                         this.tituloModal = 'Actualizar categoria';
        //                         this.tipoAccion = 2;
        //                         this.categoria_id = data ['id'];
        //                         this.nombre = data['nombre'];
        //                         this.descripcion = data ['descripcion'];
        //                         break;
        //                     }
        //                 }
        //             }
        //         }
        //     }

        },
        mounted() {
            // hacer referencia al metodo listar categoria
            this.listarRol(1,this.buscar,this.criterio);
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


