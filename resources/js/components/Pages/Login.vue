<template>
    <div class="container-fluid min-vh-100 d-flex justify-content-between align-items-center">
        <div class="container">
            <div class="row p-0">
                <div class="col-lg-7 col-md-10 p-0 d-flex flex-column align-items-center justify-content-center">
                    <h3 class="text-capitalize fw-light">restaurant gestion integration service.</h3>
                    <h3 class="text-capitalize fw-light text-wrap">Cashier, stock, personal, inventory, technical fiche, meal cost</h3>
                </div>
                <div class="col-lg-5 col-md-10 bg-white text-secondary shadow rounded-2 p-3" :class="{ loginAnim: logAnim}">
                    <h5 class="text-center text-dark w-100 fw-medium p-3">Entra com suas credencias</h5>
                    <form @submit.prevent="login" class="w-100">
                        <div class="d-flex flex-column gap-2">
                            <label for="email">Username</label>
                            <InputText :class="invalid" type="text" id="email" v-model="credentials.email" aria-describedby="username-help" />
                            <small class="text-danger" v-if="msgerrors" v-for="erremail in msgerrors.email" id="email-err"  v-text="erremail"></small>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <label for="password">Password</label>
                            <InputText :class="invalid" type="password" id="password" v-model="credentials.password" aria-describedby="username-help" />
                            <small class="text-danger" v-if="msgerrors" v-for="errpassword in msgerrors.password" id="password-err"  v-text="errpassword"></small>
                        </div>
                        <div class="mt-3">
                            <Button type="submit" label="Sign-in" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import InputText from "primevue/inputtext";
import Password from "primevue/password";
import Button from "primevue/button"

export default {
    name: 'Login',

    components: {
        InputText,
        Password,
        Button
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
            loginerrresponse: null,
            logAnim: false,
            invalid: null
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
                this.msgerrors = errors.response.data.errors
                this.invalid = 'p-invalid'
            })
        }

    },

    mounted(){
        this.logAnim = true
    }
}

</script>
<style scoped>
.container-fluid {
    background-color: #e63958;
    color: #fff;
}
.loginAnim {
    position: relative;
    animation: form 2s;
}

@keyframes form {
    from {
        transform: translateY(-70%);
        opacity: 0.4;
    }
    to {
        transform: translateY(0%);
        opacity: 1;
    }

}
</style>
