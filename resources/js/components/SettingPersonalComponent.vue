<template>
    <div>
        <div class="modal fade" id="ModelPersonalSetting" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Personal register Edition</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div v-if="ShowForm" class="w-100 edit-form">
                            <div class="">
                                <p class="text-center"><span class="fw-bold">Edit employe regist</span> <br>
                                    <small class="text-center">user info, user status and user contact.</small>
                                </p>
                            </div>
                            <div class="form w-100">
                                <form class="d-flex flex-column justify-content-center w-100 p-2" v-for="user in userForEdit">
                                    <div class="form-group w-75 m-auto d-flex justify-content-between">
                                        <input type="hidden" :value="user.id" id="user-id">
                                        <div class="w-100">
                                            <span class="text-danger" v-if="errMsg" v-for="msg in errMsg.user_name" v-text="msg"></span>
                                            <input type="text" class="form-control w-75 rounded-0 border-secondary" :value="user.name" id="user-name">
                                        </div>
                                        <div class="px-2"></div>
                                        <div class="w-100">
                                            <span class="text-danger" v-if="errMsg" v-for="msg in errMsg.user_tel" v-text="msg"></span>
                                            <input type="text" class="form-control w-75 rounded-0 border-secondary" :value="user.tel" id="user-tel">
                                        </div>
                                    </div>
                                    <div class="form-group w-100 align-items-center mt-2">
                                        <p class="w-75 m-auto text-danger" v-if="errMsg" v-for="msg in errMsg.user_email" v-text="msg"></p>
                                        <input type="text" class="form-control w-75 m-auto rounded-0 border-secondary" :value="user.email" id="user-email">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="">
                            <table class="table m-auto stripped">
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
                                        <td>{{ employe.id }}</td>
                                        <td class="text-uppercase">{{ employe.name }}</td>
                                        <td>{{ employe.email }}</td>
                                        <td>{{ employe.tel }}</td>
                                        <td class="text-uppercase">{{ employe.groupe }}</td>
                                        <td>
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
                                                    <h6 class="text-capitalize fw-medium p-2">Poste</h6>
                                                    <div class="w-100">
                                                        <li class="w-100">
                                                            <button v-for="group in groups" @click="updateEmployeStatus(employe.id, group.id)" class="btn fw-medium dropdown-btn p-2 w-100 rounded-0 text-left border-bottom text-capitalize">{{ group.groupe }}</button>
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
                        <button @click="updateEmploye" type="button" class="btn btn-primary rounded-0">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
import SearchComponent from './SearchComponent.vue';
export default {

    name: 'SettingPersonalComponent',

    components: {
        SearchComponent
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
            groups: null,
            errMsg: null
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
            this.ShowForm = !this.ShowForm
            axios.get(`/api/get/employe/${id}`).then((res) => {
                console.log(res.data)
                this.userForEdit = res.data
            }).catch((err) => {
                console.log(err);
            })
        },

        ToDeleteEmploye(id) {
            this.$swal.fire({
                title: "Quer realmente apagar o usuario ?",
                text: "Não irá ter mais acesso aos informações e serviço desse colaborador. No caso de recuperação, entre em contato con o fornecedor do aplicativo.",
                showCancelButton: true,
                confirmButtonColor: '#42b883',
                cancelButtonColor: '#d33',
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
                this.groups = response.data
            }).catch((error) => {
                console.log(error)
            })
        },

        updateEmployeStatus(id, group_id){
            this.$swal.fire({
                text: "Clique em sim para confirmar a edição da hieraquia do usuario.",
                showCancelButton: true,
                confirmButtonColor: '#42b883',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim',
                cancelButtonText: 'Cancelar'
            }).then((res) => {
                if (res.isConfirmed) {
                    axios.post(`/api/employe-status/update/${id}/${group_id}`).then((response) => {
                        this.$swal.fire({
                            text: response.data,
                            confirmButtonColor: '#42b883'
                        })
                    })

                    return this.getEmploye()
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
            })
        }
    },

    mounted(){
        this.getEmploye()
        this.getUsergroup()
    }
}


</script>