<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <DataTable id="stock-table" :value="expenseData" selectionMode="single"  paginator :rows="10" tableStyle="min-width: 50rem">
                    <Column field="product_id" sortable style="width: 8%" exportHeader="Product Code" header="Product Code"></Column>
                    <Column field="prod_name" sortable style="width: 10%" header="Nome"></Column>
                    <Column field="quantity" sortable style="width: 10%;" header="Quantidade despesa"></Column>
                    <Column field="totalCost" sortable style="width: 10%;" header="Custo despesa"></Column>
                    <Column field="item_name" sortable style="width: 10%" header="menu item"></Column>
                    <Column field="user_name" sortable style="width: 10%;" header="usuario"></Column>
                    <Column field="emissao" sortable style="width: 10%;" header="Data"></Column>
                </DataTable>
            </div>
        </div>
    </div>
</template>
<script>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ToolBar from 'primevue/toolbar';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
export default {
    name: 'ExpenseComponent',
    components: {
        DataTable,
        Column
    },
    data(){
        return {
            expenseData: null,
        }
    },
    methods: {
        async loadExpense(){
            const expenseResponse = await axios.get('/api/expense');
            this.expenseData = (await expenseResponse).data;
        }
    },
    mounted(){
        this.loadExpense()
    }
}

</script>
