<template>
    <div class="container-fuid d-flex justify-content-between">
        <div class="position-fixed">
            <SideBarComponent class="text-center"></SideBarComponent>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-8 m-auto">
                    <form @submit.prevent="createUser" class="w-100 p-4">
                        <div class="row">
                            <div class="form-header p-2 text-capitalize shadow-lg rounded-3 text-white w-100">
                                <h6>new collaborator</h6>
                                <p>Save new collaborator</p>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="d-flex flex-column gap-2">
                                    <label for="user-name">Collaborator name</label>
                                    <InputText :class="invalid" type="text" id="user-name" v-model="user.name" aria-describedby="user name" placeholder="collaborator name"/>
                                    <small class="text-danger" v-if="errMsg" v-for="errname in errMsg.name" id="user-name-err"  v-text="errname"></small>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="d-flex flex-column gap-2">
                                    <label for="user-tel">User contact</label>
                                    <InputText :class="invalid" type="text" id="user-name" v-model="user.tel" aria-describedby="user name" placeholder="collaborator contact"/>
                                    <small class="text-danger" v-if="errMsg" v-for="errtel in errMsg.tel" id="user-tel-err"  v-text="errtel"></small>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="d-flex flex-column gap-2">
                                    <label for="user-password">Collaborator e-mail or username</label>
                                    <InputText :class="invalid" type="text" id="user-email" v-model="user.email" aria-describedby="user email" placeholder="collaborator email"/>
                                    <small class="text-danger" v-if="errMsg" v-for="erremail in errMsg.email" id="user-email-err"  v-text="erremail"></small>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="d-flex flex-column gap-2">
                                    <label for="user-password">Collaborator password</label>
                                    <InputText :class="invalid" type="password" id="user-name" v-model="user.password" aria-describedby="user password" placeholder="user password"/>
                                    <small class="text-danger" v-if="errMsg" v-for="errpassword in errMsg.password" id="user-password-err"  v-text="errpassword"></small>
                                </div>
                            </div>
                            <div class="col-mt-12 mt-3">
                                <Dropdown v-model="user.group_id" option-value="id" :options="groups" optionLabel="groupe" placeholder="Select user function" class="w-100 md:w-14rem" />
                            </div>
                        </div>
                        <div class="mt-3">
                            <Button label="Save" type="submit" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SideBarComponent from './SideBarComponent.vue'
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import Button from "primevue/button";
export default {
    name: 'Employe',

    components: {
        SideBarComponent,
        InputText,
        Dropdown,
        Button
    },

    data(){
        return {
            groups: null,
            user: {
                name: null,
                tel: null,
                password: null,
                group_id: null,
                email: null
            },
            errMsg: null,
            invalid: null
        }
    },

    methods: {
        getUsergroup() {
            axios.get('/api/group').then((response) => {
                this.groups = response.data
            }).catch((error) => {
                console.log(error)
            })
        },

        createUser() {
            axios.post('/api/create/user', this.user).then((response) => {
                if (response.data.status === 200) {
                    this.$toast.success(response.data.msg);
                }else{
                    this.$toast.error(response.data.msg);
                }
                this.user.email = ""
                this.user.name = ""
                this.user.tel = ""
                this.user.group_id = ""
                this.user.password = ""
            }).catch((errors) => {
                console.log(errors.response.data.errors);
                this.errMsg = errors.response.data.errors;
                this.invalid = 'p-invalid'
            })
        }
    },

    mounted(){
        window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`
        this.getUsergroup();

    }
}

</script>
