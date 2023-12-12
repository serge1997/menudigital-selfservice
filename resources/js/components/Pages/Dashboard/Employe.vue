<template>
    <div class="container-fuid d-flex justify-content-between">
        <div class="position-fixed">
            <SideBarComponent class="text-center"></SideBarComponent>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-8 m-auto">
                    <h5 class="text-center p-2">Salvar novo colaborador</h5>
                    <form @submit.prevent="createUser" class="shadow w-100 p-4">
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <label for="name" class="fs-5">Nome : </label>
                                <input type="text" class="form-control rounded-0 border-secondary" placeholder="nome do colaborador" v-model="user.name">
                                <p class="text-danger" v-if="errMsg" v-for="errname in errMsg.name" v-text="errname"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <label for="name" class="fs-5">Celular : </label>
                                <input type="text" class="form-control rounded-0 border-secondary" placeholder="Celular pessoal" v-model="user.tel">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <label for="name" class="fs-5">Senha : </label>
                                <input type="password" class="form-control rounded-0 border-secondary" placeholder="Atribuir uma senha de acesso" v-model="user.password">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <label for="name" class="fs-5">E-mail : </label>
                                <input type="text" class="form-control rounded-0 border-secondary" placeholder="email de acesso" v-model="user.email">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <label for="tipo" class="fs-5">Função: </label>
                                <select class="form-select text-capitalize border-secondary rounded-0" v-model="user.group_id">
                                    <option v-for="group in groups" :value="group.id">{{ group.groupe }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn bg-primary rounded-0 px-4">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SideBarComponent from './SideBarComponent.vue'
export default {
    name: 'Employe',

    components: {
        SideBarComponent
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
            errMsg: null
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
            })
        }
    },

    mounted(){
        window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`
        this.getUsergroup();

    }
}

</script>
