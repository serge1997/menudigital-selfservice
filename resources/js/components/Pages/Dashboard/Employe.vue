<template>
    <SideBarComponent />
    <div class="container-fuid">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-11 m-auto">
                    <form @submit.prevent="createUser" class="w-100 p-4">
                        <div class="row">
                            <div class="form-header p-2 text-capitalize rounded-3 text-white w-100">
                                <h6>new collaborator</h6>
                                <p>Save new collaborator</p>
                            </div>
                            <div class="col-md-8 m-auto mt-3 p-2">
                                <ProgressBar v-if="load" mode="indeterminate" style="height: 6px"></ProgressBar>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="d-flex flex-column gap-2">
                                    <label for="user-name">{{$t('forms.name')}}</label>
                                    <InputText :class="invalid" type="text" id="user-name" v-model="user.name" aria-describedby="user name" placeholder="collaborator name"/>
                                    <small class="text-danger" v-if="errMsg" v-for="errname in errMsg.name" id="user-name-err"  v-text="errname"></small>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="d-flex flex-column gap-2">
                                    <label for="user-tel">{{$t('forms.tel')}}</label>
                                    <InputText :class="invalid" type="text" id="user-contac" v-model="user.tel" aria-describedby="user name" placeholder="collaborator contact"/>
                                    <small class="text-danger" v-if="errMsg" v-for="errtel in errMsg.tel" id="user-tel-err"  v-text="errtel"></small>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="d-flex flex-column gap-2">
                                    <label for="user-email">E-mail</label>
                                    <InputText :class="invalid" type="text" id="user-email" v-model="user.email" aria-describedby="user email" placeholder="collaborator email"/>
                                    <small class="text-danger" v-if="errMsg" v-for="erremail in errMsg.email" id="user-email-err"  v-text="erremail"></small>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="d-flex flex-column gap-2">
                                    <label for="user-name">{{$t('forms.user_name')}}</label>
                                    <InputText :class="invalid" type="text" id="user-name" v-model="user.username" aria-describedby="user email" placeholder="collaborator username"/>
                                    <small class="text-danger" v-if="errMsg" v-for="errusername in errMsg.username" id="username-err"  v-text="errusername"></small>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="d-flex flex-column gap-2">
                                    <label for="user-password">{{$t('forms.password')}}</label>
                                    <InputText :class="invalid" type="password" id="user-name" v-model="user.password" aria-describedby="user password" placeholder="user password"/>
                                    <small class="text-danger" v-if="errMsg" v-for="errpassword in errMsg.password" id="user-password-err"  v-text="errpassword"></small>
                                </div>
                            </div>
                            <div class="col-md-12 d-flex mt-3 gap-1">
                                <div class="col-md-6">
                                    <Dropdown :class="invalid" v-model="user.department_id" option-value="id" :options="departments" optionLabel="name" :placeholder="` ${ $t('forms.placeholder_dept') } `" class="w-100 md:w-14rem" />
                                    <small class="text-danger" v-if="errMsg" v-for="errdepartment in errMsg.department_id" id="user-department-err"  v-text="errdepartment"></small>
                                </div>
                                <div class="col-md-6">
                                    <Dropdown :class="invalid" v-model="user.position_id" option-value="id" :options="positions" optionLabel="name" :placeholder="` ${ $t('forms.placeholder_post') } `" class="w-100 md:w-14rem" />
                                    <small class="text-danger" v-if="errMsg" v-for="errposition in errMsg.position_id" id="user-password-err"  v-text="errposition"></small>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="col-md-12">
                                    <InputText v-model="user.salary" class="w-100" type="number" :placeholder="`${$t('forms.placeholder_salary')}`" />
                                </div>
                            </div>
                            <div class="col-md-12 d-flex flex-column mt-3 gap-1">
                                <div class="col-md-12 d-flex gap-3">
                                    <div class="form-check form-switch">
                                        <input v-model="user.is_full_time" value="1" class="form-check-input" type="radio" name="tipo-colaborador" id="pleno">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">{{ $t('forms.fulltime') }}</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input v-model="user.is_full_time" value="0" class="form-check-input" type="radio" name="tipo-colaborador" id="taxa">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">{{ $t('forms.partial') }}</label>
                                    </div>
                                </div>
                                <small class="text-danger" v-if="errMsg" v-for="is_full_time in errMsg.is_full_time" id="user-is-full-time"  v-text="is_full_time"></small>
                            </div>
                        </div>
                        <div class="mt-3 w-50">
                            <Button :label="`${$t('forms.submits.create')}`" type="submit" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SideBarComponent from './SideBarComponent.vue'
import { randTime } from '../../../rand';
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import Button from "primevue/button";
import ProgressBar from 'primevue/progressbar';
export default {
    name: 'Employe',

    components: {
        SideBarComponent,
        InputText,
        Dropdown,
        Button,
        ProgressBar
    },

    data(){
        return {
            departments: null,
            positions: null,
            user: {
                name: null,
                tel: null,
                username: null,
                password: null,
                department_id: null,
                position_id: null,
                email: null,
                is_full_time: null,
                salary: null
            },
            errMsg: null,
            invalid: null,
            load: false
        }
    },

    methods: {
        getDepartment(){
            return new Promise(async (resole, reject) => {
                let department = await axios.get('/api/departments');
                resole(department.data);
                if (department.status != 200){
                    reject(department.errors);
                }
            })
        },

        getPosition(){
            return new Promise( async (resolve, reject) => {
                const position = await this.axios.get('/api/positions');
                resolve(position.data);
                if (position.status != 200){
                    reject(position.errors)
                }
            })
        },

        createUser() {
            this.load = true;
            return new Promise(resolve => {
                setTimeout(() => {
                    axios.post('/api/create/user', this.user).then((response) => {
                        this.user.email = "";
                        this.user.name = "";
                        this.user.tel = "";
                        this.user.group_id = "";
                        this.user.password = "";
                        this.invalid = '';
                        this.user.is_full_time = null;
                        this.user.salary = null;
                        this.errMsg = null;
                        this.$swal.fire({
                            text: response.data,
                            icon: 'success'
                        });
                        resolve(true);
                    })
                    .catch((errors) => {
                        console.log(errors.response.data.errors);
                        this.errMsg = errors.response.data.errors;
                        errors.response.status === 500 ? this.$swal.fire({text: errors.response.data, icon: 'error'}) : null;
                        this.invalid = 'p-invalid'
                    })
                    .finally(this.load = false)
                }, randTime())
            })
        }
    },

    mounted(){
        window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`

        Promise.all([
            this.getDepartment().then(response => {
                this.departments = response;
            })
            .catch(errors => console.log(errors)),

            this.getPosition().then(response => {
                this.positions = response;
            })
            .catch(errors => console.log(errors))
        ])

    }
}

</script>
