import Vue from 'vue';
import axios from 'axios';
import queryBlock from './view/query-block';
import queryExplain from './view/query-explain';
import Clipboard from 'v-clipboard'

window.Vue = Vue;
window.Axios = axios;
window.EventBus = new Vue();

Vue.use(Clipboard);
Vue.component('queryBlock', queryBlock);
Vue.component('queryExplain', queryExplain);

window.App = new Vue({
    el: '#app',
});




