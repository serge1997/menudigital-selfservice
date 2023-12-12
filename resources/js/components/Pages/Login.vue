<template>
    <div class="container-fuid d-flex justify-content-between">
        <div class="container">
            <div class="row">
                <div class="col-8 m-auto">
                    <h5 class="text-center p-2">Entra com suas credencias</h5>
                    <form @submit.prevent="login" class="shadow w-100 p-4">
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <span class="text-danger" v-text="loginerrresponse"></span><br>
                                <label for="name" class="fs-5">E-mail : </label>
                                <input type="text" class="form-control rounded-0 border-secondary" placeholder="user@gmail.com" v-model="credentials.email">
                                <span class="text-danger" v-if="msgerrors" v-for="erremail in msgerrors.email" v-text="erremail"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <label for="name" class="fs-5">Senha : </label>
                                <input type="password" class="form-control rounded-0 border-secondary" placeholder="your password" v-model="credentials.password">
                                <span class="text-danger" v-if="msgerrors" v-for="errpassword in msgerrors.password" v-text="errpassword"></span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn bg-primary rounded-0 px-4">Login</button>
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
                this.$toast.success("Seja Bem vindo!");
            }).catch((errors) => {
                this.loginerrresponse = errors.response.data.message;
                this.msgerrors = errors.response.data.errors
            })
        }

    },

    mounted(){

    }
}

</script>
