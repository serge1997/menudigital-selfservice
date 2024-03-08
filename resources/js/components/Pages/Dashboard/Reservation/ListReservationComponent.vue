<template>
    <div class="container-fluid mt-4">
        <div class="w-100 card">
            <div class="card-header">
                <div class="w-100 d-flex justify-content-between">
                    <div class="btn-next">
                        <Button @click="previousMonth" icon="pi pi-angle-left" text/>
                    </div>
                    <div class="month-name">
                        <p class="fw-medium text-uppercase text-secondary">{{ showMonth }}</p>
                    </div>
                    <div class="btn-back">
                        <Button @click="nextMonth" icon="pi pi-angle-right" text/>
                    </div>
                </div>
            </div>
            <div class="card-body col-md-12 m-auto d-flex flex-wrap" id="scheduler">
                <div v-for="date in scheduleDate" class="p-4 col-md-2 border d-flex justify-content-center schedule-da">
                    <div v-if="!this.savedReservationDate.includes(date)" class="d-flex flex-column">
                        <div><Button v-if="!this.savedReservationDate.includes(date)" class="text-center text-secondary" :label="date.substr(0, 5)" text/></div>
                    </div>
                    <div v-else class="col-sm-12 d-flex flex-column">
                        <div class="" v-for="reservation in reservations">
                            <div v-if="reservation.date_come_in == date" class="col-sm-12 d-flex mb-2 border shadow">
                                <button @click="showReservation(reservation.id)" class="col-sm-10 btn d-flex gap-1" :class="setBgColor(reservation.status)" style="font-size: .8em;" icon="pi pi-user">
                                    <i class="pi pi-user"></i>
                                    <span>{{ reservation.customer_fullname }}</span>
                                </button>
                                <div class="d-flex flex-column col-sm-2">
                                    <Button @click="getReservation(reservation.id)" style="font-size: 0.1rem" icon="pi pi-pencil" text/>
                                    <Button @click="$emit('deleteReservation', reservation.id)" style="font-size: 0.1rem" icon="pi pi-trash" text class="text-danger"/>
                                </div>
                            </div>
                        </div>
                            <!-- <span v-if="reservation.date_come_in == date"><Tag :value="reservation.customer_fullname" /></span> -->

                    </div>
                    <!-- <Button v-if="!this.savedReservationDate.includes(date)" class="text-center" :label="date.substr(0, 5)" text/> -->
                </div>
            </div>
        </div>
        <!-- <div v-for="reservation in reservations" class="row d-flex justify-content-center shadow-sm p-1 mb-3 rounded border">
            <div class="col-md-4 d-flex flex-column customer-info">
                <h6>{{ reservation.customer_firstName }} {{ reservation.customer_lastName }}</h6>
                <div class="d-flex gap-2">
                    <span class="text-secondary"><i class="pi pi-phone"></i></span>
                    <Chip>
                        <span>{{ reservation.customer_tel }}</span>
                    </Chip>|
                    <Badge :value="reservation.person_quantity "></Badge>
                    <span class="text-primary"><i class="pi pi-users"></i></span>
                </div>
            </div>
            <div class="col-md-4 d-flex flex-column align-items-start customer-info">
                <h6 class="d-flex gap-2"><span class="text-primary"><i class="pi pi-calendar"></i></span>{{ reservation.date_come_in }}</h6>
                <Tag :value="reservation.hour + 'H' " icon="pi pi-clock" />
            </div>
            <div class="col-md-4 d-flex justify-content-end align-items-center gap-2 customer-info">
                <div>
                    <Button @click="showReservation(reservation.id)" text icon="pi pi-eye"/>
                </div>
                <div v-if="administrativeAccess.includes(`${auth.position_id}`)">
                    <Button @click="getReservation(reservation.id)" text icon="pi pi-pencil"/>
                </div>
                <div v-if="administrativeAccess.includes(`${auth.position_id}`)">
                    <Button @click="$emit('deleteReservation', reservation.id)" icon="pi pi-trash" text class="text-danger"/>
                </div>
            </div>
        </div> -->
        <div class="row">
            <Dialog v-model:visible="visibleShowReservationModal" maximizable modal header="Detalhes da reservação" :style="{ width: '75rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
                <div class="col-md-12 mt-3">
                    <div class="row show-header">
                        <div class="col-md-4 d-flex gap-2 shadow-sm p-2">
                            <p><i class="pi pi-calendar"></i></p>
                            <h6 class="fs-3">{{ reservation.date_come_in }}</h6>
                        </div>
                        <div class="col-md-4 d-flex gap-2 shadow-sm p-2">
                            <p><i class="pi pi-users"></i></p>
                            <h6 class="fs-3">{{ reservation.person_quantity }}</h6>
                        </div>
                        <div class="col-md-4 d-flex gap-2 shadow-sm bg-primary text-white p-2">
                            <p><i class="pi pi-clock"></i></p>
                            <h6 class="fs-3">{{ reservation.hour }}</h6>
                        </div>
                    </div>
                    <div class="row show-body">
                        <div class="col-md-6 p-3">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Sobrenome: <span class="fw-medium">{{ reservation.customer_firstName }}</span></li>
                                <li class="list-group-item">Nome: <span class="fw-medium">{{reservation.customer_lastName}}</span></li>
                                <li class="list-group-item">Celular: <span class="fw-medium">{{ reservation.customer_tel }}</span></li>
                                <li class="list-group-item">E-mail: <span class="fw-medium">{{ reservation.customer_email }}</span></li>
                                <li class="list-group-item">Canal: <span class="fw-medium">{{ reservation.reser_canal }}</span></li>
                            </ul>
                        </div>
                        <div class="col-md-6 p-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Observação</h6>
                                    <p>
                                        {{reservation.observation}}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </Dialog>
        </div>
        <div class="container">
            <Dialog v-if="reservation" v-model:visible="visibleEditionReservationModal" maximizable modal header="Editar reservação" :style="{ width: '75rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
                <div class="row d-flex justify-content-end">
                    <Button @click="updateStatus" class="col-md-2" label="Editar status" text />
                </div>
                <div class="col-md-12 mt-3">
                    <ProgressBar v-if="savingLoad" mode="indeterminate" style="height: 6px"></ProgressBar>
                    <div class="d-flex align-items-center gap-2 mb-3 p-2">
                        <Button text class="border" label="1" rounded />
                        <p class="text-uppercase">Informação da reservação</p>
                    </div>
                    <div class="row d-flex">
                        <div class="col-lg-4 col-md-10 d-flex flex-column gap-2 align-items-start">
                            <div class="col-md-12 d-flex">
                                <label class="fw-medium">N pessoa:  </label>

                                <input type="hidden" id="res-id" :value="reservation.id" />
                                <InputText type="number" :class="invalid" :value="reservation.person_quantity" id="res-person" class="w-25" placeholder="000"/>

                            </div>
                            <small class="text-danger" v-if="formErrMessage" v-for="person in formErrMessage.person_quantity" v-text="person"></small>
                        </div>
                        <div class="col-md-4 d-flex flex-column gap-2 align-items-center">
                            <div class="col-md-12 d-flex justify-content-center gap-2">
                                <label class="fw-medium">Date: </label>
                                <Calendar @change="checkValidDate" dateFormat="dd/mm/yy" :class="invalid + ' ' + dateInvalidClass " id="res-date" showIcon iconDisplay="input" v-model="reservationData.date_come_in" />
                            </div>
                            <small class="text-danger" v-if="formErrMessage" v-for="date_come_in in formErrMessage.date_come_in" v-text="date_come_in"></small>
                            <small class="text-danger" v-text="dataInvalid"></small>
                        </div>
                        <div class="col-md-4 d-flex flex-column align-items-center">
                            <div class="col-md-12 d-flex justify-content-center gap-2">
                                <label class="fw-medium">Horario: </label>
                                <Calendar type="time" :class="invalid" id="res-hour" showIcon iconDisplay="input" timeOnly v-model="reservationData.hour" />
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
                                <InputText class="col-md-12" :class="invalid" placeholder="Nome" id="res-fname" :value="reservation.customer_firstName"/>
                                <small class="text-danger" v-if="formErrMessage" v-for="customer_firstName in formErrMessage.customer_firstName" v-text="customer_firstName"></small>
                            </div>
                            <div class="col-md-5">
                                <InputText class="col-md-12" :class="invalid" placeholder="Sobrenome" id="res-lname" :value="reservation.customer_lastName" />
                                <small class="text-danger" v-if="formErrMessage" v-for="customer_lastName in formErrMessage.customer_lastName" v-text="customer_lastName"></small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center mt-3 gap-3">
                            <div class="col-md-5">
                                <InputText type="text" :class="invalid" class="col-md-12" id="res-tel" placeholder="Celular" :value="reservation.customer_tel" />
                                <small class="text-danger" v-if="formErrMessage" v-for="customer_tel in formErrMessage.customer_tel" v-text="customer_tel"></small>
                            </div>
                            <div class="col-md-5">
                                <InputText class="col-md-12" placeholder="e-mail" id="res-email" :value="reservation.customer_email"/>
                                <small class="text-danger" v-if="formErrMessage" v-for="customer_email in formErrMessage.customer_email" v-text="customer_email"></small>
                            </div>
                        </div>
                        <div class="col-md-11 m-auto d-flex flex-column align-items-center mt-3">
                            <Textarea class="col-md-11" :class="invalid" placeholder="Observações..." id="res-observation" :value="reservation.observation"/>
                            <small class="text-danger" v-if="formErrMessage" v-for="observation in formErrMessage.observation" v-text="observation"></small>
                        </div>
                    </div>
                    <div class="col-md-10 m-auto mt-3">
                        <Dropdown v-model="reservationData.reser_canal" :class="invalid" option-value="canal" :options="reservationCanal" id="reser-canal" optionLabel="canal" placeholder="Select user function" class="w-100 md:w-14rem" />
                        <small class="text-danger" v-if="formErrMessage" v-for="reser_canal in formErrMessage.reser_canal" v-text="reser_canal"></small>
                    </div>
                    <div class="w-100 d-flex justify-content-center mt-3">
                        <Button id="bt-update" @click.prevent="updateReservation" label="Confirmar a edição"/>
                    </div>
                </div>
            </Dialog>
        </div>
    </div>
</template>
<script>
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import Chip from "primevue/chip";
import Badge from "primevue/badge";
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import {getAuth} from "./../../auth.js";
import Textarea from "primevue/textarea";
import Calendar from "primevue/calendar";
import Tag from "primevue/tag";
export default {
    name: 'ListReservationComponent',

    props: ['reservationCanal', 'reservations', 'savedReservationDate'],

    components: {
        Button,
        Dialog,
        Chip,
        Badge,
        InputText,
        Dropdown,
        Textarea,
        Calendar,
        Tag
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
    watch: {
        navMonthIndex(newVal, oldVal){
            newVal === 11 ? this.navMonthIndex = -1 : this.navMonthIndex
        }
    },
    data(){
        return {
            visibleShowReservationModal: false,
            visibleEditionReservationModal: false,
            id: 2,
            auth: {
                id: null,
                department: null,
                adm: 1,
                showActions: false,
                position_id: null,
            },
            reservation: null,
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
            formErrMessage: null,
            administrativeAccess: localStorage.getItem('administrativeAccess').split(','),
            dateInvalidClass: null,
            dataInvalid: null,
            scheduleDate: [],
            showMonth: 'Janeiro',
            navMonthIndex: 0,
            scheduleMonth:[
                'Janeiro',
                'Fevereiro',
                'Março',
                'Abril',
                'Maio',
                'Junho',
                'Julho',
                'Agosto',
                'Setembro',
                'Outubro',
                'Novembro',
                'Dezembro'
            ],
            reser_bgColor: null
        }
    },

    methods: {
        async getReservation(id){
            const resp = await axios.get('/api/reservation/' + id);
            this.reservation = await resp.data;
            this.visibleEditionReservationModal = true;

        },

        async showReservation(id){
            const response = await axios.get('/api/reservation/' + id);
            this.reservation = await response.data;
            this.visibleShowReservationModal = true;
        },

        updateReservation(){
            this.savingLoad = true;
            var hour = "";
            var date = "";
            var formatHour = "";
            var dateFormat = "";

            hour = new Date(this.reservationData.hour);
            date = new Date(this.reservationData.date_come_in);
            formatHour = hour.toLocaleDateString('pt-BR', {
                hour: '2-digit',
                minute: '2-digit',
            });

            dateFormat = date.toLocaleDateString('pt-BR', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
            });


            const data = {
                person_quantity: document.getElementById('res-person').value,
                date_come_in: dateFormat,
                hour: formatHour,
                id: document.getElementById('res-id').value,
                customer_firstName: document.getElementById('res-fname').value,
                customer_lastName: document.getElementById('res-lname').value,
                customer_email: document.getElementById('res-email').value,
                customer_tel: document.getElementById('res-tel').value,
                reser_canal: this.reservationData.reser_canal,
                observation: document.getElementById('res-observation').value,
            }
            return new Promise(resolve => {
                setTimeout(() => {
                    this.axios.put('/api/reservation', data).then((response) => {
                        this.$toast.success(response.data)
                        this.formErrMessage
                        this.invalid = '';
                        location.reload()
                        resolve(true);
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
            let save_btn = document.getElementById('bt-update')
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
        setPeriod(){
            let month = null;
            let endDay = null;
            switch(this.showMonth) {
                case "Janeiro":
                    month = 0;
                    endDay = 31;
                    break;
                case "Fevereiro":
                    month = 1;
                    endDay = 29;
                    break;
                case "Março":
                    month = 2;
                    endDay = 31;
                    break;
                case "Abril":
                    month = 3;
                    endDay = 30;
                    break;
                case "Maio":
                    month = 4;
                    endDay = 31;
                    break;
                case "Junho":
                    month = 5;
                    endDay = 30;
                    break;
                case "Julho":
                    month = 6;
                    endDay = 31;
                    break;
                case "Agosto":
                    month = 7;
                    endDay = 31;
                    break;
                case "Setembro":
                    month = 8;
                    endDay = 30;
                    break;
                case "Outubro":
                    month = 9;
                    endDay = 31;
                    break;
                case "Novembro":
                    month = 10;
                    endDay = 30;
                    break;
                case "Dezembro":
                    month = 11;
                    endDay = 31;
                    break;
            }
            return [month, endDay];

        },
        loadCalendar(month = 0, endDay = 31){
            const start = new Date(2024, month , 0);
            const end = new Date(2024, month , endDay);
            let step = 1;
            while (start <= end){
                let dt = new Date(start.setDate(start.getDate() + step));
                this.scheduleDate.push(dt.toLocaleString('pt-BR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                }))
            }
            return this.scheduleDate;
        },
        nextMonth(){
           this.scheduleDate.length = 0;
           this.navMonthIndex++;
           this.showMonth = this.scheduleMonth[this.navMonthIndex];
           const [month, endDay] = this.setPeriod();
           return this.loadCalendar(month, endDay);

        },
        previousMonth(){
           this.scheduleDate.length = 0;
           if (this.navMonthIndex === 0) {
                this.navMonthIndex = 0 ;
                return;
           }
           this.navMonthIndex--;
           this.showMonth = this.scheduleMonth[this.navMonthIndex];
           const [month, endDay] = this.setPeriod();
           return this.loadCalendar(month, endDay);
        },
        setBgColor(status){
            switch(status) {
                case "Y":
                    return "bg-success text-white";
                case "N":
                    return "bg-danger text-white";
                case "C":
                    return "bg-primary text-white";
                case "W":
                    return "bg-warning text-dark";
            }
        },
        autoCancelReservation(){
            axios.put('/api/reservation/auto-canceled')
            .then((response) => {

            })
            .catch(errors => console.log(errors))
        },
        updateStatus(){
            const Toast = this.$swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            this.$swal.fire({
                showCancelButton: true,
                html: `
                    <div class="d-flex flex-column align-items-center p-2">
                        <p>Editar status da reserva</p>
                        <select class="form-select" id="reser-status">
                            <option value="Y">Chegou</option>
                            <option value="C">Canelado</option>
                            <option value="N">Não chegou / não avisou</option>
                        </select>
                    </div>
                `
            })
            .then((result) => {
                if (result.isConfirmed) {
                    let reservation_id = document.getElementById('res-id').value;
                    let status = document.getElementById('reser-status').value;
                    return new Promise(resolve => {
                        axios.put(`/api/reservation/${reservation_id}/status/${status}`)
                        .then((response) => {
                            Toast.fire({
                            icon: "success",
                            title: response.data
                            });
                        })
                        .catch((errors) => {
                            Toast.fire({
                            icon: "error",
                            title: errors.response.data
                            });
                        })
                        .finally(() => {

                        })
                    })
                }
            })
        }
    },
    mounted(){
        this.loadCalendar();
        this.autoCancelReservation()
        getAuth().then(result => {
            this.auth.position_id = result.position_id;
        })
    }
}
</script>
<style scoped>
.schedule-day:hover {
    background-color: #e2e3e5;
    transition: .3s ease-in;
    padding: 12px;
}
</style>
