<template>
    <div class="container-fluid min-vh-100 d-flex justify-content-between align-items-center">
        <div class="container">
            <div class="row p-0">
                <div class="col-lg-7 col-md-10 p-0 d-flex flex-column align-items-center justify-content-center">
                    <h3 class="text-capitalize fw-light">restaurant gestion integration service.</h3>
                    <h3 class="text-capitalize fw-light text-wrap">Cashier, stock, personal, inventory, technical fiche, meal cost</h3>

                </div>
                <div class="col-lg-5 col-md-10 bg-white text-secondary shadow rounded-2 p-3">
                    <h5 class="text-center text-dark border shadow w-100 fw-medium p-3">Entra com suas credencias</h5>
                    <form @submit.prevent="login" class="shadow w-100">
                        <div class="mt-3">
                            <span class="text-danger" v-text="loginerrresponse"></span><br>
                            <label for="name" class="fs-6">E-mail : </label>
                            <input type="text" class="form-control rounded-0 border-secondary" placeholder="user@gmail.com" v-model="credentials.email">
                            <span class="text-danger" v-if="msgerrors" v-for="erremail in msgerrors.email" v-text="erremail"></span>
                        </div>
                        <div class="row">
                            <div class="mt-3">
                                <label for="name" class="fs-6">Senha : </label>
                                <input type="password" class="form-control rounded-0 border-secondary" placeholder="your password" v-model="credentials.password">
                                <span class="text-danger" v-if="msgerrors" v-for="errpassword in msgerrors.password" v-text="errpassword"></span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn bg-dark text-white rounded-0 px-4">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: 'Login',

    components: {

    },

    data(){
        return {
            groups: null,
            credentials: {
                password: null,
                email: null,
                device_name: 'browser'
            },
            msgerrors: null,
            loginerrresponse: null
        }
    },

    methods: {
        login() {
            axios.post('/api/login', this.credentials).then((response) => {
                this.$router.push({path: '/dashboard/operador'})
                localStorage.setItem('token', response.data)
                console.log(response.data)
                this.$toast.success("Seja Bem vindo!");
            }).catch((errors) => {
                this.loginerrresponse = errors.response.data.message;
                this.$toast.error(errors.response.data);
                this.msgerrors = errors.response.data.errors
            })
        }

    },

    mounted(){

    }
}

</script>
<style scoped>
.container-fluid {
    background-color: #e63958;
    color: #fff;
}
</style>
