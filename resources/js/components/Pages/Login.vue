<template>
    <div class="container-fluid min-vh-100 d-flex justify-content-between align-items-center">
        <div class="container">
            <Dialog v-model:visible="visibleLoginModal" maximizable modal header="" :style="{ width: '50rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
                <div class="col-md-8 d-flex flex-column m-auto">
                    <p v-if="load" class="text-center">Aguarde, carregando suas permissões...</p>
                    <ProgressBar v-if="load" mode="indeterminate" style="height: 6px"></ProgressBar>
                </div>
            </Dialog>
            <div class="row p-0">
                <div class="col-lg-7 col-md-10 p-0 d-flex flex-column align-items-center justify-content-center">
                    <h3 class="text-capitalize fw-light">restaurant gestion integration service.</h3>
                    <h3 class="text-capitalize fw-light text-wrap">Cashier, stock, personal, inventory, technical fiche, meal cost</h3>
                </div>
                <div class="col-lg-5 col-md-10 bg-white text-secondary shadow rounded-2 p-3" :class="{ loginAnim: logAnim}">
                    <h5 class="text-center text-dark w-100 fw-medium p-3">Entra com suas credencias</h5>
                    <form @submit.prevent="login" class="w-100">
                        <div class="d-flex flex-column gap-2">
                            <small class="text-danger" v-text="loginerrresponse"></small>
                            <label for="username">Username</label>
                            <InputText :class="invalid" type="text" id="username" v-model="credentials.username" aria-describedby="username-help" />
                            <small class="text-danger" v-if="msgerrors" v-for="err_username in msgerrors.username" id="err_username"  v-text="err_username"></small>
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
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import ProgressBar from "primevue/progressbar";

export default {
    name: 'Login',

    components: {
        InputText,
        Password,
        Button,
        Dialog,
        ProgressBar
    },

    data(){
        return {
            groups: null,
            credentials: {
                password: null,
                username: null,
                device_name: 'browser'
            },
            msgerrors: null,
            loginerrresponse: null,
            logAnim: false,
            invalid: null,
            visibleLoginModal: false,
            load: false
        }
    },

    methods: {
        login() {
            return new Promise(resolve => {
                setTimeout(() => {
                    axios.post('/api/login', this.credentials).then((response) => {
                        this.visibleLoginModal = true;
                        this.load = true;
                        localStorage.setItem('token', response.data.token);
                        localStorage.setItem('stockAccess', response.data.stockAccess);
                        localStorage.setItem('managerAccess', response.data.managerAcess);
                        this.$toast.success("Seja Bem vindo!");
                        resolve(true);
                        setTimeout(() => {this.$router.push({path: '/dashboard/operador'})}, this.randTime())
                    }).catch((errors) => {
                        errors.response.status !== 422 ? this.loginerrresponse = errors.response.data: ""
                        this.msgerrors = errors.response.data.errors
                        this.invalid = 'p-invalid'
                    })
                }, 0)
            })
        },

        randTime(){
            return Math.floor(Math.random() * (2500 - 1500) + 1500);
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
