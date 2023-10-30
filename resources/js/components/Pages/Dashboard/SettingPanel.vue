<template>
    <div class="container">
        <div class="d-flex p-2 mt-3">
            <span class="p-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>
            </span>
            <h3 class="px-2">Setting</h3>
        </div>
        <div class="row p-4 mt-4">
            <div class="col-12 d-flex">
                <div class="col-6">
                    <div class="header d-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <h6 class="px-2">Employe management</h6>
                    </div>
                    <small>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore 
                        magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                    </small>
                </div>
                <div class="col-6 d-flex justify-content-center align-items-center">
                    <button class="text-primary rounded-0 alert alert-primary p-2">Manage</button>
                </div>
            </div>
            <div class="col-12 d-flex mt-5">
                <div class="col-6">
                    <div class="header d-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" 
                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                        </svg>
                        <h6 class="px-2">Ajuste menu</h6>
                    </div>
                    <small>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore 
                        magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                    </small>
                </div>
                <div class="col-6 d-flex justify-content-center align-items-center">
                    <button class="text-primary rounded-0 alert alert-primary p-2" data-bs-toggle="modal" data-bs-target="#ModelItemSetting">Menu Alteration</button>
                </div>
            </div>
            <div class="col-12 d-flex mt-5">
                <div class="col-6">
                    <div class="header d-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" 
                            stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" 
                            class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                            </path><polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <h6 class="px-2">Alert e-mail configuration</h6>
                    </div>
                    
                    <small>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore 
                        magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                    </small>
                    <div class="col-6 d-flex justify-content-start mt-2 align-items-center">
                        <button class="text-primary rounded-0 alert alert-primary p-2">E-mail configuration</button>
                    </div>
                </div>
            </div>
        </div>
        <SettingMenuComponent />
    </div>
</template>

<script>
import SettingMenuComponent from '../../SettingMenuComponent.vue';

export default{
    name: 'SettingPanel',

    components: {
        SettingMenuComponent
    },
    
    data(){
        return{
            MenuItems: null
        }
    },

    mounted() {
        axios.get('/api/menu/items').then((response) => {
            this.MenuItems = response.data.items
            console.log(response.data.items)
            this.load = false
        }).catch((errors) => {
            console.log(errors)
        })
    },
}

</script>