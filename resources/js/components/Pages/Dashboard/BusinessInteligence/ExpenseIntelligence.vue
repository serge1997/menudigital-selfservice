<template>
    <div class="container-fluid">
        <div class="row d-flex p-4">
            <div class="col-lg-6 col-md-12 d-flex flex-column">
                <div class="shadow w-100 mt-2">
                    <div id="bar_expense"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <DataTable id="stock-table" :value="expenseData" selectionMode="single"  paginator :rows="10" tableStyle="min-width: 50rem">
                    <template #header>
                        <div class="d-flex justify-content-between">
                            <div style="text-align: left">
                                <Button icon="pi pi-external-link" label="Export" @click="downloadCsv" />
                            </div>
                            <div class="col-md-10 d-flex justify-content-end gap-3">
                                <div class="d-flex flex-column gap-2 col-md-3">
                                    <label>Produto | Item menu</label>
                                    <span class="p-input-icon-left">
                                        <InputText @input="getFiltersData" class="w-100" v-model="filtreParam.item" placeholder="produto, fornecedor" />
                                    </span>
                                </div>
                                <div class="d-flex flex-column gap-2 col-md-3">
                                    <label>Ano </label>
                                    <Dropdown @change="getFiltersData" class="w-100" :options="years" optionValue="year" optionLabel="year" placeholder="Selecione ano..." v-model="filtreParam.year" />
                                </div>
                                <div class="d-flex flex-column gap-2 col-md-3">
                                    <label>Mês </label>
                                    <Dropdown @change="getFiltersData" class="w-100" :options="monthData" optionValue="value" optionLabel="month" placeholder="Selecione mês..." v-model="filtreParam.month" />
                                </div>
                            </div>
                        </div>
                    </template>
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
import Dropdown from 'primevue/dropdown';
import bb, {bar, line} from 'billboard.js';
export default {
    name: 'ExpenseComponent',
    components: {
        DataTable,
        Column,
        Dropdown,
        InputText,
        Button
    },
    data(){
        return {
            expenseData: null,
            monthData: [
                {value: "Jan", month: "Janeiro"},
                {value: "Feb", month: "Fevrereiro"},
                {value: "Mar", month: "Março"},
                {value: "Apr", month: "Abril"},
                {value: "Mai", month: "Maio"},
                {value: "Jun", month: "Juno"},
                {value: "Jul", month: "Julho"},
                {value: "Aug", month: "Agosto"},
                {value: "Set", month: "Setembro"},
                {value: "Oct", month: "Octubro"},
                {value: "Nov", month: "Novembro"},
                {value: "Dec", month: "Dezembro"}
            ],
            years: [
                {year: "2024"},
                {year: "2025"},
                {year: "2026"},
                {year: "2027"},
                {year: "2028"},
                {year: "2029"},
                {year: "2030"}
            ],
            filtreParam: {
                item: null,
                month: null,
                year: null
            },
        }
    },
    methods: {
        async loadExpense(){
            const expenseResponse = await axios.get('/api/expense');
            this.expenseData = (await expenseResponse).data;
        },
        async getFiltersData(){
            const filterData = axios.get('/api/expense-filter/', {
                params: {year: this.filtreParam.year, month: this.filtreParam.month, item: this.filtreParam.item}
            });
            this.expenseData = (await filterData).data;
        },
        SetBarChart(){
            const xArray = ["Italy","France","Spain","USA","Argentina"];
            const yArray = [55, 49, 44, 24, 15];
            const data = [{
                x: xArray,
                y: yArray,
                type: "bar",
                orientation:"h",
                marker: {color:"rgba(0,0,255)"}
            }];
            Plotly.newPlot('bar_expense', data)
        },
    },
    mounted(){
        this.loadExpense();
        this.SetBarChart()
    }
}

</script>
