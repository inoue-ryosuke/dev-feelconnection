import Vue from 'vue';
import router from './router.js';
import App from './App.vue';

require('./bootstrap');

const app = new Vue({
  el: '#app',
  router,
  render: h => h(App)
});