<template>
    <div v-if="sidebar" class="btn-div">
        <button @click="ShowSideBar" class="btn p-0"> 
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-align-center"><line x1="18" y1="10" x2="6" y2="10"></line><line x1="21"
                y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="18" y1="18" x2="6" y2="18"></line>
            </svg>
        </button>
    </div>
    <div class="col-4 position-fixed">
        <div v-if="!sidebar" class="col-lg-5 col-md-2 bg-primary min-vh-100 p-0">
            <div class="sidebar-header d-flex justify-content-between align-items-center min-vh-25">
                <h6 class="sidebar-title w-50 text-white p-1">
                    Casino bar
                </h6>
                <button @click="CloseSideBar" class="btn p-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" 
                        stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="sidebar-body">
                <ul class="list-group w-100 mt-4">
                    <li class="list-group-item border-0 rounded-0 border-top">
                        <router-link class="nav-link" to="/">Order</router-link>
                    </li>
                    <li class="list-group-item rounded-0 border-0 border-top">
                        <router-link class="nav-link" to="/">Historico</router-link>
                    </li>
                    <li class="list-group-item rounded-0 border-0 border-top">
                        <router-link class="nav-link" to="/">Dashboard</router-link>
                    </li>
                    <li class="list-group-item rounded-0 border-0 border-top">
                        <router-link class="nav-link" :to="{ name: 'NewItem'}">New item</router-link>
                    </li>
                    <li class="list-group-item rounded-0 border-0 border-top">
                        <router-link class="nav-link" :to="{ name: 'NewMenuType'}">New Menu type</router-link>
                    </li>
                    <li class="list-group-item rounded-0 border-0 border-top">
                        <router-link class="nav-link" :to="{ name: 'Employe'}">New Employe</router-link>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: 'SideBarComponent',

    data() {
        return {
            sidebar: true
        }
    },

    methods: {
       CloseSideBar() {
        this.sidebar = !this.sidebar
       },

       ShowSideBar() {
        this.sidebar = !this.sidebar
       }
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
    border-radius: 0px;
}

.list-group-item {
    background-color: #0d6efd;
    color: #fff;
    transition: .1s ease;
}

.list-group-item:hover {
    background-color: #fff;
    color: #333;
}


</style>