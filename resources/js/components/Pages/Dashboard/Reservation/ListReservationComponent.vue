<template>
    <div class="container mt-4">
        <div class="row d-flex justify-content-center shadow-sm p-1 mb-3">
            <div class="col-md-4 d-flex flex-column customer-info">
                <h6>Serge Gogo</h6>
                <p class="d-flex gap-2"><i class="pi pi-phone"></i><small>41 997182410 | </small><small>6</small><i class="pi pi-users"></i></p>
            </div>
            <div class="col-md-4 d-flex flex-column align-items-start customer-info">
                <h6 class="d-flex gap-2"><i class="pi pi-calendar"></i>26/01/2024</h6>
                <p class="d-flex gap-2"><i class="pi pi-clock"></i><small>14H</small></p>
            </div>
            <div class="col-md-4 d-flex justify-content-end align-items-center gap-2 customer-info">
                <div>
                    <Button @click="visibleShowReservationModal = true" icon="pi pi-eye"/>
                </div>
                <div>
                    <Button @click="$emit('ShowModelFromList')" icon="pi pi-pencil"/>
                </div>
                        <div>
                    <Button icon="pi pi-trash" text class="text-danger"/>
                </div>
            </div>
        </div>
        <div class="row">
            <Dialog v-model:visible="visibleShowReservationModal" maximizable modal header="Detalhes da reservação" :style="{ width: '75rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
                <div class="col-md-12 mt-3">
                    <div class="row show-header">
                        <div class="col-md-4 d-flex gap-2 shadow-sm p-2">
                            <p><i class="pi pi-calendar"></i></p>
                            <h6 class="fs-3">26/01/2024</h6>
                        </div>
                        <div class="col-md-4 d-flex gap-2 shadow-sm p-2">
                            <p><i class="pi pi-users"></i></p>
                            <h6 class="fs-3">6</h6>
                        </div>
                        <div class="col-md-4 d-flex gap-2 shadow-sm bg-primary text-white p-2">
                            <p><i class="pi pi-clock"></i></p>
                            <h6 class="fs-3">14H00</h6>
                        </div>
                    </div>
                    <div class="row show-body">
                        <div class="col-md-6 p-3">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Sobrenome: <span class="fw-medium">Serge Gogo</span></li>
                                <li class="list-group-item">Nome: <span class="fw-medium">Lotchi</span></li>
                                <li class="list-group-item">Celular: <span class="fw-medium">41 997182410 </span></li>
                                <li class="list-group-item">E-mail: <span class="fw-medium">serge@gmail.com</span></li>
                                <li class="list-group-item">Canal: <span class="fw-medium">Whatsapp</span></li>
                            </ul>
                        </div>
                        <div class="col-md-6 p-3">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Observação</h6>
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                        Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit
                                        esse cillum dolore eu fugiat nulla pariatur.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </Dialog>
        </div>
    </div>
</template>
<script>
import Button from "primevue/button";
import Dialog from "primevue/dialog";
export default {
    name: 'ListReservationComponent',

    components: {
        Button,
        Dialog
    },
    props: ['id'],

    data(){
        return {
            visibleShowReservationModal: false,

        }
    }
}
</script>
