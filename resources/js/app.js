import './bootstrap';
import { createApp } from 'vue';
import VueAxios from 'vue-axios';
import axios from 'axios';
import Toaster from '@meforma/vue-toaster';
import router from './router/index';
import App from './App.vue';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import PrimeVue from "primevue/config";
import 'primevue/resources/themes/saga-blue/theme.css';
import 'primeicons/primeicons.css'
import i18next from "i18next";
import I18NextVue from "i18next-vue";

console.log("Hello world from app.js")

function loggedIn() {
    return localStorage.getItem('token')
}

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
const locales = {
    pt: {
        operator: {
            title: 'Espaço operador',
            table_box_title: 'Ocupação mesa na sala',
            table: 'Mesa',
            toolbar: {
                one: 'novo pedido',
                two: 'inventario',
                three: 'report',
                four: 'historico pedidos'
            },
            dataTable: {
                two: 'Nome',
                three: 'Mesa',
                four: 'Valor Total',
                six: 'Ação'
            }
        },
        //invetory components
        inventory: {
            title: 'Inventario dos produtos (em tempo real)',
            filter_label: 'Filtro por departamento ',
            dataTable: {
                one: 'Produto',
                two: 'Saldo inicial',
                three: 'Saída',
                four: 'Saldo final'
            }
        }
    },
    fr: {
        //operator page
        operator: {
            title: 'Espace operateur',
            table_box_title: 'Occupation de tables en salle',
            table: 'Table',
            toolbar: {
                one: 'nouvelle commande',
                two: 'inventaire',
                three: 'rapport',
                four: 'historiques'
            },
            dataTable: {
                two: 'Nom',
                three: 'Table',
                four: 'Total',
                six: 'Action'
            }
        },
        //invetory components
        inventory: {
            title: 'Inventaire des produits (en temps réel)',
            filter_label: 'Filtre par departements',
            dataTable: {
                one: 'Produit',
                two: 'Quantité initial',
                three: 'Sortie',
                four: 'Quantité final'
            }
        }
    }
}

i18next.init({
    lng: 'fr',
    fallback: 'pt',
    witeList: ['pt', 'fr'],
    resources: {
        pt: {translation: locales.pt },
        fr: {translation: locales.fr}
    }
});

createApp(App).use(router).use(VueAxios, axios).use(Toaster, {
    position: 'top'
}).use(VueSweetalert2).use(PrimeVue).use(I18NextVue, {i18next}).mount('#app')
