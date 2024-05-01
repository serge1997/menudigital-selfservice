<template>
    <SideBarComponent />
    <div class="container-fluid">
        <template id="swal-template">
            <swal-html>
                Loading
            </swal-html>
        </template>
        <div class="col-md-8 m-auto p-4">
            <div class="col-md-6 m-auto d-flex flex-column">
               <div class="w-100 d-flex flex-column p-4">
                   <h3>Generate Order Code</h3>
                   <img class="w-25 img-thumbnail m-auto" :src="qrCode" alt="">
               </div>
                <div class="w-100 p-3">
                    <Button @click="generateQrCode" label="Generate" />
                </div>
            </div>
        </div>
        <div class="col-md-10 m-auto">
            <div class="w-100">
                <table class="table table-borderless table-hover">
                   <thead>
                        <tr>
                            <th>Numero</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                   </thead>
                   <tbody>
                        <tr v-for="qr in qrCodeList">
                            <td>{{ qr.qrcode_order_number }}</td>
                            <td><img style="width: 80px;" class="img-thumbnail" :src="`${code_}${cod}${qr.qrcode_order_number}`" alt="qr code"></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a class="nav-link" target="_blank" :href="`${code_}create-qr-code/?size=350x350&data=${qr.qrcode_order_number}`">
                                        <i class="pi pi-eye"></i>
                                    </a>
                                    <span class="text-primary" v-tooltip="'Pour imprimer cliquer sur icon oeil ensuite ctrl + p'">
                                        <i class="pi pi-info-circle"></i>
                                    </span>
                                </div>
                            </td>
                        </tr>
                   </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
import Dropdown from "primevue/dropdown";
import Button from "primevue/button";
import { randTime } from "@/rand.js";
import SideBarComponent from "../Pages/Dashboard/SideBarComponent.vue";
import { Html5Qrcode, Html5QrcodeScanner } from 'html5-qrcode';
export default {
    name: 'GenerateQrCode',

    components: {
        Dropdown,
        Button,
        SideBarComponent
    },

    data(){
        return {
            code_: 'https://api.qrserver.com/v1/',
            cod: 'create-qr-code/?size=150x150&data=',
            qrCode: null,
            qrCodeList: null
        }
    },

    methods: {
      loadEffect(){
        this.$swal.fire({
          template: "#swal-template",
          html:`
                   <div id="swal-load" style="height: 6rem;">
                       <div style="width: 3rem; height: 3rem; padding: 12px;" class="spinner-border" role="status">
                        </div>
                        <br>
                         <span class="">Aguarde...</span>
                   </div>
          `,
          showConfirmButton: false
        })
        setTimeout(() => {
          let div = document.querySelector('.swal2-container');
          div.remove()
        }, randTime())
      },
        generateQrCode() {
            this.loadEffect();
            const orderNumber = (Math.random() * 100).toFixed(0);
            this.qrCode = `${this.code_}${this.cod}${orderNumber}`;
            const data = { qrcode_order_number: orderNumber};
            setTimeout(() => {
                axios.post('/api/qrcode-order-number', data)
                .then((response) => {
                    this.$toast.success(response.data)
                })
                .catch((error) => {
                    error.response.status === 500 ? this.$toast.error(error.response.data.message) : '';
                })
            }, randTime() + 100)
        },

        listAll(){
            this.axios.get('/api/qrcode-order-number')
            .then((response) => {
                this.qrCodeList = response.data
                console.log(this.qrCodeList);
            })
            .catch(error => console.log(error))
        }
    },

    mounted() {
        this.listAll()
    }
}
</script>
<style scoped>
.container-fluid {
    display: flex;
    flex-direction: column;
    justify-content: center;
    margin: 0;
    padding: 0;
    height: 100vh;
    box-sizing: border-box;
    text-align: center;
}


</style>
