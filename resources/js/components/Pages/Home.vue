<template>

    <div class="container-fluid min-vh-100">
        <div class="row banner">

        </div>
        <div class="row p-2 shadow brandy">
            <h5 class="text-white">Restaurant Equinox</h5>
            <small class="text-white"><i>Mais que um sabor</i></small>
        </div>
        <div class="w-100 mt-5">
            <div class="col-md-4 d-flex justify-content-center m-auto mb-4">
                <button type="button" class="col-md-4 nav-link px-4 py-1" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    Menu
                </button>
                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content rounded-0">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Seja bem vindo ao Equinox Bar: </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div>
                                    <label class="fs-5" for="">Informe o numero da tabela: </label>
                                    <select class="form-select rounded-0 border-secondary" v-model="tableNumber" id="">
                                        <option v-for="tab in table" :value="tab.table">{{ tab.table }}</option>
                                    </select>
                                </div>
                                <div class="d-flex flex-column align-items-center mt-4 justify-content-center">
                                    <button @click="SetTableNumer" class="btn btn-primary rounded-0 h-4" data-bs-dismiss="modal">OK</button>
                                </div>
                            </div>
                            <div class="text-center p-4 text-secondary">
                               <small class="text-center">O numero da mesa está na deco da mesa</small>
                            </div>
                        </div>
                </div>
            </div>
            </div>
            <div class="col-md-4 d-flex justify-content-center m-auto">
                <button class="col-md-4 nav-link rounded-0 px-4 py-1" @click.prevent="$router.push({name: 'Garcom'})">
                    Dashboard
                </button>
            </div>
            <div class="col-md-4 d-flex justify-content-center m-auto mt-3">
                <button class="col-md-4 px-4 py-1" @click.prevent="logout">
                    Logout
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Home',

    components: {

    },

    data() {
        return {
            tableNumber: null,
            table: null,
            token: localStorage.getItem('token')
        }
    },

    async created() {
        const response = await axios.get('/api/tablenumber')
        this.table = await response.data
    },

    methods: {
        SetTableNumer() {
            localStorage.setItem('table', this.tableNumber);
            this.$router.push('/menu')
            //window.location.reload();
        },

        logout() {
            axios.post('/api/logout').then((response) => {
                localStorage.removeItem('token')
                this.$router.push('/');
            }).catch((error) => {
                console.log(error);
            })
        }
    },

    mounted(){
        window.axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        console.log(localStorage.getItem('token'))
    }
}

</script>

<style scoped>

.container-fluid {
    background-color: #1F2024 ;
}

.nav-link {
    background-color: #Fff;
}

.banner {
    background: url('img/banner.jpg');
    background-position: center;
    background-size: content;
    min-height: 35vh;
}

.brandy {
    background-color: #42424250;
}
</style>
