<template>
  <div class="container">
    <Button label="Qr code" text @click="visibleQrcodeReader = true" />
    <Dialog v-model:visible="visibleQrcodeReader" maximizable modal :header="`${$t('stockModals.product_title')}`" :style="{ width: '75rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
      <div class="row">
        <div class="col-md-10 m-auto d-flex flex-column">
          <div class="w-100">
            <h3>Generate Order QR code</h3>
          </div>
          <div class="w-100" id="my-qr-reader">
          </div>
        </div>
      </div>
    </Dialog>
  </div>
</template>
<script>
import Dialog from "primevue/dialog";
import Button from "primevue/button";
export default {
    name: 'GenerateQrCode',

    components: {
      Dialog,
      Button
    },

    data(){
        return {
          visibleQrcodeReader: false
        }
    },

    methods: {
        setDom(callback) {
            if ( document.readyState === "complete" || document.readyState === "interactive") {
                setTimeout(callback, 1000);
            } else {
                document.addEventListener("DOMContentLoaded", callback);
            }
        },
        mounteQrCodeScanner(){
            this.setDom(function() {
                function onScanSuccess(decodeText, decodeResult) {
                    alert('Your qr code result is: ' + decodeText, decodeResult)
                }

                let htmlScanner = new Html5QrcodeScanner(
                    "my-qr-reader",
                    {fps: 15, qrbos: 350}
                );

                htmlScanner.render(onScanSuccess);
            })
        }
    },

    mounted() {
        this.mounteQrCodeScanner();
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

button {
    padding: 10px 20px;
    border: 1px solid #b2b2b2;
    outline: none;
    border-radius: 0.25em;
    color: white;
    font-size: 15px;
    cursor: pointer;
    margin-top: 15px;
    margin-bottom: 10px;
    background-color: #008000ad;
    transition: 0.3s background-color;
}
#my-qr-reader {
    padding: 20px !important;
    border: 1.5px solid #b2b2b2 !important;
    border-radius: 8px;
}

#my-qr-reader img[alt="Info icon"] {
    display: none;
}

#my-qr-reader img[alt="Camera based scan"] {
    width: 100px !important;
    height: 100px !important;
}
</style>
