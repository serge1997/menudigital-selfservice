<template>
    <Button :label="`${ $t('operator.toolbar.two')}`" icon="pi pi-external-link" @click="visibleIventoryModal = true" />
    <Dialog v-model:visible="visibleIventoryModal" maximizable modal :header="` ${ $t('inventory.title') }`" :style="{ width: '75rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div class="col-md-8 m-auto mb-2">
            <div v-if="load" class="p-1 mb-3">
                <ProgressBar mode="indeterminate" style="height: 6px"/>
            </div>
            <div class="d-flex flex-column">
                <span class="fw-medium"><i style="font-size: 0.9rem" class="pi pi-filter"></i> {{ $t('inventory.filter_label') }}</span>
                <Dropdown @change="filtreInventory" v-model="department_id" class="w-100 mt-2" :options="filter" option-value="id" option-label="department" placeholder="selectione um departamento..."/>
            </div>
        </div>
        <DataTable :value="inventory" scrollable scrollHeight="flex">
            <Column field="prod_name" sortable style="width: 25%" exportHeader="Product Code" :header="`${ $t('inventory.dataTable.one') }`"></Column>
            <Column field="saldoinicial" sortable style="width: 25%" :header="`${ $t('inventory.dataTable.two') }`"></Column>
            <Column field="saida" sortable style="width: 25%" :header="`${ $t('inventory.dataTable.three') }`"></Column>
            <Column field="saldofinal" sortable style="width: 25%" :header="`${ $t('inventory.dataTable.four') }`"></Column>
        </DataTable>
    </Dialog>
</template>
<script>
import Dialog from "primevue/dialog";
import DataTable from "primevue/datatable";
import Button from "primevue/button";
import Column from "primevue/column";
import Dropdown from "primevue/dropdown";
import { randTime } from "../rand";
import ProgressBar from "primevue/progressbar";
export default {
    name : 'InventoryComponent',

    components: {
        Dialog,
        DataTable,
        Button,
        Column,
        Dropdown,
        ProgressBar
    },

    data(){
        return {
            inventory: null,
            visibleIventoryModal: false,
            filter: [
                {id: 1, department: 'Bar'},
                {id: 2, department: 'kitchen'}
            ],
            department_id: 1,
            load: false
        }
    },

    methods: {
        filtreInventory(){
            this.load = true
            return new Promise(resolve => {
               setTimeout(() => {
                    axios.get('/api/inventory-filter', {params: {department: this.department_id}})
                    .then((response) => {
                        console.log(response.data)
                        this.inventory = response.data
                    })
                    .catch(errors => console.log(errors))
                    .finally(this.load = false)
               }, randTime())
            })
        }
    },

    mounted(){
        axios.get('/api/inventory-filter', {params: {department: this.department_id}}).then((response) => {
            console.log(response.data)
            this.inventory = response.data
        }).catch((errors) => {
            console.log(errors)
        })
    }
}
</script>
