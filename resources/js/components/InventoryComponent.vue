<template>
    <Button label="Inventory" icon="pi pi-external-link" @click="visibleIventoryModal = true" />
    <Dialog v-model:visible="visibleIventoryModal" maximizable modal header="Product Inventory" :style="{ width: '75rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <DataTable :value="inventory">
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
export default {
    name : 'InventoryComponent',

    components: {
        Dialog,
        DataTable,
        Button,
        Column
    },

    data(){
        return {
            inventory: null,
            visibleIventoryModal: false
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
