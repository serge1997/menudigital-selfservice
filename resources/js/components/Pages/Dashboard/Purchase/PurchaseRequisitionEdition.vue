<template>
    <div class="container">
        <Button @click="visibleEditionPurchaseModal= true" label="Editar requisição" icon="pi pi-plus"/>
        <Dialog v-model:visible="visibleEditionPurchaseModal" maximizable modal header="Edição requisição de compra" :style="{ width: '75rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="w-100">
                <h6>Lista dos itens</h6>
                <div class="col-md-12 d-flex flex-column align-items-center mt-3 p-3">
                    <div class="com-md-8 d-flex justify-content-start">
                        <p>Departamento requerente: <span class="fw-medium">Cozinha</span></p>
                    </div>
                    <div class="col-md-8 mb-2">
                        <label class="fw-medium" for="requisition-status">Status da requisição: </label>
                    </div>
                    <Dropdown v-model="requisitionData.status_id" id="requisition-status" class="col-md-8" :options="status" option-label="un" />
                </div>
                <div class="w-75 m-auto gap-2 d-flex flex-column">
                   <div class="w-100 d-flex justify-content-between alert alert-primary p-1" v-for="(val, index) in requisition">
                       <div class="d-flex align-items-center gap-2">
                           <Button v-if="!showEditInput[index]" @click=" showEditInput[index] = true " icon="pi pi-pencil" text/>
                           <Button v-if="showEditInput[index]" @click=" showEditInput[index] = false " style="color: red" icon="pi pi-trash" text/>
                           <Checkbox v-model="requisitionData.products_id" id="product-id" :value="val.un" />
                           <label for="ingredient1" class="ml-2 fw-medium"> {{ val.un }}</label>
                       </div>
                       <div class="w-100 d-flex justify-content-center">
                           <InputText class="col-md-4" v-if="showEditInput[index]" type="number" :value="val.quantidade" />
                       </div>
                       <div>
                            <Badge size="large" :value="val.quantidade" />
                       </div>
                   </div>
                    <div class="w-100 d-flex flex-column m-auto">
                        <label>Faz um descrição curta: </label>
                        <Textarea class="w-100 mt-1" placeholder="Observação..." />
                    </div>
                    <div class="w-100 mt-2 d-flex justify-content-end">
                        <Button label="confirmar a requisição de compra"/>
                    </div>
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

    data(){
        return {
            visibleEditionPurchaseModal: false,
            requisition: [
                { un: 'Coca', quantidade: 4 },
                { un: 'heineken', quantidade: 26},
                { un: 'Stella', quantidade: 24},
                { un: 'Cachaça', quantidade: 4 },
            ],
            status: [
                { un: 'pendente'},
                { un: 'Aprovado'},
                { un: 'Não aprovado'},
            ],
            showEditInput: [],
            requisitionData: {
                products_id: null,
                status_id: null
            }
        }
    }
}
</script>
