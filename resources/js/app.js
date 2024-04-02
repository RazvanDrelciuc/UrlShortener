import './bootstrap.js';
import { createApp } from 'vue';
import App from './components/App.vue';
import UrlShortenForm from './components/UrlShortenForm.vue';
import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    { path: '/', component: UrlShortenForm },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

const app = createApp(App);
app.component('url-shorten-form', UrlShortenForm);
app.use(router);
app.mount('#app');
