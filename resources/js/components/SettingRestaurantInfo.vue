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
                                <InputText type="text" v-model="restaurant.res_name" placeholder="" id="" />
                            </div>
                            <div class="w-75 d-flex flex-column">
                                <label class="px-2"  for="">Restaurant E-mail </label>
                                <InputText type="text" v-model="restaurant.res_email" placeholder="" id="" />
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <div class="w-75 d-flex flex-column">
                                <label for="">City </label>
                                <InputText type="text" v-model="restaurant.res_city" placeholder="" id="" />
                            </div>
                            <div class="w-75 d-flex flex-column">
                                <label class="px-2" for="">Neighborhoods </label>
                                <InputText type="text" v-model="restaurant.res_neighborhoods" placeholder="" id="" />
                            </div>
                        </div>
                        <div class="d-flex flex-column mt-2">
                            <label class="fw-medium">Logo: </label>
                            <InputText type="file" v-model="restaurant.res_logo"/>
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <div class="w-75 d-flex flex-column">
                                <label class="text-capitalize" for="">Hora abertura : </label>
                                <InputText type="time" v-model="restaurant.res_open"/>
                            </div>
                            <div class="w-75 d-flex flex-column">
                                <label class="px-2 text-capitalize"  for="">hora fechamento: </label>
                                <InputText type="time" v-model="restaurant.res_close"/>
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
                id: 1,
                res_name: null,
                res_email: null,
                res_city: null,
                res_neighborhoods: null,
                res_logo: null,
                res_open: null,
                res_close: null
            }
        }
    },

    methods: {
        postRestInfo(){
            let method;
            this.restaurant.id == null ? method = axios.post: method = axios.put;
            method("/api/rest-info", this.restaurant).then((response) => {
                console.log(response.data)
            })
        }
    },

    mounted(){
        //postRestInfo();
    }
}
</script>
