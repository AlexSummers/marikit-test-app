import Vue from 'vue';
import Vuex from 'vuex';
import products from './modules/products';

Vue.use(Vuex);

const isStrict = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
    strict: true,
    modules: {
        products,
    },
});