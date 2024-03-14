import { createRouter, createWebHistory } from "vue-router";

import Home from '../components/Pages/Home.vue';
import Menu from '../components/Pages/Menu.vue';
import NewItem from '../components/Pages/Dashboard/NewItem.vue';
import ItemCart from '../components/Pages/ItemCart.vue';
import CustomerOrderList from '../components/Pages/CustomerOrderList.vue';
import OperadorPanel from '../components/Pages/Dashboard/OperadorPanel.vue';
import NewMenuType from '../components/Pages/Dashboard/NewMenuType.vue';
import CustomerBill from '../components/Pages/Dashboard/CustomerBill.vue';
import Employe from '../components/Pages/Dashboard/Employe.vue';
import Login from '../components/Pages/Login.vue';
import Garcom from '../components/Pages/Garcom.vue';
import BusinessInteligence from '../components/Pages/Dashboard/BusinessInteligence/BiIndex.vue';
import SettingPanel from '../components/Pages/Dashboard/SettingPanel.vue';
import Stock from '../components/Pages/Dashboard/Stock.vue';
import PublicMenu from '../components/Pages/PublicMenu.vue'
import PurchaseRequisition from "../components/Pages/Dashboard/Purchase/PurchaseRequisition.vue";
import EmployeePlanning from "../components/Pages/Dashboard/EmployeePlanning.vue";
import Reservation from '../components/Pages/Dashboard/Reservation/Reservation.vue'
import ConsultDelivery from '../components/Pages/Dashboard/ConsultDelivery.vue';


var managerAccess;
var stockAccess;
var administrativeAccess;
localStorage.getItem('managerAccess') ? managerAccess = localStorage.getItem('managerAccess').split(','): null;
localStorage.getItem('stockAccess') ? stockAccess = localStorage.getItem('stockAccess').split(','): null;
localStorage.getItem('administrativeAccess') ? administrativeAccess = localStorage.getItem('administrativeAccess').split(','): null;
const routes = [

    {
        path: '/',
        name: 'Login',
        component: Login,
        meta: {guest: true}

    },

    {
        path: '/home',
        name: 'Home',
        component: Home,
        meta: {requiresAuth: true},
    },

    {
        path: '/menu',
        name: 'Menu',
        component: Menu,
        meta: {requiresAuth: true}
    },

    {
        path: '/new/item',
        name: 'NewItem',
        component: NewItem,
        beforeEnter: (to, from, next) => {
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`
            axios.get('/api/user').then((response) => {
                if(managerAccess){
                    managerAccess.includes(`${response.data.position_id}`) ? next() : next('/home');
                }
            })
        },
        meta: {requiresAuth: true}
    },

    {
        path: '/item/cart/:id',
        name: 'ItemCart',
        component: ItemCart
    },

    {
        path: '/cart',
        name: 'Cart',
        component: CustomerOrderList,
        meta: {requiresAuth: true}
    },

    {
        path: '/dashboard/operador',
        name: 'OperadorPanel',
        component: OperadorPanel,
        beforeEnter: (to, from, next) => {
             window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`
             axios.get('/api/user').then((response) => {
                if (administrativeAccess){
                    administrativeAccess.includes(`${response.data.position_id}`) ? next() : next('/home');
                }
            })
        },
        meta: {requiresAuth: true}
    },

    {
        path: '/dashboard/bi',
        name: 'BusinessInteligence',
        component: BusinessInteligence,
        beforeEnter: (to, from, next) => {
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`
            axios.get('/api/user').then((response) => {
                if (managerAccess){
                    managerAccess.includes(`${response.data.position_id}`) ? next() : next('/home');
                }
            })
        },
        meta: {requiresAuth: true}
    },

    {
        path:'/new/type',
        name: 'NewMenuType',
        component: NewMenuType,
        beforeEnter: (to, from, next) => {
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`
            axios.get('/api/user').then((response) => {
                if (managerAccess){
                    managerAccess.includes(`${response.data.position_id}`) ? next() : next('/home');
                }
            })
        },
        meta: {requiresAuth: true}
    },

    {
        path: '/bill/:id',
        name: 'Bill',
        component: CustomerBill
    },

    {
        path: '/new/employe',
        name: 'Employe',
        component: Employe,
        beforeEnter: (to, from, next) => {
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`
            axios.get('/api/user').then((response) => {
                if (managerAccess){
                    managerAccess.includes(`${response.data.position_id}`) ? next() : next('/home');
                }

            })
        },
        meta: {requiresAuth: true}
    },


    {
        path: '/dashboard/setting',
        name: 'SettingPanel',
        component: SettingPanel,
        beforeEnter: (to, from, next) => {
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`
            axios.get('/api/user').then((response) => {
                if (managerAccess){
                   managerAccess.includes(`${response.data.position_id}`) ? next() : next('/home');
                }
            })
        },
        meta: {requiresAuth: true}
    },


    {
        path: '/dashboard/garcom',
        name: 'Garcom',
        component: Garcom,
        meta: {requiresAuth: true}

    },

    {
        path: '/dashboard/stock',
        name: 'Stock',
        component: Stock,
        beforeEnter: (to, from, next) => {
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`
            axios.get('/api/user').then((response) => {
                if (stockAccess){
                    stockAccess.includes(`${response.data.position_id}`) ? next() : next('/home');
                }
            })
        },
        meta: {requiresAuth: true}
    },

    {
        path: '/public-menu',
        name: 'PublicMenu',
        component:PublicMenu
    },
    {
        path: '/dashboard/purchase/',
        name: 'PurchaseRequisition',
        component: PurchaseRequisition,
        beforeEnter: (to, from, next) => {
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`
            axios.get('/api/user').then((response) => {
               if (administrativeAccess){
                   administrativeAccess.includes(`${response.data.position_id}`) ? next() : next('/home');
               }
           })
       },
        meta: {requiresAuth: true}
    },
    {
        path: '/dashboard/planning',
        name: 'EmployeePlanning',
        component: EmployeePlanning,
        meta: {requiresAuth: true}
    },
    {
        path: '/dashboard/reservation',
        name: 'Reservation',
        component: Reservation,
        meta: {requiresAuth: true}
    },
    {
        path: '/dashboard/consult-delivery',
        name: 'ConsultDelivery',
        beforeEnter: (to, from, next) => {
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`
            axios.get('/api/user').then((response) => {
                if (stockAccess){
                    stockAccess.includes(`${response.data.position_id}`) ? next() : next('/home');
                }
            })
        },
        component: ConsultDelivery,
        meta: {requiresAuth: true}
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
