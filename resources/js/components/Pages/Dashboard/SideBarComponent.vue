<template>
    <Sidebar v-model:visible="visibleSidebar">
        <template #container="{ closeCallback }">
            <div class="d-flex flex-column align-content-between h-full">
                <div class="d-flex align-items-center justify-content-between px-4 pt-3 flex-shrink-0">
                    <div v-for="rest in restaurant">
                        <img class="w-25 type-btn" :src="'/img/logo/'+ rest.res_logo" alt="">
                    </div>
                    <span>
                        <Button text type="button" @click="closeCallback" icon="pi pi-times" rounded outlined class="h-2rem w-2rem"></Button>
                    </span>
                </div>
                <div class="w-100 d-flex flex-column justify-content-between">
                    <ul class="list-group w-100 mt-4">
                        <li v-if="administrativeAccess.includes(`${user.position_id}`)" class="list-group-item rounded-0 border-0">
                            <router-link class="nav-link" :to="{name: 'OperadorPanel'}">
                            <span class="pi pi-dollar"></span>
                                Caixa
                            </router-link>
                        </li>
                        <li class="list-group-item rounded-0 border-0">
                            <router-link class="nav-link" :to="{name: 'Garcom'}">
                                <span class="pi pi-user"></span>
                                Waiter
                            </router-link>
                        </li>
                        <li v-if="managerAccess.includes(`${user.position_id}`)" class="list-group-item border-0 rounded-0">
                            <router-link class="nav-link" :to="{name: 'BusinessInteligence'}">
                            <span class="pi pi-chart-bar"></span>
                                Business Inteligence
                            </router-link>
                        </li>
                        <li class="list-group-item rounded-0 border-0">
                          <router-link class="nav-link" :to="{ name: 'Reservation'}">
                            <span class="pi pi-calendar"></span>
                            Gestão de reservação
                          </router-link>
                        </li>
                        <li v-if="managerAccess.includes(`${user.position_id}`)" class="list-group-item rounded-0 border-0">
                            <router-link class="nav-link" :to="{ name: 'NewItem'}">
                                <span class="pi pi-plus"></span>
                                New item
                            </router-link>
                        </li>
                        <li v-if="managerAccess.includes(`${user.position_id}`)" class="list-group-item rounded-0 border-0">
                            <router-link class="nav-link" :to="{ name: 'NewMenuType'}">
                            <span class="pi pi-plus"></span>
                                New Menu type
                            </router-link>
                        </li>
                        <li v-if="managerAccess.includes(`${user.position_id}`)" class="list-group-item rounded-0 border-0">
                            <router-link class="nav-link" :to="{ name: 'Employe'}">
                            <span class="pi pi-user-plus"></span>
                                Colaborador
                            </router-link>
                        </li>
                        <li class="list-group-item rounded-0 border-0">
                          <router-link class="nav-link" :to="{ name: 'EmployeePlanning'}">
                            <span class="pi pi-calendar"></span>
                            Escala
                          </router-link>
                        </li>
                        <li v-if="stockAccess.includes(`${user.position_id}`)" class="list-group-item rounded-0 border-0 border-top">
                            <router-link class="nav-link" :to="{ name: 'Stock'}">
                            <span class="pi pi-database"></span>
                                Stock
                            </router-link>
                        </li>
                        <li v-if="stockAccess.includes(`${user.position_id}`)" class="list-group-item rounded-0 border-0 border-top">
                            <router-link class="nav-link" :to="{ name: 'PurchaseRequisition'}">
                                <span class="pi pi-cart-plus"></span>
                                Compras
                            </router-link>
                        </li>
                        <li v-if="managerAccess.includes(`${user.position_id}`)" class="list-group-item rounded-0 border-0">
                            <router-link class="nav-link" :to="{ name: 'SettingPanel'}">
                            <span class="pi pi-cog"></span>
                                Setting
                            </router-link>
                        </li>
                    </ul>
                    <div class="d-flex flex-column p-4">
                        <Button :label="username"/>
                        <button @click="LogOut" class="btn logout-btn px-2 mt-4">Log out</button>
                    </div>
                </div>
            </div>
        </template>
    </Sidebar>
    <Button icon="pi pi-bars" @click="visibleSidebar = true" />
</template>

<script>
import { getAuth } from "../auth.js";
import Button from 'primevue/button';
import Sidebar from "primevue/sidebar";
import Avatar from "primevue/avatar";
import { computed } from "vue";

export default {
    name: 'SideBarComponent',

    components:{
        Button,
        Sidebar,
        Avatar
    },

    data() {
        return {
            sidebar: true,
            user: {
                position_id: null
            },
            username: null,
            visibleSidebar: false,
            restaurant: null,
            managerAccess: localStorage.getItem('managerAccess').split(','),
            stockAccess: localStorage.getItem('stockAccess').split(','),
            administrativeAccess: localStorage.getItem('administrativeAccess').split(','),
            stock_show: false,
            manager_show: false,
            administrative_show: false,
            is_toShow: null,

        }
    },
    provide(){
        return{
            position_id: computed(() => this.user.position_id)
        }
    },
    methods: {
       CloseSideBar() {
        this.sidebar = !this.sidebar
       },

       ShowSideBar() {
        this.sidebar = !this.sidebar
       },

        LogOut(){
            axios.post('/api/logout').then((response) => {
                localStorage.removeItem('token')
                localStorage.removeItem('stockAccess')
                localStorage.removeItem('managerAccess')
                localStorage.removeItem('administrativeAccess')
                this.$router.push('/');
            }).catch((error) => {
                console.log(error);
            })
        },
    },
    mounted(){
        console.log(this.administrativeAccess)
        window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
        getAuth().then(result => {
            this.username = result.name;
            this.user.position_id = result.position_id;

            this.managerAccess.forEach((element) => {
                element == this.user.position_id ? this.manager_show = true : null
            });
            this.stockAccess.forEach(el => {
                el == this.user.position_id ? this.stock_show = true : null
            })

            this.administrativeAccess.forEach(element => {
                element == this.user.position_id ? this.administrative_show = true : null
            })

        });
        axios.get('/api/rest-info').then((response) => {
            console.log(response.data)
            this.restaurant = response.data
        }).catch((errors) => {
            console.log(errors)
        })

    },

    created(){

    }
}
</script>

<style scoped>


.btn-icon:hover {
    color: red;
}
.sidebar-body {
    min-height: 85vh;
    display: flex;
    align-items: start;
    align-content: center;
    justify-content: center;
}

.list-group-item {
    transition: .1s ease;
    border-bottom: 1px solid #e63958;
}

.list-group-item:hover {
    background-color: #e63958;
    color: #fff;
}
.logout-btn {
    border: 2px solid #e63958;
    transition: all ease-in .4s;
}

.logout-btn:hover{
    background-color: #e63958;
    color: #fff;
}


</style>
