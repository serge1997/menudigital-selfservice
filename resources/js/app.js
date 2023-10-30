import './bootstrap';
import { createApp } from 'vue';
import VueAxios from 'vue-axios';
import axios from 'axios';
import Toaster from '@meforma/vue-toaster';
import router from './router/index';
import App from './App.vue';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

console.log("Hello world from app.js")

function loggedIn() {
    return localStorage.getItem('token')
};

router.beforeEach((to, from, next) => {
    if(to.matched.some(record => record.meta.requiresAuth)) {
        if (!loggedIn()) {
            next({
                path: '/',
                query: { redirect: to.fullPath },
            })
        }else {
            next()
        }
    }else if (to.matched.some(record => record.meta.guest)) {
        if (loggedIn()) {
            next({
                path: '/home',
                query: { redirect: to.fullPath },
            })
        }else {
            next()
        }
    }else{
        next()
    }
})

//localStorage.removeItem('token')


createApp(App).use(router).use(VueAxios, axios).use(Toaster, {
    position: 'top'
}).use(VueSweetalert2).mount('#app')
