<template>
    <div class="container mt-4">
        <div v-for="reservation in reservations" class="row d-flex justify-content-center shadow-sm p-1 mb-3 rounded border">
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
                <div>
                    <Button @click="getReservation(reservation.id)" text icon="pi pi-pencil"/>
                </div>
                        <div>
                    <Button @click="$emit('deleteReservation', reservation.id)" icon="pi pi-trash" text class="text-danger"/>
                </div>
            </div>
        </div>
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
                                <Button icon="pi pi-plus" severity="success"/>
                                <input type="hidden" id="res-id" :value="reservation.id" />
                                <InputText type="number" :class="invalid" :value="reservation.person_quantity" id="res-person" class="w-25" placeholder="000"/>
                                <Button icon="pi pi-minus" severity="primary" />
                            </div>
                            <small class="text-danger" v-if="formErrMessage" v-for="person in formErrMessage.person_quantity" v-text="person"></small>
                        </div>
                        <div class="col-md-4 d-flex flex-column gap-2 align-items-center">
                            <div class="col-md-12 d-flex justify-content-center gap-2">
                                <label class="fw-medium">Date: </label>
                                <Calendar dateFormat="dd/mm/yy" :class="invalid" id="res-date" showIcon iconDisplay="input" v-model="reservationData.date_come_in" />
                            </div>
                            <small class="text-danger" v-if="formErrMessage" v-for="date_come_in in formErrMessage.date_come_in" v-text="date_come_in"></small>
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
                        <Dropdown v-model="reservationData.reser_canal" :class="invalid" option-value="canal" :options="reservationCanal" optionLabel="canal" placeholder="Select user function" class="w-100 md:w-14rem" />
                        <small class="text-danger" v-if="formErrMessage" v-for="reser_canal in formErrMessage.reser_canal" v-text="reser_canal"></small>
                    </div>
                    <div class="w-100 d-flex justify-content-center mt-3">
                        <Button @click.prevent="updateReservation" label="Confirmar reservação"/>
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
import Textarea from "primevue/textarea";
import Calendar from "primevue/calendar";
import Tag from "primevue/tag";
export default {
    name: 'ListReservationComponent',

    props: ['reservationCanal', 'reservations'],

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

    data(){
        return {
            visibleShowReservationModal: false,
            visibleEditionReservationModal: false,
            id: 2,

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
            formErrMessage: null
        }
    },

    methods: {
        /*async listAllReservation(){
            const reservationRespone = await axios.get('/api/reservation');
            this.reservations = await reservationRespone.data;

        },*/
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

            var hour = new Date(this.reservationData.hour);
            var date = new Date(this.reservationData.date_come_in);
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
        }
    },
    mounted(){
        let dt = '000';
        console.log(new Date(dt));
    }
}
</script>
