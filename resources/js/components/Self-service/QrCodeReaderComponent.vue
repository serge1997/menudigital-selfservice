<template>
  <div class="container">
    <div class="modal fade" id="orderConfirmqrcodeReaderModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-0 p-2">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="exampleModalLabel">Qr code reader</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" :value="customerName" id="customer-name" />
                    <input type="hidden" :value="customerQuantity" id="customer-quantity" />
                    <div class="w-100">
                        <h6>Scan your Qrcode to confirm your order !</h6>
                    </div>
                    <div class="w-100" id="confirm-order-qr-reader"></div>
                </div>
            </div>
        </div>
    </div>
  </div>
</template>
<script>
import Dialog from "primevue/dialog";
import Button from "primevue/button";
export default {
    name: 'GenerateQrCode',

    props: ['customerName', 'customerQuantity'],
    components: {
      Dialog,
      Button
    },

    data(){
        return {
          visibleQrcodeReader: false,
          domain: location.origin,
          confirmOrderData: {
                ped_tableNumber: localStorage.getItem('table'),
                qrcode_order_number: null,
                ped_customerName: null,
                ped_customer_quantity: null
            },
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
            let self = this;
            this.setDom(function() {
                function onScanSuccess(decodeText, decodeResult) {
                    let customer = document.getElementById('customer-name').value;
                    let quantity = document.getElementById('customer-quantity').value;
                    let readerDiv = document.getElementById('confirm-order-qr-reader');
                    self.confirmOrderData.qrcode_order_number = decodeText;
                    self.confirmOrderData.ped_customerName = customer;
                    self.confirmOrderData.ped_customer_quantity = quantity;
                    axios.post('/api/order', self.confirmOrderData).then((response) => {
                        //self.$router.push({name: 'SelfServiceIndex'});
                        self.$toast.success(response.data)
                        location.replace(`${self.domain}/self-service/home`);
                        htmlScanner.stop();
                    }).catch((errors) => {
                        if (errors.response.status === 500){
                            self.$swal.fire({
                                text: !errors.response.data.message ? errors.response.data : errors.response.data.message  ,
                                icon: "warning"
                            })
                        }
                    })
                }
                let htmlScanner = new Html5QrcodeScanner(
                    "confirm-order-qr-reader",
                    {fps: 10, qrbox: 250}
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
