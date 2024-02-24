<template>
    <Button @click="visibleDevolutionModal = true" icon="pi pi-arrow-right-arrow-left" label="Devolution" text/>
    <div class="container">
        <Dialog v-model:visible="visibleDevolutionModal" maximizable modal header="Devolução de compra" :style="{ width: '85rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="accordion accordion_flush" id="accordionExample">
                <div v-for="devolution in devolutions"  @click="getDevolutionItems(devolution.id)" class="accordion-item mb-4">
                    <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button d-flex gap-3" type="button" data-bs-toggle="collapse" :data-bs-target="`#collapse-${devolution.id}`" aria-expanded="false" aria-controls="collapseOne">
                        <span> <i class="pi pi-hashtag"></i> Requisition code: {{ devolution.requisition_code }}</span>
                        <span><i class="pi pi-user"></i> {{ devolution.user_name }} </span>
                        <span> <i class="pi pi-box"></i> Department name: {{ devolution.department_name }}</span>
                        <span> <i class="pi pi-calendar"></i> Devolution date: {{ devolution.devolution_date }}</span>
                    </button>
                    </h2>
                    <div :id="`collapse-${devolution.id}`" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div v-if="loadItems" class="col-md-10 m-auto">
                                <p class="text-center">Carregando...</p>
                                <ProgressBar mode="indeterminate" style="height: 6px;" />
                            </div>
                            <table v-if="devolutionItems" class="table caption-top table-borderless">
                                <caption>List of products</caption>
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nome produto</th>
                                        <th scope="col">Quantidade</th>
                                        <th scope="col">Custo</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in devolutionItems">
                                        <th scope="row">{{ item.productID }}</th>
                                        <td>{{ item.prod_name }}</td>
                                        <td>{{ item.quantity }}</td>
                                        <td>{{ item.unitCost }}</td>
                                        <td>{{ item.totalCost }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </Dialog>
    </div>
</template>
<script>
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import Accordion from 'primevue/accordion';
import AccordionTab from 'primevue/accordiontab';
import ProgressBar from 'primevue/progressbar';
import { randTime } from '../rand';
export default {
    name: 'DeliveryDevolutionComponent',

    components: {
        Dialog,
        Button,
        Accordion,
        AccordionTab,
        ProgressBar
    },
    data(){
        return {
            visibleDevolutionModal: false,
            devolutions: null,
            devolutionItems: null,
            loadItems: false,
        }
    },
    methods: {
        loadDevolution(){
            return new Promise(resolve => {
                this.axios.get('/api/stock-devolution').then((response) => {
                    this.devolutions = response.data;
                    resolve(true);
                })
                .catch(errors => console.log(errors));
            })
        },
        getDevolutionItems(requisition_id){
           this.loadItems = true;
           this.devolutionItems = null;
           return new Promise(resolve => {
                setTimeout(() => {
                    axios.get('/api/stock-devolution/items/' + requisition_id)
                    .then((response) => {
                        this.devolutionItems = response.data;
                        resolve(true)
                    })
                    .catch(errors => console.log(errors))
                    .finally(() => {
                        this.loadItems = false;
                    })
                }, randTime())
           })
        }
    },
    mounted(){
        this.loadDevolution();
    }
}
</script>
<style scoped>


</style>
