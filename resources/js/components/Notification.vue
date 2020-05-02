<template>
    <li class="nav-item d-md-down-none">
        <a class="nav-link" href="#" data-toggle="dropdown">
            <i class="icon-bell"></i>
            <!-- el largo de las notificaciones -->
            <span class="badge badge-pill badge-danger">{{notifications.length}}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-header text-center">
                <strong>Notificaciones</strong>
            </div>
            <div v-if="notifications.length">
            <li v-for="item in listar" :key="item.id">
            <a class="dropdown-item" href="#">
                <!-- lo que llega es una cadena se parse con JSON -->
                <!-- se borra el JSON.PARSE -->
                <!-- <i class="fa fa-envelope-o"></i> {{JSON.parse(item.data).datos.ingresos.msj}} -->
                <i class="fa fa-envelope-o"></i> {{item.ingresos.msj}}
                <span class="badge badge-success">{{item.ingresos.numero}}</span>
            </a>
            <a class="dropdown-item" href="#">
                <i class="fa fa-tasks"></i> {{item.ventas.msj}}
                <span class="badge badge-danger">{{item.ventas.numero}}</span>
            </a>
            </li>
            </div>
            <div v-else>
                <a><span>No tiene notificaciones</span></a>
            </div>
        </div>
    </li>
</template>
<script>
export default {
    // se declara el props notifications
    props : ['notifications'],
    data (){
        return {
            arrayNotifications: []
        }
    },
    computed:{
        listar: function(){
            // acceder a la ultima notificacion con array 0
            // return this.notifications[0];
            this.arrayNotifications = Object.values(this.notifications[0]);
            if (this.notifications == '') {
                return this.arrayNotifications = [];
            } else {
                // capturo la ultima notificacion agregada
                this.arrayNotifications = Object.values(this.notifications[0]);
                // validacion por indice fuera de rango
                if (this.arrayNotifications.length > 3) {
                    // si el tamaño es > 3 es cuando las notificaciones son obtenidas desde el 
                    return Object.values(this.arrayNotifications[4]);
                } else {
                    // si el tamaño es < 3 es cuando las notificaciones son obtenidas desde el 
                    return Object.values(this.arrayNotifications[0]);
                }
            }            
        }
    }    
}
</script>
