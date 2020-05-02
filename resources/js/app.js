
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.$ = window.jQuery = require('jquery');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('categoria', require('./components/Categoria.vue').default);
Vue.component('articulo', require('./components/Articulo.vue').default);
Vue.component('cliente', require('./components/Cliente.vue').default);
Vue.component('proveedor', require('./components/Proveedor.vue').default);
Vue.component('rol', require('./components/Rol.vue').default);
Vue.component('user', require('./components/User.vue').default);
Vue.component('ingreso', require('./components/Ingreso.vue').default);
Vue.component('venta', require('./components/Venta.vue').default);
Vue.component('dashboard', require('./components/Dashboard.vue').default);
Vue.component('consultaingreso', require('./components/consultaIngreso.vue').default);
Vue.component('consultaventa', require('./components/consultaVenta.vue').default);
Vue.component('notification', require('./components/Notification.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    // declara la propiedad data con la variable menu inicializada en cero
    data :{
        menu : 0,
        notifications : []
    },
    created() {
        let me = this;
        axios.post('notification/get').then(function(response){
            // console.log(response.data);
            me.notifications=response.data;
        }).catch(function(error){
            console.log(error);        
        });

        //en la var se almacena el id que se captura a traves de la etiqueta meta
        // se obtiene el id del usuario q trabaja con el sist
        var userId = $('meta[name="userId"]').attr('content');

        // escucha los evento de transmision de echo
        Echo.private('App.User.' + userId).notification((notification) => {
            // cada ves que alla una nueva venta o ompra se notificara
            // con unshif se agrega al inicio del arreglo    
            me.notifications.unshift(notification);
        });
    }
});
