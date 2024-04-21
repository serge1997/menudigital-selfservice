<template>
    <div class="container-fluid p-0">
        <div class="row p-0">
            <div class="col-md-6 border shadow d-flex flex-column justify-content-center align-items-center gap-3">
                <div class="w-100">
                    <Button @click="this.$router.push({name: 'Menu'})" class="w-50" label="Start ordering" />
                </div>
                <div class="w-100">
                    <Button data-bs-toggle="modal" data-bs-target="#qrcodeReaderModal" class="w-50" label="See order" />
                </div>
            </div>
            <div class="col-md-6 p-0">
                <img class="w-100 h-100" src="/img/self-banner.jpg" alt="">
            </div>
        </div>
        <div class="modal fade" id="qrcodeReaderModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content rounded-0 p-2">
                        <div class="modal-header border-0">
                            <h5 class="modal-title" id="exampleModalLabel">Qr code reader</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="w-100">
                                <h6>Scan your Qrcode to see your order !</h6>
                            </div>
                            <div class="customer-order-list">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Quantité</th>
                                            <th>Prix</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in order">
                                            <td>{{ item.item_name }}</td>
                                            <td>{{ item.item_quantidade }}</td>
                                            <td>{{ item.item_price }}</td>
                                            <td>{{ item.item_price *  item.item_quantidade}}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Total: {{ total }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="w-100" id="customer-qr-reader">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <div class="col-md-12">
            <Dialog v-model:visible="visibleOrderQrcode" maximizable modal :header="`${ $t('waiterpage.modal.title')} `" :style="{ width: '75rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
                <h1>Hello world</h1>
            </Dialog>
        </div>
    </div>
</template>
<script>
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
export default {
    name: 'selfServiceIndex',

    components: {
        Button,
        Dialog
    },

    data() {
        return {
            visibleOrderQrcode: false,
            order: null,
            checkAlwreadySend: true,
            total: 0
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
            let qrcode_order_number = decodeText;
            if (self.checkAlwreadySend){
                self.checkAlwreadySend = false
                axios.get('/api/oder-qr-code/' + qrcode_order_number)
                .then(response => {

                    let reader_div = document.getElementById("customer-qr-reader");
                    let orderListDiv = document.getElementById('customer-order-list');
                    if (response.status == 200 && response.data) {
                        self.order = response.data
                        for (let item of self.order) {
                            self.total += Number(item.item_quantidade) * Number(item.item_price)
                        }
                        reader_div.remove();
                    }
                })
            }
          }
          function onScanFailure(error) {
             //console.warn(`Code scan error = ${error}`);
          }

          let htmlScanner = new Html5QrcodeScanner(
              "customer-qr-reader",
              {fps: 10, qrbox: 250}
          );

          htmlScanner.render(onScanSuccess, onScanFailure);
        })
      }
    },
    mounted(){
        this.mounteQrCodeScanner();
    }
}
</script>

<style scoped>
.row {
    width: 100%;
    display: flex;
    justify-content: center;
    margin: 0;
    padding: 0;
    height: 100vh;
    box-sizing: border-box;
    text-align: center;
}

#html5-qrcode-button-camera-start {
    padding: 6px;
    background-color: blueviolet;
}
</style>
