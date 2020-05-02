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
                        <i class="fa fa-align-justify"></i> Proveedores
                        <!-- para las ventanas modal ya nose utiliza jquery sino vue por eso se elimino el data-terget -->
                        <!-- al boton se le agrega una directiva @click con el metodo abrirModal y los parametros persona. y regis. -->
                        <button type="button" @click="abrirModal('persona', 'registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <!-- Criterios de busqueda -->
                                      <option value="nombre">Nombre</option>
                                      <option value="num_documento">Documento</option>
                                      <option value="email">Email</option>
                                      <option value="telefono">Telefono</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarPersona(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarPersona(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Tipo Documento</th>
                                    <th>Numero</th>
                                    <th>Direccion</th>
                                    <th>Telefono</th>
                                    <th>Email</th>
                                    <th>Contacto</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                <!-- objeto persona -->
                                <tr v-for="persona in arrayPersona" :Key="persona.id">
                                    <td>
                                        <!-- para actualizar se envia el objeto (persona) sin apostrofos -->
                                        <button type="button" @click="abrirModal('persona', 'actualizar', persona)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> 
                                    </td>
                                    <td v-text="persona.nombre"></td>
                                    <td v-text="persona.tipo_documento"></td>
                                    <td v-text="persona.num_documento"></td>
                                    <td v-text="persona.direccion"></td>
                                    <td v-text="persona.telefono"></td>
                                    <td v-text="persona.email"></td>
                                    <td v-text="persona.contacto"></td>          
                                                              
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
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre Empresa (*)</label>
                                    <div class="col-md-9">
                                        <!-- v-model realiza una coexion bidireccional entre el input y la capa de datos la propiedad data del cod javascript -->
                                        <!-- "conecta" al fontend con la capas de  datos -->
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de la Empresa">                                      
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Tipo Documento</label>
                                    <div class="col-md-9">
                                        <!-- v-model realiza una coexion bidireccional entre el input y la capa de datos la propiedad data del cod javascript -->
                                        <!-- "conecta" al fontend con la capas de  datos -->
                                        <select v-model="tipo_documento" class="form-control">
                                            <option value="CUIT">CUIT</option>
                                            <option value="DNI">DNI</option>
                                            <option value="CI">CI</option>
                                        </select>                                      
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Numero</label>
                                    <div class="col-md-9">
                                        <!-- v-model realiza una coexion bidireccional entre el input y la capa de datos la propiedad data del cod javascript -->
                                        <!-- "conecta" al fontend con la capas de  datos -->
                                        <input type="text" v-model="num_documento" class="form-control" placeholder="Numero de documento">                                      
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Direccion</label>
                                    <div class="col-md-9">
                                        <!-- v-model realiza una coexion bidireccional entre el input y la capa de datos la propiedad data del cod javascript -->
                                        <!-- "conecta" al fontend con la capas de  datos -->
                                        <input type="text" v-model="direccion" class="form-control" placeholder="Direccion">                                      
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Telefono Empresa</label>
                                    <div class="col-md-9">
                                        <!-- v-model realiza una coexion bidireccional entre el input y la capa de datos la propiedad data del cod javascript -->
                                        <!-- "conecta" al fontend con la capas de  datos -->
                                        <input type="text" v-model="telefono" class="form-control" placeholder="Telefono de la empresa">                                      
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Email</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="email" class="form-control" placeholder="Email">
                                    </div>
                                </div>
                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Contacto</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="contacto" class="form-control" placeholder="Nombre proveedor">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Telefono de contacto</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="telefono_contacto" class="form-control" placeholder="Telefono del proveedor">
                                    </div>
                                </div>

                                <div v-show="errorPersona" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <!-- iterar dentro de nuestro objeto error -->
                                        <div v-for="error in errorMostrarMsjPersona" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
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
    export default {
        data(){
            return {  
                // corresponde al id de la categoria q quiero actuliazar   
                // se declaran las variables q se utilizan
                // la data que reciba nuestro objeto categoria se almacene en este array
                // var modal inicializa en 0 indica si se muestra o se oculta la ventana
                // var titulo para mostra el titulo correspondiente
                // var tipoAccion muestra el boton correspondiente segun sea act o regis
                persona_id : 0,
                nombre : '',
                tipo_documento : 'CUIT',
                num_documento : '',
                direccion : '',
                telefono : '',
                email : '',
                contacto : '',
                telefono_contacto : '',
                arrayPersona : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorPersona : 0,
                errorMostrarMsPersona : [],
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
            listarPersona (page,buscar,criterio){
                let me=this;
                var url= '/proveedor?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                // mediante el metodo axios con el verbo get obtengo todo lo que devuelve la uri /categoria (metodo index del controlador)
                axios.get(url).then(function (response) {
                  var respuesta = response.data;  
                // esto es para almacenar nuestro contendo respone en nuestro array
                  me.arrayPersona = respuesta.personas.data;
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
                me.listarPersona(page,buscar,criterio);
            },
            registrarPersona (){
                if (this.validarPersona()){
                    return;
                }
                // hace refernecia a este mismo archivo
                let me = this; 
                // axios con el verbo post con la ruta indicada, 2°param los valores a registrar
                // indicando las variables de la propiedad data
                axios.post('/proveedor/registrar',{
                    'nombre': this.nombre,
                    'tipo_documento' : this.tipo_documento,
                    'num_documento': this.num_documento,
                    'direccion' : this.direccion,
                    'telefono': this.telefono,
                    'email': this.email,
                    'contacto': this.contacto,
                    'telefono_contacto': this.telefono_contacto
                }).then(function (response){
                    // si todo esta correcto se ejecutan los sig metodos
                    me.cerrarModal();
                    me.listarPersona(1,'','nombre');                    
                }).catch(function (error){
                    // en caso contrario se muestran los errores por consola
                    console.log(error);
                });
            },
            actualizarPersona (){
                if (this.validarPersona()){
                    return;
                }
                // hace refernecia a este mismo archivo
                let me = this; 
                // axios con el verbo post con la ruta indicada, 2°param los valores a registrar
                // indicando las variables de la propiedad data
                axios.put('/proveedor/actualizar',{
                    'nombre': this.nombre,
                    'tipo_documento' : this.tipo_documento,
                    'num_documento': this.num_documento,
                    'direccion' : this.direccion,
                    'telefono': this.telefono,
                    'email': this.email,
                    'contacto': this.contacto,
                    'telefono_contacto': this.telefono_contacto,
                    'id' : this.persona_id
                }).then(function (response){
                    // si todo esta correcto se ejecutan los sig metodos
                    me.cerrarModal();
                    me.listarPersona(1,'','nombre');                    
                }).catch(function (error){
                    // en caso contrario se muestran los errores por consola
                    console.log(error);
                });
            },           
            validarPersona (){
                this.errorPersona = 0;
                // array vacio 
                this.errorMostrarMsjPersona = [];
                // se verifica si el nombre de la categoria esta vacio
                // si lo esta al arreglo se le inserta mediante el metodo push de js un msj 
                // indicando q no puede estar vacio debido a q en la db se indica q no puede ser null
                if (!this.nombre) this.errorMostrarMsjPersona.push("El nombre de la persona no puede estar vacio.");

                if (this.errorMostrarMsjPersona.length) this.errorPersona = 1;

                return this.errorPersona;
            },
            cerrarModal (){
                this.modal=0;
                this.tituloModal='';
                this.nombre='';
                this.tipo_documento='CUIT';
                this.num_documento='';
                this.direccion='';
                this.telefono='';
                this.email='';
                this.contacto='';
                this.telefono_contacto='';
                this.errorPersona=0;
            },
            // espera 3 param 1-es el nombre del modelo, 2-accion(registrar o actulizar) 3-el objeto correspondiente a una fila
            abrirModal (modelo, accion, data = []){
                //selectiva mult evalua el 1°param si el texto almacenado el var modelo es igual a categoria
                switch (modelo) {
                    case "persona":
                    {
                        // se ejecuta el sig codigo evaluando si es reg o act
                        switch (accion) {
                            case 'registrar':
                            {
                                // nuestra variable modal = 1
                                this.modal = 1;
                                this.tituloModal = 'Registrar Proveedor';
                                this.nombre='';
                                this.tipo_documento='CUIT';
                                this.num_documento='';
                                this.direccion='';
                                this.telefono='';
                                this.email='';
                                this.contacto='';
                                this.telefono_contacto='';
                                this.tipoAccion = '1';
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Actualizar Proveedor';
                                this.tipoAccion = 2;
                                this.persona_id = data ['id'];
                                this.nombre = data['nombre'];
                                this.tipo_documento = data ['tipo_documento'];
                                this.num_documento = data['num_documento'];
                                this.direccion = data ['direccion'];
                                this.telefono = data['telefono'];
                                this.email = data ['email'];
                                this.contacto = data ['contacto'];
                                this.telefono_contacto = data ['telefono_contacto'];
                                break;
                            }
                        }
                    }
                }
            }

        },
        mounted() {
            // hacer referencia al metodo listar categoria
            this.listarPersona(1,this.buscar,this.criterio);
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


