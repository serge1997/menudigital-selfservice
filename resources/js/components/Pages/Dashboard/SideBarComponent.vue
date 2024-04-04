<template>
    <div class="w-100 d-flex">
        <div class="col-md-8">
            <Sidebar style="overflow: scroll;" v-model:visible="visibleSidebar">
                <template #container="{ closeCallback }">
                    <div class="d-flex flex-column align-content-between h-full">
                        <div class="d-flex align-items-center justify-content-between px-4 flex-shrink-0">
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
                                        {{  $t('sidebarmenu.one') }}
                                    </router-link>
                                </li>
                                <li class="list-group-item rounded-0 border-0">
                                    <router-link class="nav-link" :to="{name: 'Garcom'}">
                                        <span class="pi pi-user"></span>
                                        {{  $t('sidebarmenu.two') }}
                                    </router-link>
                                </li>
                                <li v-if="managerAccess.includes(`${user.position_id}`)" class="list-group-item border-0 rounded-0">
                                    <router-link class="nav-link" :to="{name: 'BusinessInteligence'}">
                                    <span class="pi pi-chart-bar"></span>
                                    {{  $t('sidebarmenu.three') }}
                                    </router-link>
                                </li>
                                <li class="list-group-item rounded-0 border-0">
                                <router-link class="nav-link" :to="{ name: 'Reservation'}">
                                    <span class="pi pi-calendar"></span>
                                    {{  $t('sidebarmenu.four') }}
                                </router-link>
                                </li>
                                <li v-if="managerAccess.includes(`${user.position_id}`)" class="list-group-item rounded-0 border-0">
                                    <router-link class="nav-link" :to="{ name: 'NewItem'}">
                                        <span class="pi pi-plus"></span>
                                        {{  $t('sidebarmenu.five') }}
                                    </router-link>
                                </li>
                                <li v-if="managerAccess.includes(`${user.position_id}`)" class="list-group-item rounded-0 border-0">
                                    <router-link class="nav-link" :to="{ name: 'NewMenuType'}">
                                    <span class="pi pi-plus"></span>
                                    {{  $t('sidebarmenu.six') }}
                                    </router-link>
                                </li>
                                <li v-if="managerAccess.includes(`${user.position_id}`)" class="list-group-item rounded-0 border-0">
                                    <router-link class="nav-link" :to="{ name: 'Employe'}">
                                    <span class="pi pi-user-plus"></span>
                                    {{  $t('sidebarmenu.seven') }}
                                    </router-link>
                                </li>
                                <li class="list-group-item rounded-0 border-0">
                                <router-link class="nav-link" :to="{ name: 'EmployeePlanning'}">
                                    <span class="pi pi-calendar"></span>
                                    {{  $t('sidebarmenu.eight') }}
                                </router-link>
                                </li>
                                <li v-if="stockAccess.includes(`${user.position_id}`)" class="list-group-item rounded-0 border-0">
                                    <router-link class="nav-link" :to="{ name: 'Stock'}">
                                    <span class="pi pi-database"></span>
                                    {{  $t('sidebarmenu.nine') }}
                                    </router-link>
                                </li>
                                <li v-if="stockAccess.includes(`${user.position_id}`)" class="list-group-item rounded-0 border-0">
                                    <router-link class="nav-link" :to="{ name: 'PurchaseRequisition'}">
                                        <span class="pi pi-cart-plus"></span>
                                        {{  $t('sidebarmenu.ten') }}
                                    </router-link>
                                </li>
                                <li v-if="stockAccess.includes(`${user.position_id}`)" class="list-group-item rounded-0 border-0">
                                    <router-link class="nav-link" :to="{ name: 'ConsultDelivery'}">
                                        <span class="pi pi-truck"></span>
                                        {{  $t('sidebarmenu.eleven') }}
                                    </router-link>
                                </li>
                                <li v-if="managerAccess.includes(`${user.position_id}`)" class="list-group-item rounded-0 border-0">
                                    <router-link class="nav-link" :to="{ name: 'SettingPanel'}">
                                    <span class="pi pi-cog"></span>
                                    {{  $t('sidebarmenu.twelve') }}
                                    </router-link>
                                </li>
                            </ul>
                            <div class="d-flex flex-column p-4">
                                <Button :label="username"/>
                                <button @click="LogOut" class="btn logout-btn px-2 mt-4">{{ $t('sidebarmenu.logout') }}</button>
                            </div>
                            <div class="d-flex flex-column p-4">
                                <label for="lang">{{ $t('sidebarmenu.lang_label')}}</label>
                                <div v-for="lng in lang" class="dropdown">
                                    <a v-if="lng.value == currentLang" class="btn dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                        <img style="width: 35px;" class="img-thumbnail" :src="lng.flag" alt="">
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <li v-for="lng in lang" @click="setSysLanguage(lng.value)" class="dropdown-item d-flex align-items-center gap-2">
                                            <span><img style="width: 35px;" class="img-thumbnail" :src="lng.flag" alt=""></span>
                                            <span>{{ lng.label }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </Sidebar>
            <Button icon="pi pi-bars" @click="visibleSidebar = true" />
        </div>
        <div class="col-lg-1 col-md-2 m-auto">
            <div class="card shadow-sm">
               <Tag icon="pi pi-clock" :value="time" severity="primary" />
            </div>
        </div>
    </div>
</template>

<script>
import { getAuth } from "../auth.js";
import Button from 'primevue/button';
import Sidebar from "primevue/sidebar";
import Avatar from "primevue/avatar";
import Tag from 'primevue/tag';
import Dropdown from "primevue/dropdown";
import { computed } from "vue";

export default {
    name: 'SideBarComponent',

    components:{
        Button,
        Sidebar,
        Avatar,
        Tag,
        Dropdown
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
            time: null,
            currentLang: localStorage.getItem('lang') ?? 'pt',
            lang: [
                {value: 'pt', label: 'Portugues', flag: "/img/brazil.png"},
                {value: 'fr', label: 'Français', flag: "/img/france.png"}
            ]
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
                localStorage.removeItem('token');
                localStorage.removeItem('stockAccess');
                localStorage.removeItem('managerAccess');
                localStorage.removeItem('administrativeAccess');
                localStorage.removeItem('table');
                this.$router.push('/');
            }).catch((error) => {
                console.log(error);
            })
        },
        setSysLanguage(lang){
            axios.put('/api/language/' + lang).then((response) => {
                if (localStorage.getItem('lang')){
                    localStorage.removeItem('lang')
                    localStorage.setItem('lang', lang)
                }
                localStorage.setItem('lang', lang)
                location.reload()
                return this.$toast.success(response.data)
            })
            .catch(errors => console.log(errors));
        }
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
        setInterval(() => {
            let dateTime = new Date().toLocaleDateString('pt-BR', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            })
            this.time = dateTime.substring(12, 20)
        }, 1000)

        if ( localStorage.getItem('lang') ) {
            let lang = localStorage.getItem('lang')
            axios.put('/api/language/' + lang).then((response) => {
                console.log('language setted');
            })
        }

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

*{
    scrollbar-width: thin;
    scrollbar-color: #9a1010 #ff00;
}

::-webkit-scrollbar-track {
    scrollbar-width: thin;
    background-color: #fff;
}

*::-webkit-scrollbar {
    scrollbar-width: thin;
    width: 4px;
  }

  *::-webkit-scrollbar-thumb {
    background-color: #333;
    border-radius: 10px;
    border: 3px solid #ffffff;
  }

</style>
