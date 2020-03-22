import Vue from 'vue';
import Clipboard from 'v-clipboard'
import ViewIndex from './view/index.vue';
import router from './routes/web.js';


window.Vue = Vue;
window.EventBus = new Vue();

Vue.use(Clipboard);
Vue.component('app', ViewIndex);

window.App = new Vue({
    el: '#app',
    router
});




