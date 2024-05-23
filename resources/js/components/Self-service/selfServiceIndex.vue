<template>
    <div class="container-fluid p-0">
        <div class="col-md-12">
            <div class="modal fade" id="AddMenu" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <MenuComponent />
                    </div>
                    <div class="modal-footer">
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row p-0">
            <div class="col-md-6 border shadow d-flex flex-column justify-content-between align-items-center gap-3">
                <div class="w-50 p-1" v-if="restaurant">
                    <img class="w-25 sidebar-logo" :src="'/img/logo/'+ restaurant.res_logo" alt="">
                    <h6 class="rest-name mt-2">{{ restaurant.rest_name }}</h6>
                </div>
                <div class="w-100 d-flex flex-column gap-3 align-items-center">
                    <Button style="background-color: #1e293b;" text @click="this.$router.push({name: 'Menu'})" class="w-50 text-white" label="Start ordering" />
                    <Button style="background-color: #f1f5f9; color: #1e293b; border: 1px solid #cbd5e1;" data-bs-toggle="modal" data-bs-target="#AddMenu" text class="w-50" label="Add" />
                    <Button style="background-color: #f1f5f9; color: #1e293b; border: 1px solid #cbd5e1;" data-bs-toggle="modal" data-bs-target="#qrcodeReaderModal" text class="w-50" label="See order" />
                </div>
                <div class="w-100"></div>
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
                            <button @click="closeCustomerQrCodeReaderModal" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="w-100 p-2 mb-3">
                                <h6>Scan your Qrcode to see your order !</h6>
                            </div>
                            <div class="customer-order-list">
                                <table class="table table-borderless isDisplayed">
                                    <thead class="">
                                        <tr>
                                            <th>Item</th>
                                            <th>Quantité</th>
                                            <th>Prix</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-medium">
                                        <tr v-for="item in order">
                                            <td style="background-color: #e2e3e5;" class="p-3">{{ item.item_name }}</td>
                                            <td class="p-3">{{ item.item_quantidade }}</td>
                                            <td class="p-3">{{ item.item_price }}</td>
                                            <td class="p-3">{{ item.item_price *  item.item_quantidade}}</td>
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

        </div>
    </div>
</template>
<script>
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import MenuComponent from './../Pages/MenuComponent.vue';
import { Html5Qrcode, Html5QrcodeScanner } from 'html5-qrcode';

export default {
    name: 'selfServiceIndex',

    components: {
        Button,
        Dialog,
        MenuComponent
    },

    data() {
        return {
            visibleOrderQrcode: false,
            order: null,
            checkAlwreadySend: true,
            showOrderDetails: false,
            total: 0,
            restaurant: null,
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
                    let table = document.querySelector('.table');
                    table.classList.remove('isDisplayed');
                    if (response.status == 200 && response.data) {
                        self.order = response.data
                        for (let item of self.order) {
                            self.total += Number(item.item_quantidade) * Number(item.item_price)
                        }
                        reader_div.style.display = "none";
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
      },

      closeCustomerQrCodeReaderModal(){
        this.checkAlwreadySend = true;
        let reader_div = document.getElementById("customer-qr-reader");
        let table = document.querySelector('.table');
        setTimeout(() => {
            reader_div.style.display = "block";
            table.classList.add('isDisplayed');
        }, 3000)

      }
    },
    mounted(){
        this.mounteQrCodeScanner();
        axios.get('/api/rest-info').then((response) => {
            this.restaurant = response.data
        }).catch((errors) => {
            console.log(errors)
        })

        setTimeout(() => {
            let hiddenElements = document.querySelector('span#html5-qrcode-anchor-scan-type-change')
            let reader = document.querySelector('#customer-qr-reader > span#html5-qrcode-anchor-scan-type-change')
            hiddenElements.classList.add('d-none')
            reader.classList.add('d-none')
        }, 2000)
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

.html5-qrcode-element > button {
    color: 'red';
    border: '1px solid red';
    background-color: blue;
}
.isDisplayed {
    display: none;
}

.rest-name {
    font-family: 'Borel';
}
</style>
