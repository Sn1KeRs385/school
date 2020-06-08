/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import VueNotifications from 'vue-notifications'
import miniToastr from 'mini-toastr' // https://github.com/se-panfilov/mini-toastr

import App from './App.vue';
import router from './router';

import FormField from "./components/Form/FormField";

Vue.component('App', require('./App.vue'));
Vue.component('FormField', FormField)
Vue.use(BootstrapVue);

  miniToastr.init({
    types: {
      success: 'success',
      error: 'error',
      info: 'info',
      warn: 'warn'
    }
  })

function toast({
  title,
  message,
  type,
  timeout,
  cb
}) {
  return miniToastr[type](message, title, timeout, cb)
}

const options = {
  success: toast,
  error: toast,
  info: toast,
  warn: toast
}

Vue.use(VueNotifications, options);

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  template: '<App/>',
  components: {
    App
  }
})