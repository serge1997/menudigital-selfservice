<template>
    <div class="container-fluid">
        <template id="swal-template">
            <swal-html>
                Loading
            </swal-html>
        </template>
        <div class="row">
            <div class="col-md-11 m-auto d-flex flex-column">
               <div class="w-100 d-flex flex-column">
                   <h3>Generate Order Code</h3>
                   <img class="w-100 img-thumbnail" :src="qrCode" alt="">
               </div>
                <div class="w-100 p-3">
                    <Button @click="generateQrCode" label="Generate" />
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Dropdown from "primevue/dropdown";
import Button from "primevue/button";
import { randTime } from "@/rand.js";
export default {
    name: 'GenerateQrCode',

    components: {
        Dropdown,
        Button
    },

    data(){
        return {
            code_: 'https://api.qrserver.com/v1/',
            cod: 'create-qr-code/?size=150x150&data=',
            qrCode: null
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
            axios.post('/api/qrcode-order-number', data)
                .then((response) => {
                  this.$toast.success(response.data)
                })
                .catch((error) => {
                  error.response.status === 500 ? this.$toast.error(error.response.data.message) : '';
                })
        }
    },

    mounted() {

    }
}
</script>
<style scoped>
.container-fluid {
    display: flex;
    justify-content: center;
    margin: 0;
    padding: 0;
    height: 100vh;
    box-sizing: border-box;
    text-align: center;
}


</style>
