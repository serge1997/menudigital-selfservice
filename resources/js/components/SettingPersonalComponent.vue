<template>
    <div>
        <div class="modal fade" id="ModelPersonalSetting" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="w-75 form-header p-2 text-capitalize rounded-3 text-white m-auto">
                            <h6>Personal management</h6>
                            <p>personal hr</p>
                        </div>
                        <div class="w-100 d-flex justify-content-center mt-4">
                            <Button @click="$router.push({ name: 'Employe'})" label="New" icon="pi pi-plus" data-bs-dismiss="modal" />
                        </div>
                        <div v-if="ShowForm" class="w-100 edit-form mt-4">
                            <div class="col-md-4 m-auto d-flex justify-content-lg-center">
                                <div v-if="load" class="spinner-grow m-auto" style="width: 3rem; height: 3rem;" role="status">
                                    <span class="visually-hidden"></span>
                                </div>
                            </div>
                            <div class="form w-100">
                                <form class="d-flex flex-column justify-content-center w-100 p-2" v-for="user in userForEdit">
                                    <div class="w-75 d-flex m-auto">
                                        <input type="hidden" :value="user.id" id="user-id">
                                        <div class="w-50 d-flex flex-column">
                                            <InputText type="text" class="w-100" :value="user.name" id="user-name"/>
                                            <small class="text-danger" v-if="errMsg" v-for="msg in errMsg.user_name" v-text="msg"></small>
                                        </div>
                                        <div class="px-2"></div>
                                        <div class="w-50 d-flex flex-column">
                                            <InputText type="text" class="w-100" :value="user.tel" id="user-tel"/>
                                            <small class="text-danger" v-if="errMsg" v-for="msg in errMsg.user_tel" v-text="msg"></small>
                                        </div>
                                    </div>
                                    <div class="form-group w-100 d-flex align-items-center flex-column mt-2">
                                        <InputText type="text" class="w-75 m-auto" :value="user.email" id="user-email"/>
                                        <small class="w-75 m-auto text-danger" v-if="errMsg" v-for="msg in errMsg.user_email" v-text="msg"></small>
                                    </div>
                                    <div class="w-75 m-auto d-flex justify-content-center flex-wrap p-2 mt-2">
                                        <div v-for="roles in user_roles" class="form-check p-2 mt-1">
                                            <button v-if="roles.role_id" @click.prevent="updateUserRoles(roles.id, roles.desc = true)" class="btn check">
                                                {{ roles.role_name}}
                                            </button>
                                            <button v-else @click.prevent="updateUserRoles(roles.id, roles.desc = false)" class="btn btn-roles">
                                                {{ roles.role_name}}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="">
                            <table class="table m-auto table-striped">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>E-mail</th>
                                        <th>Tel</th>
                                        <th>Status</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="employe in employes">
                                        <td class="p-3">{{ employe.id }}</td>
                                        <td class="text-uppercase p-3">{{ employe.name }}</td>
                                        <td class="p-3">{{ employe.email }}</td>
                                        <td class="p-3">{{ employe.tel }}</td>
                                        <td class="text-uppercase p-3">{{ employe.position }}</td>
                                        <td class="p-3">
                                            <button class="btn" @click="ShowEmployeEditForm(employe.id)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                                </svg>
                                            </button>
                                            <button @click="ToDeleteEmploye(employe.id)" class="btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#dc3545"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line>
                                                </svg>
                                            </button>
                                            <div class="btn-group">
                                                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                </button>
                                                <ul class="dropdown-menu p-0 rounded-0 shadow">
                                                    <h6 class="text-capitalize fw-medium p-2 bg-dark text-white">Poste</h6>
                                                    <div class="w-100">
                                                        <li class="w-100">
                                                            <button v-for="position in positions" @click="updateEmployeStatus(employe.id, position.id)" class="btn fw-medium dropdown-btn p-2 w-100 rounded-0 text-left border-bottom text-capitalize">{{ position.name }}</button>
                                                            <button class="btn"></button>
                                                        </li>
                                                    </div>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <Button @click="updateEmploye" type="button" label="Save" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
import SearchComponent from './SearchComponent.vue';
import Button from "primevue/button";
import InputText from "primevue/inputtext";
export default {

    name: 'SettingPersonalComponent',

    components: {
        SearchComponent,
        Button,
        InputText
    },

    data(){
        return {
            employes: null,
            ShowForm: false,
            updateEmployeData: {
                user_id: null,
                user_name: null,
                user_email: null,
                user_tel: null
            },
            userForEdit: null,
            positions: null,
            errMsg: null,
            load: false,
            user_roles: null,
            setroles: []
        }
    },

    methods: {
        getEmploye(){
            axios.get('/api/get/employe').then((res) => {
                this.employes = res.data
                console.log(res.data)
            }).catch((err) => {
                console.log(err)
            })
        },

        ShowEmployeEditForm(id){
            if (!this.ShowForm){
                this.load = true
            }
            this.ShowForm = true
            return new Promise(() => {
                setTimeout(() => {
                    axios.get(`/api/get/employe/${id}`).then((res) => {
                        console.log(res.data)
                        this.userForEdit = res.data.employe
                        this.user_roles = res.data.withroles
                        this.load = false
                    }).catch((err) => {
                        console.log(err);
                    })
                }, 1000)
            })
        },

        ToDeleteEmploye(id) {
            this.$swal.fire({
                title: "Quer realmente apagar o usuario ?",
                text: "Não irá ter mais acesso aos informações e serviço desse colaborador. No caso de recuperação, entre em contato con o fornecedor do aplicativo.",
                showCancelButton: true,
                confirmButtonColor: '#e63958',
                confirmButtonText: 'Deletar colaborador',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(`/api/employe/delete/${id}`).then((res) => {
                        this.$swal.fire({
                            text: res.data,
                            confirmButtonColor: '#42b883',

                        })
                        return this.getEmploye()
                    }).catch((err) => {
                        console.log(err)
                    })

                }
            })
        },

        getUsergroup() {
            axios.get('/api/group').then((response) => {
                this.positions = response.data.positions
            }).catch((error) => {
                console.log(error)
            })
        },

        updateEmployeStatus(id, group_id){
            this.$swal.fire({
                text: "Clique em sim para confirmar a edição da hieraquia do usuario.",
                showCancelButton: true,
                confirmButtonColor: '#333',
                confirmButtonText: 'Sim',
                cancelButtonText: 'Cancelar'
            }).then((res) => {
                if (res.isConfirmed) {
                    axios.post(`/api/employe-status/update/${id}/${group_id}`).then((response) => {
                        this.$swal.fire({
                            text: response.data,
                            icon: "success",
                            confirmButtonColor: '#42b883'
                        })
                        return this.getEmploye()
                    }).catch((errors) => {
                        errors.response.status === 500 ? this.$swal.fire({text: errors.response.data, icon: "error" }): "";
                    })
                }
            })
        },

        updateEmploye() {

            let id = document.getElementById('user-id').value;
            let name = document.getElementById('user-name').value;
            let email = document.getElementById('user-email').value;
            let tel = document.getElementById('user-tel').value;

            this.updateEmployeData.user_id = id;
            this.updateEmployeData.user_email = email;
            this.updateEmployeData.user_name = name;
            this.updateEmployeData.user_tel = tel;

            axios.post('/api/employe/update', this.updateEmployeData).then((response) => {
                this.$toast.success(response.data);
                this.ShowForm = !this.ShowForm;
            }).catch((errors) => {
                this.errMsg = errors.response.data.errors;
                this.$toast.error(errors.response.data);
            })
        },

        updateUserRoles(id, check)
        {
            let user_id = document.getElementById('user-id').value;
            this.updateEmployeData.user_id = user_id;
            if (check){
                this.$swal.fire({
                    text: "You really want to delete this user role ?",
                    icon: "question",
                    showCancelButton: true
                }).then((result) => {
                    if (result.isConfirmed){
                        axios.post('/api/role-delete/' + id, this.updateEmployeData).then((response) => {
                            this.$swal.fire({
                                text: response.data,
                                icon: "success",
                            })
                        }).catch((errors) => {
                                this.$swal.fire({
                                text: errors.response.data,
                                icon: "error",
                            })
                        })

                        return this.ShowEmployeEditForm(user_id);
                    }
                })
            }else{
                this.$swal.fire({
                    text: "You really want to add this function for the user ?",
                    icon: "question",
                    showCancelButton: true
                }).then((result) => {
                    if (result.isConfirmed){
                        axios.post('/api/role/' + id, this.updateEmployeData).then((response) => {
                            this.$swal.fire({
                                text: response.data,
                                icon: "success",
                            })
                        }).catch((errors) => {
                            this.$swal.fire({
                                text: errors.response.data,
                                icon: "error",
                            })
                        })
                        return this.ShowEmployeEditForm(user_id);
                    }
                })

            }
        }
    },

    mounted(){
        this.getEmploye()
        this.getUsergroup()
    }
}
</script>

<style scoped>
.check {
    border: 1px solid rgba(34, 34, 34, 0.18);
    border-left: 6px solid #e63958;
}

.btn-roles{
    border: 1px solid rgba(34, 34, 34, 0.18);
}
</style>
