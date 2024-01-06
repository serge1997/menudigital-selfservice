<template>
    <div class="modal fade" id="ModalRestaurantinfo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content rounded-0">
                <div class="modal-header text-white rounded-0">
                    <h5 class="modal-title" id="exampleModalLabel">Registe The General Restaurant Information</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    <div class="modal-body p-4">
                        <div class="d-flex justify-content-center align-items-center py-4">
                            <div class="res-header">
                                <h6 class="text-capitalize">Salvar informação geral</h6>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <div class="w-75 d-flex flex-column">
                                <input type="hidden" v-model="restaurant.id"/>
                                <label for="">Restaurant Name </label>
                                <InputText type="text" :class="invalidInput" v-model="restaurant.rest_name" placeholder="" id="" />
                                <small class="text-danger" v-if="errMsg" v-for="errname in errMsg.rest_name" v-text="errname"></small>
                            </div>
                            <div class="w-75 d-flex flex-column">
                                <label class="px-2"  for="rest-email">Restaurant E-mail </label>
                                <InputText type="text" :class="invalidInput" v-model="restaurant.rest_email" placeholder="" id="rest-email" />
                                <small class="text-danger" v-if="errMsg" v-for="erremail in errMsg.rest_email" v-text="erremail"></small>
                            </div>
                        </div>
                        <div class="d-flex flex-column mt-2">
                            <label for="rest-cnpj" class="">CNPJ : </label>
                            <InputText type="text" :class="invalidInput" v-model="restaurant.rest_cnpj" id="rest-cnpj"/>
                            <small class="text-danger" v-if="errMsg" v-for="errcnpj in errMsg.rest_cnpj" v-text="errcnpj"></small>
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <div class="w-75 d-flex flex-column">
                                <label for="">City </label>
                                <InputText type="text" :class="invalidInput" v-model="restaurant.res_city" placeholder="" id="rest-city" />
                                <small class="text-danger" v-if="errMsg" v-for="errcity in errMsg.res_city" v-text="errcity"></small>
                            </div>
                            <div class="w-75 d-flex flex-column">
                                <label class="px-2" for="">Neighborhoods </label>
                                <InputText type="text" :class="invalidInput" v-model="restaurant.res_neighborhood" placeholder="" id="" />
                                <small class="text-danger" v-if="errMsg" v-for="errneighborhood in errMsg.res_neighborhood" v-text="errneighborhood"></small>
                            </div>
                        </div>
                        <div class="d-flex flex-column mt-2">
                            <label class="" for="rest-streetname">Street name: </label>
                            <InputText id="rest-streetname" type="text" :class="invalidInput" v-model="restaurant.rest_streetName"/>
                            <small class="text-danger" v-if="errMsg" v-for="errstreetName in errMsg.rest_streetName" v-text="errstreetName"></small>
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <div class="w-75 d-flex flex-column">
                                <label for="rest-cep">Cep </label>
                                <InputText type="text" v-model="restaurant.rest_cep" placeholder="" id="rest-cep" />
                            </div>
                            <div class="w-75 d-flex flex-column">
                                <label class="px-2" for="rest-StreetNumber">Address number </label>
                                <InputText type="text" :class="invalidInput" v-model="restaurant.rest_StreetNumber" placeholder="" id="rest-StreetNumber" />
                                <small class="text-danger" v-if="errMsg" v-for="errStreetNumber in errMsg.rest_StreetNumber" v-text="errStreetNumber"></small>
                            </div>
                        </div>
                        <div v-if="!restaurant.id" class="d-flex flex-column mt-2">
                            <label class="" id="rest-logo">Logo: </label>
                            <InputText :class="invalidInput" type="file" @change="logoHandle" id="rest-logo"/>
                            <small class="text-danger" v-if="errMsg" v-for="errlogo in errMsg.res_logo" v-text="errlogo"></small>
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <div class="w-75 d-flex flex-column">
                                <label class="text-capitalize" for="">Hora abertura : </label>
                                <InputText :class="invalidInput" type="time" v-model="restaurant.res_open"/>
                                <small class="text-danger" v-if="errMsg" v-for="erropen in errMsg.res_open" v-text="erropen"></small>
                            </div>
                            <div class="w-75 d-flex flex-column">
                                <label class="px-2 text-capitalize"  for="">hora fechamento: </label>
                                <InputText :class="invalidInput" type="time" v-model="restaurant.res_close"/>
                                <small class="text-danger" v-if="errMsg" v-for="errclose in errMsg.res_close" v-text="errclose"></small>
                            </div>
                        </div>
                    <div class="modal-footer">
                        <Button @click="postRestInfo" type="button">Salvar</Button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import InputText from "primevue/inputtext";
import Button from "primevue/button";
export default {
    name: 'SettingRestaurantInfo',

    components: {
        InputText,
        Button
    },

    data(){
        return{
            restaurant: {
                id: null,
                rest_name: null,
                rest_email: null,
                rest_cnpj: null,
                res_city: null,
                res_neighborhood: null,
                rest_cep: null,
                rest_streetName: null,
                rest_StreetNumber: null,
                res_logo: null,
                res_open: null,
                res_close: null
            },
            errMsg: null,
            invalidInput: null
        }
    },

    methods: {
        getRestaurantInfo(){
            axios.get('/api/rest-info').then((response) => {
                console.log(response.data)
                for (let result of response.data){
                    console.log(result.rest_name)
                    this.restaurant.rest_name = result.rest_name;
                    this.restaurant.rest_email = result.rest_email;
                    this.restaurant.res_close = result.res_close;
                    this.restaurant.res_open = result.res_open;
                    this.restaurant.id  = result.id;
                    this.restaurant.rest_StreetNumber = result.rest_StreetNumber;
                    this.restaurant.res_city = result.res_city;
                    this.restaurant.rest_cep = result.rest_cep;
                    this.restaurant.rest_cnpj = result.rest_cnpj;
                    this.restaurant.rest_streetName = result.rest_streetName;
                    this.restaurant.res_neighborhood = result.res_neighborhood;
                    this.restaurant.res_logo = result.res_logo
                }
            }).catch((errors) => {
                console.log(errors);
            })
        },
        logoHandle(event){
            this.restaurant.res_logo = event.target.files[0];
        },
        postRestInfo(){
            let method;
            let data = new FormData()
            data.append('id', this.restaurant.id);
            data.append('res_logo', this.restaurant.res_logo);
            data.append('rest_name', this.restaurant.rest_name);
            data.append('rest_email', this.restaurant.rest_email);
            data.append('rest_cnpj', this.restaurant.rest_cnpj);
            data.append('res_city', this.restaurant.res_city);
            data.append('res_neighborhood', this.restaurant.res_neighborhood);
            data.append('rest_cep', this.restaurant.rest_cep);
            data.append('rest_streetName', this.restaurant.rest_streetName);
            data.append('rest_StreetNumber', this.restaurant.rest_StreetNumber);
            data.append('res_open', this.restaurant.res_open);
            data.append('res_close', this.restaurant.res_close);
            this.restaurant.id == null ? method = axios.post: method = axios.put;
            method("/api/rest-info", this.restaurant.id == null ? data : this.restaurant).then((response) => {
                console.log(response.data)
                this.$toast.success(response.data);
            }).catch((errors) => {
                this.invalidInput = 'p-invalid';
                this.errMsg = errors.response.data.errors;
                console.log(errors.response.data);
            })
        },
    },

    mounted(){
        //postRestInfo();
        this.getRestaurantInfo()
    }
}
</script>
