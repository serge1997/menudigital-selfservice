<template>
    <div class="container">
        <div class="modal fade" id="ModalSupplier" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title text-capitalize" id="exampleModalLabel">Save a new supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="w-100 p-2">
                            <div class="w-50 text-wrap m-auto">
                                <small>
                                    Cadastre um novo fornecedor
                                </small>
                            </div>
                        </div>
                        <div class="w-100">
                            <span class="p-1 text-danger" v-if="errMsg" v-for="msg in errMsg.sup_name" v-text="msg"></span>
                            <div class="input-group p-1">
                                <input type="text" class="form-control border-secondary rounded-0" placeholder="Supplier name" v-model="supplier.sup_name">
                                <span class="px-2"></span>
                                <input type="text" class="form-control border-secondary rounded-0" placeholder="CPF / CNPJ" v-model="supplier.sup_personID">
                            </div>
                            <span class="p-1 text-danger" v-if="errMsg" v-for="msg in errMsg.sup_tel" v-text="msg"></span>
                            <div class="input-group p-1 mt-2">
                                <input type="text" placeholder="Celular" class="form-control rounded-0 border-secondary" v-model="supplier.sup_tel">
                                <span class="px-2"></span>
                                <input type="text" placeholder="Bairro" class="form-control rounded-0 border-secondary" v-model="supplier.sup_neighborhood">
                            </div>
                            <div class="input-group p-1 mt-2">
                                <input type="text" placeholder="E-mail" class="form-control rounded-0 border-secondary" v-model="supplier.sup_email">
                            </div>
                            <div class="input-group p-1 mt-2">
                                <input type="text" placeholder="Cidade" class="form-control rounded-0 border-secondary" v-model="supplier.sup_city">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button @click="StoreSupplier" type="button" class="btn btn-primary rounded-0">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default{
    name: 'SupplierComponent',

    data() {
        return {
            supplier: {
                sup_name: null,
                sup_personID: null,
                sup_tel: null,
                sup_city: null,
                sup_neighborhood: null,
                sup_email: null
            },
            errMsg: null
        }
    },
    methods: {
        StoreSupplier(){
            axios.post('/api/supplier', this.supplier).then((response) => {
                if (response.data.status == 200){
                    this.$toast.success(response.data.msg_success)
                    this.supplier.sup_city = "";
                    this.supplier.sup_email = "";
                    this.supplier.sup_name = "";
                    this.sup_neighborhood = "";
                    this.supplier.sup_tel = "";
                    this.supplier.sup_personID = "";
                }else {
                    this.$toast.success(response.data.msg_error)
                }
            }).catch((errors) => {
                console.log(errors)
                this.errMsg = errors.response.data.errors
            })
        }
    }
}

</script>