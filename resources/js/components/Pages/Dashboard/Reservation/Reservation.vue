<template>
    <div class="container-fluid">
        <SideBarComponent />
        <div class="container">
            <div class="w-100 d-flex">
                <Toolbar class="w-100">
                    <template #start>
                        <div>
                            <h5 class="d-flex gap-3 align-items-center"><i class="pi pi-calendar"></i>Lista reservação</h5>
                        </div>
                    </template>
                    <template #end>
                        <div>
                            <Button @click="visibleNewReservationModal = true" icon="pi pi-plus" label="Novo" />
                        </div>
                    </template>
                </Toolbar>
            </div>
        </div>
        <div class="container">
            <ListReservationComponent @show-model-from-list="showModal" />
        </div>
        <div class="container">
            <Dialog v-model:visible="visibleNewReservationModal" maximizable modal header="Nova reservação" :style="{ width: '75rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
                <div class="col-md-12 mt-3">
                    <div class="d-flex align-items-center gap-2 mb-3 p-2">
                        <Button text class="border" label="1" rounded />
                        <p class="text-uppercase">Informação da reservação</p>
                    </div>
                    <div class="row d-flex">
                        <div class="col-lg-4 col-md-10 d-flex justify-content-center gap-2 align-items-center">
                            <label class="fw-medium">N pessoa:  </label>
                            <div class="col-md-8">
                                <Button icon="pi pi-plus" severity="success"/>
                                <InputText type="number" class="w-25" placeholder="000"/>
                                <Button icon="pi pi-minus" severity="primary" />
                            </div>
                        </div>
                        <div class="col-md-4 d-flex justify-content-center gap-2 align-items-center">
                            <label class="fw-medium">Date: </label>
                            <Calendar showIcon iconDisplay="input" />
                        </div>
                        <div class="col-md-4 d-flex justify-content-center gap-2 align-items-center">
                            <label class="fw-medium">Horario: </label>
                            <Calendar showIcon iconDisplay="input" timeOnly />
                        </div>
                    </div>
                    <div class="w-100 border-top mt-3">
                        <div class="d-flex align-items-center gap-2 mt-3 p-2">
                            <Button text class="border" label="2" rounded />
                            <p class="text-uppercase">Informação do cliente</p>
                        </div>
                        <div class="d-flex justify-content-center mt-3 gap-3">
                            <InputText class="col-md-5" placeholder="Nome"/>
                            <InputText class="col-md-5" placeholder="Sobrenome"/>
                        </div>
                        <div class="d-flex justify-content-center mt-3 gap-3">
                            <InputText class="col-md-5" placeholder="Celular"/>
                            <InputText class="col-md-5" placeholder="e-mail"/>
                        </div>
                        <div class="col-md-11 m-auto d-flex justify-content-center mt-3">
                            <Textarea class="col-md-11" placeholder="Observações..."/>
                        </div>
                    </div>
                    <div class="col-md-10 m-auto mt-3">
                        <Dropdown v-model="reservationData.canal" option-value="canal" :options="reservationCanal" optionLabel="canal" placeholder="Select user function" class="w-100 md:w-14rem" />
                    </div>
                    <div class="w-100 d-flex justify-content-center mt-3">
                        <Button label="Confirmar reservação"/>
                    </div>
                </div>
            </Dialog>
        </div>
    </div>
</template>

<script>
import SideBarComponent from '../SideBarComponent.vue';
import ListReservationComponent from './ListReservationComponent.vue';
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import Toolbar from "primevue/toolbar";
import Calendar from "primevue/calendar";
import Dropdown from "primevue/dropdown";
export default {
    name: 'Reservation',

    components: {
        SideBarComponent,
        ListReservationComponent,
        Dialog,
        Button,
        InputText,
        Toolbar,
        Calendar,
        Textarea,
        Dropdown
    },
    data(){
        return {
            visibleNewReservationModal: false,
            reservationCanal: [
                {"canal": "Whatsapp"},
                {"canal": "Instagram"},
                {"canal": "Facebook"},
                {"canal": "Telefone"},
                {"canal": "E-mail"}
            ],
            reservationData: {
                canal: null
            }
        }
    },

    methods: {
        showModal(){
            this.visibleNewReservationModal = true;
        }
    },

    mounted(){

    }
}
</script>
