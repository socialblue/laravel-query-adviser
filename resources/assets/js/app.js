import Vue from 'vue';
import Clipboard from 'v-clipboard'
import ViewLayout from './view/layout.vue';
import router from './routes/web.js';


window.Vue = Vue;
window.EventBus = new Vue();

Vue.use(Clipboard);
Vue.component('ViewLayout', ViewLayout);

window.App = new Vue({
    el: '#app',
    router
});




