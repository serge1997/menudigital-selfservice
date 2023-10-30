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
import Garcom from '../components/Pages/Dashboard/Garcom.vue';
import BusinessInteligence from '../components/Pages/Dashboard/BusinessInteligence.vue'
import SettingPanel from '../components/Pages/Dashboard/SettingPanel.vue';
import { defaults } from "lodash";




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

                if (response.data.group_id != 1 && response.data.group_id != 2){
                    next('/home')
                }else{
                    next()
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

                if (response.data.group_id != 1 && response.data.group_id != 2){
                    next('/home')
                }else{
                    next()
                }
            })
        },
        meta: {requiresAuth: true}
    },

    {
        path:'/new/type',
        name: 'NewMenuType',
        component: NewMenuType,
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
                if (response.data.group_id != 1) {
                    next('/home')
                }else{
                    next()
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
                if (response.data.group_id != 1) {
                    next('/home')
                }else{
                    next()
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

    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router