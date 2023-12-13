<template>
    <div class="container">
        <div class="modal fade" id="ModalStockEntry" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-header p-2 text-capitalize shadow-lg rounded-3 text-white w-100">
                            <h6>Save a new product delivery</h6>
                            <p>Stock management</p>
                        </div>
                        <div class="w-100 p-2">
                            <span v-if="errMsg" v-for="msg in errMsg.productID" class="text-danger"> {{ msg }} </span><br>
                            <label for="">Produto : </label>
                            <div class="input-group p-1">
                                <select class="form-select border-secondary rounded-0" v-model="stockEntry.productID">
                                    <option value="" selected>produto</option>
                                    <option v-for="product in products" :value="product.id">{{ product.prod_name }}</option>
                                </select>
                            </div>
                            <div class="w-100 mt-2 p-1">
                                <div class="w-100 d-flex justify-content-between">
                                    <div class="w-50 d-flex justify-content-start">
                                        <span v-if="errMsg" v-for="msg in errMsg.quantity" class="text-danger"> {{ msg }} </span>
                                    </div>
                                    <div class="w-50 d-flex justify-content-start">
                                        <span v-if="errMsg" v-for="msg in errMsg.unitCost" class="text-danger"> {{ msg }} </span>
                                    </div>
                                </div>
                                <div class="input-group w-100">
                                    <input type="text" placeholder="Quantidade" class="form-control rounded-0 border-secondary" v-model="stockEntry.quantity">
                                    <span class="px-2"></span>
                                    <input type="text" placeholder="Custo unitario" class="form-control rounded-0 border-secondary" v-model="stockEntry.unitCost">
                                </div>
                            </div>
                            <div class="w-100 mt-2 p-1">
                                <span v-if="errMsg" v-for="msg in errMsg.supplierID"  v-text="msg" class="text-danger p-0"></span>
                                <label for="">Fornecedor : </label>
                                <div class="input-group w-100">
                                    <select class="form-select border-secondary rounded-0" v-model="stockEntry.supplierID">
                                        <option value="" selected>Confirm fornecedor</option>
                                        <option v-for="supplier in suppliers" :value="supplier.id">{{ supplier.sup_name }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button @click="StoreStockEntry" type="button" class="btn bg-dark text-white rounded-0">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'

export default{
    name: 'StockEntryComponent',

    data(){
        return {
            stockEntry: {
                productID: null,
                unitCost: null,
                quantity: null,
                supplierID: null
            },

            products: null,
            suppliers: null,
            errMsg: null
        }
    },

    methods: {
        StoreStockEntry(){
            axios.post('/api/stock-entry', this.stockEntry).then((response) => {
                if (response.data.status == 400){
                    this.$toast.error(response.data.msg)
                }else {
                    this.$toast.success(response.data)
                    this.stockEntry.productID = "";
                    this.stockEntry.quantity = "";
                    this.stockEntry.supplierID = "";
                    this.stockEntry.unitCost = ""
                }
            }).catch((errors) => {
                this.errMsg = errors.response.data.errors
            })
        }
    },

    mounted(){
        axios.get('/api/products').then((response) => {
            this.products = response.data.products
            this.suppliers = response.data.suppliers
        }).catch((errors) => {
            console.log(errors)
        })
    }
}

</script>
