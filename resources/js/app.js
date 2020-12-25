import router from './router';
import common from './common';
import ViewUI from 'view-design';
import 'view-design/dist/styles/iview.css';


require('./bootstrap');

window.Vue = require('vue');

Vue.use(ViewUI);

Vue.mixin(common)

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

Vue.component('main-app', require('./components/mainapp.vue').default);

const app = new Vue({
    el: '#app',
    router,
});

