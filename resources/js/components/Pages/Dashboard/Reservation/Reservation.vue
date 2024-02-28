<template>
    <div class="container-fluid">
        <SideBarComponent />
        <div class="container">
            <div class="w-100 d-flex flex-column">
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
                <div class="col-sm-8 m-auto">
                    <div class="card mt-3">
                        <div class="card-body">
                            <MeterGroup :value="biData"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <ListReservationComponent :reservation-canal="reservationCanal" :reservations="reservations" @delete-reservation="deleteReservation"/>
        </div>
        <div class="container">
            <Dialog v-model:visible="visibleNewReservationModal" maximizable modal header="Nova reservação" :style="{ width: '75rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
                <div class="col-md-12 mt-3">
                    <ProgressBar v-if="savingLoad" mode="indeterminate" style="height: 6px"></ProgressBar>
                    <div class="d-flex align-items-center gap-2 mb-3 p-2">
                        <Button text class="border" label="1" rounded />
                        <p class="text-uppercase">Informação da reservação</p>
                    </div>
                    <div class="row d-flex">
                        <div class="col-lg-4 col-md-10 d-flex flex-column gap-2 align-items-start">
                            <div class="col-md-12 d-flex gap-3">
                                <label class="fw-medium">N pessoa:  </label>
                                <InputText type="number" :class="invalid" v-model="reservationData.person_quantity" class="w-25" placeholder="000"/>
                            </div>
                            <small class="text-danger" v-if="formErrMessage" v-for="person in formErrMessage.person_quantity" v-text="person"></small>
                        </div>
                        <div class="col-md-4 d-flex flex-column gap-2 align-items-center">
                            <div class="col-md-12 d-flex justify-content-center gap-2">
                                <label class="fw-medium">Date: </label>
                                <Calendar @change="checkValidDate" dateFormat="dd/mm/yy" :class="invalid + ' ' + dateInvalidClass" showIcon iconDisplay="input" id="res-date" v-model="reservationData.date_come_in" />
                            </div>
                            <small class="text-danger" v-if="formErrMessage" v-for="date_come_in in formErrMessage.date_come_in" v-text="date_come_in"></small>
                            <small class="text-danger" v-text="dataInvalid"></small>
                        </div>
                        <div class="col-md-4 d-flex flex-column align-items-center">
                            <div class="col-md-12 d-flex justify-content-center gap-2">
                                <label class="fw-medium">Horario: </label>
                                <Calendar type="time" :class="invalid + dateInvalidClass" showIcon iconDisplay="input" timeOnly v-model="reservationData.hour" />
                            </div>
                            <small class="text-danger" v-if="formErrMessage" v-for="hour in formErrMessage.hour" v-text="hour"></small>
                        </div>
                    </div>
                    <div class="w-100 border-top mt-3">
                        <div class="d-flex align-items-center gap-2 mt-3 p-2">
                            <Button text class="border" label="2" rounded />
                            <p class="text-uppercase">Informação do cliente</p>
                        </div>
                        <div class="d-flex justify-content-center mt-3 gap-3">
                            <div class="col-md-5">
                                <InputText class="col-md-12" :class="invalid" placeholder="Nome" v-model="reservationData.customer_firstName"/>
                                <small class="text-danger" v-if="formErrMessage" v-for="customer_firstName in formErrMessage.customer_firstName" v-text="customer_firstName"></small>
                            </div>
                            <div class="col-md-5">
                                <InputText class="col-md-12" :class="invalid" placeholder="Sobrenome" v-model="reservationData.customer_lastName" />
                                <small class="text-danger" v-if="formErrMessage" v-for="customer_lastName in formErrMessage.customer_lastName" v-text="customer_lastName"></small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center mt-3 gap-3">
                            <div class="col-md-5">
                                <InputMask v-model="reservationData.customer_tel" mask="(99)99 999-9999" :class="invalid" class="col-md-12" placeholder="(99)99 999-9999" />
                                <small class="text-danger" v-if="formErrMessage" v-for="customer_tel in formErrMessage.customer_tel" v-text="customer_tel"></small>
                            </div>
                            <div class="col-md-5">
                                <InputText class="col-md-12" placeholder="e-mail" v-model="reservationData.customer_email"/>
                                <small class="text-danger" v-if="formErrMessage" v-for="customer_email in formErrMessage.customer_email" v-text="customer_email"></small>
                            </div>
                        </div>
                        <div class="col-md-11 m-auto d-flex flex-column align-items-center mt-3">
                            <Textarea class="col-md-11" :class="invalid" placeholder="Observações..." v-model="reservationData.observation"/>
                            <small class="text-danger" v-if="formErrMessage" v-for="observation in formErrMessage.observation" v-text="observation"></small>
                        </div>
                    </div>
                    <div class="col-md-10 m-auto mt-3">
                        <Dropdown v-model="reservationData.reser_canal" :class="invalid" option-value="canal" :options="reservationCanal" optionLabel="canal" placeholder="Reservation canal" class="w-100 md:w-14rem" />
                        <small class="text-danger" v-if="formErrMessage" v-for="reser_canal in formErrMessage.reser_canal" v-text="reser_canal"></small>
                    </div>
                    <div class="w-100 d-flex justify-content-center mt-3">
                        <Button @click.prevent="createReservation" id="btn-save" label="Confirmar reservação"/>
                    </div>
                </div>
            </Dialog>
        </div>
    </div>
</template>

<script>
import SideBarComponent from '../SideBarComponent.vue';
import ListReservationComponent from './ListReservationComponent.vue';
import InputMask from "primevue/inputmask";
import ProgressBar from "primevue/progressbar";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import Toolbar from "primevue/toolbar";
import Calendar from "primevue/calendar";
import Dropdown from "primevue/dropdown";
import { getAuth } from './../../auth';
import MeterGroup from 'primevue/metergroup';
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
        Dropdown,
        ProgressBar,
        InputMask,
        MeterGroup
    },

    watch: {
      'reservationData.date_come_in': {
        handler:  function(newVal, oldVal){
            console.log("working")
            return this.checkValidDate()
        },
        deep: true
      }
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
            biCanal: [
                {label: "whatsapp", color:"#333",  value: 57}
            ],
            biData: JSON.parse(localStorage.getItem('meter_data')),
            reservationData: {
                id: null,
                person_quantity: null,
                date_come_in: null,
                hour: null,
                customer_firstName: null,
                customer_lastName: null,
                customer_email: null,
                customer_tel: null,
                reser_canal: null,
                observation: null
            },
            savingLoad: false,
            formErrMessage: null,
            dataInvalid: null,
            dateInvalidClass: null,
            invalid: null,
            reservations: null,
            user: {
                name: null,
                position_id: null,
            },
            administrativeAccess: localStorage.getItem('administrativeAccess').split(',')
        }
    },

    methods: {
        showModal(id){
            this.visibleNewReservationModal = true;
            alert(id)
        },
        async listAllReservation(){
            const reservationRespone = await axios.get('/api/reservation');
            this.reservations = await reservationRespone.data;

        },

        createReservation(){
            this.savingLoad = true;
            var hour = new Date(this.reservationData.hour);
            var date = new Date(this.reservationData.date_come_in);
            var formatHour = hour.toLocaleDateString('pt-BR', {
                hour: '2-digit',
                minute: '2-digit',
            });

            var dateFormat = date.toLocaleDateString('pt-BR', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
            });
            const data = {
                person_quantity: this.reservationData.person_quantity,
                date_come_in: dateFormat.substr(0, 10),
                hour: formatHour.substr(12, 17),
                customer_firstName: this.reservationData.customer_firstName,
                customer_lastName: this.reservationData.customer_lastName,
                customer_email: this.reservationData.customer_email,
                customer_tel: this.reservationData.customer_tel,
                reser_canal: this.reservationData.reser_canal,
                observation: this.reservationData.observation
            }
            return new Promise(resolve => {
                setTimeout(() => {
                    this.axios.post('/api/reservation', data).then((response) => {
                        this.$toast.success(response.data)
                        this.invalid = '';
                        this.reservationData.person_quantity = '',
                        this.reservationData.customer_firstName = '',
                        this.reservationData.customer_lastName = '',
                        this.reservationData.customer_email = '',
                        this.reservationData.customer_tel = '',
                        this.reservationData.reser_canal = '',
                        this.reservationData.observation = '';
                        this.reservationData.date_come_in = '';
                        this.reservationData.hour = '';
                        resolve(true);
                        this.loadBiData()
                        return this.listAllReservation();
                    })
                    .catch((errors) => {
                        console.log(errors)
                        this.formErrMessage = errors.response.data.errors;
                        this.invalid = "border border-danger";
                        errors.response.status === 500 ? this.$swal.fire({text: errors.response.data, icon: 'warning'}): null;
                    })
                    .finally(() => this.savingLoad = false);
                }, 1000)
            })
        },

        checkValidDate(){
            let calendar = document.getElementById('res-date');
            let save_btn = document.getElementById('btn-save')
            var today = new Date();
            var date = new Date(this.reservationData.date_come_in);
            var todayFormat = today.toLocaleDateString('en-US', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
            })
            var dateFormat = date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
            })
            if (dateFormat < todayFormat){
                save_btn.setAttribute('disabled', 'disabled')
                this.dataInvalid = "data invalida";
                this.dateInvalidClass = 'border border-danger'
            }else {
                save_btn.removeAttribute('disabled')
                this.dataInvalid = null;
                this.dateInvalidClass = null
            };
        },

        deleteReservation(id){
            this.$swal.fire({
                text: 'Está sendo deletando uma reservação. clique em ok para continuar',
                icon: 'warning',
                showCancelButton: true
            }).then(result => {
                if (result.isConfirmed){
                    this.axios.delete('/api/reservation/' + id).then((response) => {
                        this.$toast.success(response.data)
                        return this.listAllReservation()
                    }).catch(errors => {
                        errors.response.status = 500 ? this.$swal.fire({text: errors.response.data, icon: 'warning'}): null;
                    })
                }
            })

        },
        async loadBiData(){
            const biResponse = await axios.get('/api/reservation-bi');
            this.biData = await biResponse.data;
            if (localStorage.getItem('meter_data')){
                localStorage.removeItem('meter_data');
            }
            localStorage.setItem('meter_data', JSON.stringify( await biResponse.data));
            console.log(await biResponse.data)
        }
    },

    mounted(){
        this.listAllReservation();
        this.loadBiData()
        getAuth().then(result => {
            this.user.position_id = result.position_id;
        })
    }
}
</script>
