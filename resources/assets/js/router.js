import Vue from 'vue';
import Router from 'vue-router';
import NewsList from './components/NewsList';
import NewsPage from './components/NewsPage';
import ProductsPage from './components/ProductsPage';

Vue.use(Router);

export default new Router({
  mode: 'history',
  routes: [
      { path: '/', component: NewsList, name: 'NewsList' },
      { path: '/news', component: NewsList, name: 'newsList' },
      { path: '/news/:id', component: NewsPage, name: 'newsPage' },
      { path: '/products', component: ProductsPage, name: 'productsPage' },
  ]
});
