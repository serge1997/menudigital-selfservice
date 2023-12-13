<template>
    <div v-if="sidebar" class="btn-div">
        <button @click="ShowSideBar" class="btn p-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-align-center"><line x1="18" y1="10" x2="6" y2="10"></line><line x1="21"
                y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="18" y1="18" x2="6" y2="18"></line>
            </svg>
        </button>
    </div>
    <div class="col-lg-2 col-md-12 position-fixed shadow bg-white" :class="{animate: isanimate}">
        <div v-if="!sidebar" class="col-lg-12 col-md-10 side-bar min-vh-100 p-0">
            <div class="sidebar-header d-flex justify-content-between align-items-center min-vh-25">
                <div class="sidebar-title d-flex flex-column p-1 w-50 text-dark">
                    <div class="user-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <p class="text-wrap">{{ username }}</p>
                </div>
                <button @click="CloseSideBar" class="btn p-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#e63958" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="sidebar-body d-flex flex-column justify-content-between">
                <ul class="list-group w-100 mt-4">
                    <li class="list-group-item rounded-0 border-0 border-top">
                        <router-link class="nav-link" :to="{name: 'OperadorPanel'}">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                                     stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23">
                                    </line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>
                            </span>
                            Caixa
                        </router-link>
                    </li>
                    <li class="list-group-item rounded-0 border-0 border-top">
                        <router-link class="nav-link" :to="{name: 'Garcom'}">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                                     stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </span>
                            Garcom
                        </router-link>
                    </li>
                    <li class="list-group-item border-0 rounded-0 border-top">
                        <router-link class="nav-link" :to="{name: 'BusinessInteligence'}">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                                </svg>
                            </span>
                            Business Inteligence
                        </router-link>
                    </li>
                    <li class="list-group-item rounded-0 border-0 border-top">
                        <router-link class="nav-link" :to="{ name: 'NewItem'}">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z">
                                    </path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line>
                                </svg>
                            </span>
                            New item
                        </router-link>
                    </li>
                    <li class="list-group-item rounded-0 border-0 border-top">
                        <router-link class="nav-link" :to="{ name: 'NewMenuType'}">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z">
                                    </path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line>
                                </svg>
                            </span>
                            New Menu type
                        </router-link>
                    </li>
                    <li class="list-group-item rounded-0 border-0 border-top">
                        <router-link class="nav-link" :to="{ name: 'Employe'}">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="CurrentColor"
                                     stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </span>
                            New Employe
                        </router-link>
                    </li>
                    <li class="list-group-item rounded-0 border-0 border-top">
                        <router-link class="nav-link" :to="{ name: 'Stock'}">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database">
                                    <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                                    <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                                </svg>
                            </span>
                            Stock
                        </router-link>
                    </li>
                    <li class="list-group-item rounded-0 border-0 border-top">
                        <router-link class="nav-link" :to="{ name: 'SettingPanel'}">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                </svg>
                            </span>
                            Setting
                        </router-link>
                    </li>
                </ul>
                <div class="p-2">
                    <button @click="LogOut" class="btn logout-btn px-4">Sair</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: 'SideBarComponent',

    data() {
        return {
            sidebar: true,
            username: null,
            isanimate: false
        }
    },

    methods: {
       CloseSideBar() {
        this.sidebar = !this.sidebar
       },

       ShowSideBar() {
        this.sidebar = !this.sidebar
        this.isanimate = true;
       },

        LogOut(){
            axios.post('/api/logout').then((response) => {
                localStorage.removeItem('token')
                this.$router.push('/');
            }).catch((error) => {
                console.log(error);
            })
        },
    },
    mounted(){
        console.log("side bar")
        window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`
        axios.get('/api/user').then((response) => {
            this.username = response.data.name
        }).catch((errors) => {
            console.log(errors)
        })
    }
}
</script>

<style scoped>
.animate {
    transition: .5s ease-in;
    opacity: 0.9;
}

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
