import App from './components/App.vue';
import Vue from 'vue';
import router from './router';
import Vuetify from 'vuetify';
import store from './store/index';

Vue.use(Vuetify);

export default new Vue({
    router,
    store,
    render: h => h(App)
});
