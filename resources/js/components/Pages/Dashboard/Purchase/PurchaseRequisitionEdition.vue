<template>
    <div class="container">
        <!--<Button v-if="status_desc !== 'Recusado' && status_desc !== 'Aprovado'" @click="getRequisitionItens(id)"  icon="pi pi-pencil" text/> -->
        <Button @click="getRequisitionItens(id)"  icon="pi pi-pencil" text/>
        <Dialog v-model:visible="visibleEditionPurchaseModal" maximizable modal header="Edição requisição de compra" :style="{ width: '75rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="w-100">
                <h6>Lista dos itens</h6>
                <div class="col-md-12 d-flex flex-column align-items-center mt-3 p-3">
                    <small class="text-danger" v-if="errMsg" v-for="prducts_ids in errMsg.products_id" v-text="prducts_ids"></small>
                    <div class="com-md-8 d-flex justify-content-start">
                        <p>Departamento requerente: <span class="fw-medium text-uppercase">{{ department_name }}</span></p>
                    </div>
                    <div class="col-md-8 mb-2">
                        <label class="fw-medium" for="requisition-status">Status da requisição: </label>
                    </div>
                    <Dropdown v-model="requisitionData.status_id" option-value="id" id="requisition-status" class="col-md-8" :options="status" option-label="stat_desc" />
                   <div class="mt-2 d-flex justify-content-center align-items-center">
                       <div v-if="loadQuantity" class="spinner-grow" style="width: 2rem; height: 2rem;" role="status">
                           <span class="visually-hidden">Loading...</span>
                       </div>
                   </div>
                </div>
                <div class="w-75 m-auto gap-2 p-1 d-flex flex-column">
                   <div class="w-100 d-flex justify-content-between p-3 rounded border" v-for="(item, index) in itens" style="background-color: #f3f4f6">
                       <div class="col-md-7 d-flex justify-content-between align-items-center gap-1">
                           <div class="col-md-3 d-flex justify-content-start">
                               <input type="hidden" id="post-requisition-id" :value="item.id" />
                               <Button v-if="!showEditInput[index]" @click=" showEditInput[index] = true " icon="pi pi-pencil" text/>
                               <Button v-if="showEditInput[index]" @click=" showEditInput[index] = false " style="color: red" icon="pi pi-times" text/>
                               <Button @click="setRequisitionItemStatusToRejected(item.id, item.product_id)" style="color: red" icon="pi pi-trash" text/>
                           </div>
                           <div class="col-md-10 d-flex justify-content-start gap-2">
                                <div class="form-check">
                                    <input class="form-check-input product-id" type="checkbox" :value="item.product_id" />
                                    <label for="ingredient1" class="ml-2 fw-medium form-check-label">
                                        {{ item.prod_name }}
                                    </label>
                                </div>
                               <!-- <Checkbox @click="onSelected" v-model="requisitionData.products_id" class="product-id" :value="item.product_id" />
                               <label for="ingredient1" class="ml-2 fw-medium"> {{ item.prod_name }}</label> -->
                           </div>
                       </div>
                       <div class="col-md-3 d-flex justify-content-center">
                           <input type="hidden" :value="item.quantity" class="quantity-data"/>
                           <InputText :id="item.prod_name.replace(' ', '')" class="w-100 quantity-input" v-if="showEditInput[index]" type="number" :value="item.quantity" @blur="updateRequisitionProductQuantity(item.product_id, item.prod_name, item.id)" />
                       </div>
                       <div class="col-md-1">
                            <Badge :value="item.quantity" />
                       </div>
                   </div>
                    <div class="d-flex gap-2">
                        <input type="checkbox" @click="onSelectAll" id="select-all" v-model="requisitionData.selectAll"/>
                        <label>Selecionar todos</label>
                    </div>
                    <div class="w-100 d-flex flex-column m-auto mt-2">
                        <label>Faz um descrição curta: </label>
                        <Textarea v-model="requisitionData.observation" class="w-100 mt-1" placeholder="Observação..." />
                    </div>
                    <div v-if="requisitionData.status_id === paymentApproved" class="w-100 d-flex justify-content-between mt-2">
                        <div class="col-md-6 d-flex flex-column align-items-center justify-content-center gap-4">
                            <div @click="this.paymentSelectedStyle.isMoney =true; this.paymentSelectedStyle.isBank = false" class="card col-md-5 position-relative" :class="{paymentSelected : this.paymentSelectedStyle.isMoney}">
                                <div class="card-header">
                                    <h6>Dinheiro</h6>
                                </div>
                                <div class="card-body d-flex justify-content-center">
                                    <span><i class="pi pi-dollar" style="font-size: 2.5rem"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex flex-column align-items-center justify-content-center">
                            <div @click="this.paymentSelectedStyle.isBank = true; this.paymentSelectedStyle.isMoney =false;" class="card col-md-5 position-relative" :class="{paymentSelected : this.paymentSelectedStyle.isBank}">
                                <div class="card-header">
                                    <h6>Banco</h6>
                                </div>
                                <div class="card-body d-flex justify-content-center">
                                    <span><i class="pi pi-building" style="font-size: 2.5rem"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-100 d-flex justify-content-center">
                        <div class="w-50">
                            <div v-if="this.paymentSelectedStyle.isMoney && requisitionData.status_id === paymentApproved" class="w-100 d-flex justify-content-center">
                                <Dropdown class="col-md-12" option-value="Caixa" ></Dropdown>
                            </div>
                        </div>
                        <div class="w-50">
                            <div v-if="this.paymentSelectedStyle.isBank && requisitionData.status_id === paymentApproved" class="w-100 d-flex justify-content-start">
                                <Dropdown class="col-md-12" option-value="Caixa" ></Dropdown>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-100 d-flex justify-content-end">
                    <Button @click="confirmPurchaseRequisition" label="confirmar a requisição de compra"/>
                </div>
            </div>
        </Dialog>
    </div>
</template>
<script>
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import Button from "primevue/button";
import Checkbox from "primevue/checkbox";
import Badge from "primevue/badge";
import Textarea from "primevue/textarea";
export default {
    name: 'PurchaseRequisitionEdition',

    components: {
        Dialog,
        Dropdown,
        InputText,
        Button,
        Checkbox,
        Badge,
        Textarea
    },
    props: ['id', 'status_desc'],

    data(){
        return {
            visibleEditionPurchaseModal: false,
            itens: null,
            status: null,
            showEditInput: [],
            requisitionData: {
                requisition_id: null,
                products: [],
                products_id: [],
                confirm_quantity: null,
                status_id: null,
                observation: null,
                selectAll: false,
                getProducts: function(){
                    const data = this.selectAll ? this.products_id : this.products;
                    return data;
                }
            },
            department_name: null,
            paymentSelectedStyle: {
                isBank: false,
                isMoney: false
            },
            paymentApproved: 2,
            loadQuantity: false,
            errMsg: null,
            selectAll: false,
            isChecked: false,
        }
    },
    methods: {
        async getRequisitionItens(id) {
            const response = await axios.get(`/api/purchase-requisition/${id}`);
            this.itens = response.data.requisition_itens;
            this.status = response.data.requisition_status
            this.visibleEditionPurchaseModal = true;
            this.getDepartmentName();
        },
        getDepartmentName(){
           for (let department of this.itens){
               this.department_name = department.department_name;
           }
        },

        setRequisitionItemStatusToRejected(requisition_id, product_id){
            this.$swal.fire({
                text: "Quer rejeitar esse item",
                icon: 'question',
                showCancelButton: true,
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed){
                    axios.put('/api/purchase-requisition-item/rejected/' + requisition_id + '/' + product_id).then((response) => {
                        console.log(response.data);
                        this.$swal.fire({
                            text: response.data,
                            icon: "success"
                        })
                        return this.getRequisitionItens(requisition_id);
                    }).catch(errors => {
                        console.log(errors)
                    })
                }
            })
        },

        confirmPurchaseRequisition(){
            let input = document.querySelectorAll('.quantity-data');
            let product_ids = document.querySelectorAll('.product-id');
            if (this.checkAllSelected()){
                return this.$toast.error("Selecione todos os campos");
            }

            this.requisitionData.confirm_quantity = [].map.call(input, value => value.value);
            this.requisitionData.requisition_id = document.getElementById('post-requisition-id').value;
            this.requisitionData.products_id = [].map.call(product_ids, value => value.value);
            axios.post('/api/purchase-requisition/confirm', this.requisitionData).then((response) => {
                this.visibleEditionPurchaseModal = false;
                this.$swal.fire({
                    text: response.data,
                    icon: 'success'
                })
            }).catch((errors) => {
                console.log(errors)
                this.errMsg = errors.response.data.errors
            })
        },

        updateRequisitionProductQuantity(product_id, idValue, requisition_id){
            let dynamicID = idValue.replace(' ', '');
            let quantity = document.getElementById(`${dynamicID}`).value;
            const data = {
                "requisition_id": requisition_id,
                "product_id" : product_id,
                "quantity": quantity
            };
            this.loadQuantity = true;
            return new Promise(resolve => {
                setTimeout(() => {
                    axios.post('/api/purchase-requisition-product/quantity', data).then((response) => {
                        if (response && response.status === 200){
                            resolve(true);
                            this.$toast.success(response.data);
                            return this.getRequisitionItens(requisition_id);
                        }
                    }).catch((errors) => {
                        console.log(errors);
                    })
                    this.loadQuantity = false;
                }, 2000)
            })

        },
        onSelected(){ console.log("Ocurred")},

        onSelectAll(){
            this.isChecked = ! this.isChecked;
            this.selectAll = ! this.selectAll;
            let products = document.querySelectorAll(".product-id");
            if ( this.selectAll ) {
                Array.prototype.map.call(products, (product) => {
                    product.checked = true
                    product.checked ? this.requisitionData.products_id.push(product.value) : ''
                })
            }else{
                Array.prototype.map.call(products, (product) => {
                    product.checked = false
                    this.requisitionData.products_id.length = 0
                 })
            }
            console.log(this.requisitionData.products_id)

        },

        checkAllSelected(){
            let product_ids = document.querySelectorAll('.product-id');
            var isNotchecked;
            product_ids.forEach((product) => {
                if ( ! product.checked ) {
                    isNotchecked = true;
                }
            })
            return isNotchecked;
        }


    },
    mounted() {
    }
}
</script>
<style>
.paymentSelected {
    border: 1px #0275d8 solid;
    box-shadow: 1px 3px 2px rgba(145, 144, 144, 0.6);
}

</style>
