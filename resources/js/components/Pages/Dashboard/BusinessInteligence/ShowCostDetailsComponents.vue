<template>
    <div class="col-md-10">
        <Button @click="visibleCostDetailModal = true, $emit('getCostDetails')" icon="pi pi-eye" text/>
        <Dialog v-model:visible="visibleCostDetailModal" maximizable modal header="Detalhes dos produtos" :style="{ width: '75rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        </Dialog>
    </div>

</template>
<script>
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';

export default {
    name: 'ShowCostDetailsComponents',

    props: ['productsCostDetail'],

    components: {
        Dialog,
        Button,
        DataTable,
        Column
    },
    data(){
        return {
            visibleCostDetailModal: false
        }
    }
}
</script>