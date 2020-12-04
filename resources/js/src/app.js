import Vue from 'vue';
import App from './App.vue';
import VueRouter from "vue-router";
import routes from "./routes";
import store from "./store";
import vueToastr2 from "vue-toastr-2";
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import SemanticUIVue from "semantic-ui-vue";
import "./plugins/global"
import "vue-toastr-2/dist/vue-toastr-2.min.css"
import 'font-awesome/css/font-awesome.css';

import 'material-design-icons-iconfont/dist/material-design-icons.css';
import './styles/global.css';
import 'semantic-ui-css/semantic.min.css';
import './plugins/script'


import axios from 'axios'
import VueAxios from 'vue-axios'
Vue.use(VueAxios, axios);



import { setupComponents } from './config/setup-components';
import Multiselect from "vue-multiselect";
import swatches from 'vue-swatches';
import "vue-swatches/dist/vue-swatches.css"

Vue.use(VueRouter);
Vue.component('swatches', swatches);
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
setupComponents(Vue);


const moment = require('moment');
require('moment/locale/fr');
Vue.use(require('vue-moment'),{
    moment
});

window.toastr = require('toastr');
Vue.use(vueToastr2, {
  defaultTimeout: 5000,
  defaultProgressBar: true,
  defaultProgressBarValue: 0,
  defaultPosition: "toast-top-center",
  defaultCloseOnHover: false,
  //defaultStyle: { "background-color": "red" },
  defaultClassNames: ["animated", "zoomInUp"]
});
Vue.component('multiselect', Multiselect);
Vue.config.productionTip = false;
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.use(SemanticUIVue);
new Vue({
  el: '#app',
  router: new VueRouter(routes),
  store,
  components: { App },
  template: '<App/>',
});


