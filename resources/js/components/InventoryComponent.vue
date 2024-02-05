<template>
    <Button label="Inventory" icon="pi pi-external-link" @click="visibleIventoryModal = true" />
    <Dialog v-model:visible="visibleIventoryModal" maximizable modal header="Product Inventory" :style="{ width: '75rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div class="col-md-8 m-auto">
            <label>Filtro por departamento: {{ department_id }}</label>
            <Dropdown v-model="department_id" class="w-100 mt-2" :options="filter" option-value="id" option-label="department" placeholder="selectione um departamento..."/>
        </div>
        <DataTable :value="inventory" scrollable scrollHeight="flex">
            <Column field="prod_name" sortable style="width: 25%" exportHeader="Product Code" header="inventory"></Column>
            <Column field="saldoinicial" sortable style="width: 25%" header="Saldo Inicial"></Column>
            <Column field="saldofinal" sortable style="width: 25%" header="Saldo Final"></Column>
        </DataTable>
    </Dialog>
</template>
<script>
import Dialog from "primevue/dialog";
import DataTable from "primevue/datatable";
import Button from "primevue/button";
import Column from "primevue/column";
import Dropdown from "primevue/dropdown";
export default {
    name : 'InventoryComponent',

    components: {
        Dialog,
        DataTable,
        Button,
        Column,
        Dropdown,
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
        }
    },

    mounted(){
        axios.get('/api/inventory').then((response) => {
            console.log(response.data)
            this.inventory = response.data
        }).catch((errors) => {
            console.log(errors)
        })
    }
}
</script>
