<template>
    <div class="container">
        <div class="modal fade" id="ModalCreateProduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-header p-2 text-capitalize shadow-lg rounded-3 text-white w-100">
                            <h6>create a new product </h6>
                            <p>product</p>
                        </div>
                        <div class="w-100 mt-4">
                            <span class="text-danger p-1" v-if="errMsg" v-for="msg in errMsg.prod_name" v-text="msg"></span>
                            <div class="input-group p-1">
                                <input type="text" class="form-control rounded-0 border border-secondary" placeholder="Name " v-model="product.prod_name">
                                <span class="px-2"></span>
                                <input type="text" class="form-control rounded-0 border border-secondary" placeholder="Description" v-model="product.prod_desc">
                            </div>
                            <div class="input-group mt-2 p-1">
                                <select name="" id="" class="form-select rounded-0 border-secondary" v-model="product.prod_unmed">
                                    <option selected value="">unidade de medida</option>
                                    <option value="bt">bottle</option>
                                    <option value="cl">cl</option>
                                    <option value="g">g</option>
                                    <option value="folha">folha</option>
                                </select>
                                <span class="px-2"></span>
                                <input type="text" class="form-control rounded-0 border border-secondary" placeholder="conteudo" v-model="product.prod_contain">
                            </div>
                            <span class="text-danger p-1" v-if="errMsg" v-for="msg in errMsg.prod_supplierID" v-text="msg"></span>
                            <div class="input-group mt-2 p-1">
                                <select name="" id="" class="form-select rounded-0 border-secondary" v-model="product.prod_supplierID">
                                    <option selected value="">Fornecedor</option>
                                    <option v-for="supplier in suppliers" :value="supplier.id">{{ supplier.sup_name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button @click="StoreProduct" type="button" class="btn bg-dark text-white rounded-0">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default{

    name: "CreateProductComponent",

    data(){
        return {
            product:{
                prod_desc: null,
                prod_name: null,
                prod_unmed: null,
                prod_contain: null,
                prod_supplierID: null
            },
            suppliers: null,
            errMsg: null
        }
    },
    methods: {
        StoreProduct(){
            axios.post("/api/product", this.product).then((response) => {
                    this.$toast.success(response.data)
                    this.product.prod_contain = "";
                    this.product.prod_desc = "";
                    this.product.prod_name = "";
                    this.product.prod_unmed = "";
                    this.product.prod_supplierID = ""
            }).catch((errors) => {
                this.errMsg = errors.response.data.errors
                this.$toast.error(errors.response.data)
            })
        }
    },

    mounted(){
        axios.get('/api/supplier').then((response) => {
            this.suppliers = response.data
            console.log(this.supplier)
        }).catch((errors) => {
            console.log(errors)
        })
    }
}
</script>
