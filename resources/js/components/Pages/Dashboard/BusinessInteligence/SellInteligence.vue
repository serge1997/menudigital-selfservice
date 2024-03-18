<template>
    <div class="container-fluid">
        <div class="col-md-10 m-auto d-flex">
            <Toolbar class="w-100">
                <template #start>
                    <div class="d-flex align-items-center gap-1">
                        <div class="d-flex flex-column">
                            <span class="fw-medium">
                                <span class="icon-filtro"><i style="font-size: 14px;" @click.prevent="limparFiltroData" class="pi pi-filter-slash" title="Limpar filtro"></i></span>
                                Inicio
                            </span>
                            <Calendar date-format="dd/mm/yy" v-model="dateFilter.start" showIcon placeholder="start"/>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fw-medium">Fim</span>
                            <Calendar date-format="dd/mm/yy" v-model="dateFilter.end" showIcon iconDisplay="input" placeholder="end"/>
                        </div>
                    </div>
                </template>
                <template #center>
                    <div class="d-flex gap-2 align-items-center">
                        <div class="d-flex flex-column">
                            <span class="d-flex align-items-center">
                                <span class="fw-medium">
                                    <span class="icon-filtro"><i style="font-size: 14px;" @click.prevent="limparFiltroMenu" class="pi pi-filter-slash" title="Limpar filtro"></i></span>
                                    Menu
                                </span>
                            </span>
                            <Dropdown style="width: 15rem;" :options="menuItems" optionValue="id" optionLabel="item_name" placeholder="Selecione item do menu..." v-model="dateFilter.item"/>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="d-flex align-items-center">
                                <span class="fw-medium">
                                    <span class="icon-filtro"><i style="font-size: 14px;" @click.prevent="limparFiltroColaborador" class="pi pi-filter-slash" title="Limpar filtro"></i></span>
                                    Colaborador
                                </span>
                            </span>
                            <Dropdown style="width: 15rem;" :options="users" optionValue="id" optionLabel="name" placeholder="Selecione colaborador..." v-model="dateFilter.user"/>
                        </div>
                    </div>
                </template>
                <template #end>
                    <div class="d-flex align-items-center">
                        <Button @click.prevent="getGeneralStat" icon="pi pi-filter" class="border-bottom" text title="Filtro"></Button>
                        <Button @click.prevent="limparFiltros" icon="pi pi-filter-slash" class="border-bottom" text title="Limpar filtro"></Button>
                    </div>
                </template>
            </Toolbar>
        </div>
        <div class="row m-auto mt-2 d-flex justify-content-center p-2 gap-4">
            <div class="col-md-2  border p-2 bg-white shadow-sm" id="score">
                <div class="d-flex justify-content-between border-0 bg-white" id="score">
                    <div class="d-flex justify-content-center alert alert-warning py-0 rounded-circle px-2 py-2">
                        <i class="pi pi-dollar" style="font-size: 1.2rem; color: #f0ad4e;"></i>
                    </div>
                </div>
                <div class="d-flex justify-content-between p-0 bg-white">
                    <div class="d-flex flex-column">
                        <p class="d-flex flex-column p-0" v-for="sell in monthlySell.totalDay">
                            <span class="fw-medium">{{ sell.totalDay == null ? '00 ' + 'R$' : sell.totalDay + ' R$' }}</span>
                            <small>Venda hoje</small>
                        </p>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="pi pi-chart-line" style="color: #e63958; font-weight: 400; font-size: 1.3rem"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-2 border shadow-sm p-2 bg-white" id="score">
                <div class="d-flex justify-content-between border-0 bg-white" id="score">
                    <div class="d-flex justify-content-center alert alert-primary py-0 rounded-circle px-2 py-2">
                        <i class="pi pi-dollar" style="font-size: 1.2rem; color: #0275d8;"></i>
                    </div>
                </div>
                <div class="d-flex justify-content-between p-0 bg-white">
                    <div class="d-flex flex-column">
                        <p class="d-flex flex-column p-0" v-for="sell in monthlySell.currentMonth">
                            <span class="fw-medium">{{ sell.total == null ? '00 ' + 'R$' : sell.total + ' R$' }}</span>
                            <small>Venda mes atual</small>
                        </p>
                    </div>
                   <div class="d-flex justify-content-center align-items-center">
                       <div class="d-flex justify-content-center align-items-center rounded-circle" :class="cardClass.cardClassThis">
                           <i v-if="!monthlyComparaison" class="pi pi-caret-up" style="color: green; font-weight: 400; font-size: 1.1rem"></i>
                           <i v-else class="pi pi-caret-down" style="color: #e63958; font-weight: 400; font-size: 1.1rem"></i>
                       </div>
                       <small class="text-success px-1 fw-medium">+53%</small>
                   </div>
                </div>
            </div>
            <div class="col-md-2 border shadow-sm p-2 bg-white" id="score">
                <div class="d-flex justify-content-between border-0 bg-white" id="score">
                    <div class="d-flex justify-content-center alert alert-danger py-0 rounded-circle px-2 py-2">
                        <i class="pi pi-dollar" style="font-size: 1.2rem; color: #e63958;"></i>
                    </div>
                </div>
                <div class="d-flex justify-content-between p-0 bg-white">
                    <div class="d-flex flex-column">
                        <p class="d-flex flex-column" v-for="sell in monthlySell.lastMonth">
                            <span class="fw-medium">{{ sell.total == null ? '00 ' + 'R$' : sell.total + ' R$'}}</span>
                            <small>Venda mes anterior</small>
                        </p>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="d-flex justify-content-center align-items-center rounded-circle" :class="cardClass.cardClassLast">
                            <i v-if="monthlyComparaison" class="pi pi-caret-up" style="color: green; font-weight: 400; font-size: 1.1rem"></i>
                            <i v-else class="pi pi-caret-down" style="color: #e63958; font-weight: 400; font-size: 1.1rem"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 border shadow-sm bg-white p-2" id="score">
                <div class="d-flex justify-content-between border-0 bg-white" id="score">
                    <div class="d-flex justify-content-center alert alert-warning py-0 rounded-circle px-2 py-2">
                        <i class="pi pi-dollar" style="font-size: 1.2rem; color: #f0ad4e;"></i>
                    </div>
                </div>
                <div class="d-flex justify-content-between p-0 bg-white">
                    <div class="d-flex flex-column">
                        <span class="fw-medium">{{ couverts == null ? '00 ' : couverts }}</span>
                        <small>Couverts</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="pi pi-users" style="color: #e63958; font-weight: 400; font-size: 1.3rem"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row d-flex p-4">
            <div class="col-lg-6 col-md-12 d-flex flex-column">
                <div class="w-100 shadow">
                    <div id="Chart"></div>
                </div>
                <div class="shadow w-100 mt-2">
                    <div id="bar"></div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 d-flex flex-column">
                <div class="row d-flex jusify-content-between">
                    <div id="charDonut"></div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-lg-7 col-md-10 shadow" id="waiterChart"></div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <DataTable v-model:filters="filters" ref="dt" :value="dataTable" selectionMode="single" dataKey="id" editMode="cell" @cell-edit-complete="onCellEditComplete"  filterDisplay="row"  paginator :rows="10" tableStyle="min-width: 50rem">
                    <div class="position-absolute" :class="{ 'place': placeh}"></div>
                    <template #header>
                        <div style="text-align: left">
                            <Button icon="pi pi-external-link" label="Export" @click="downloadCsv" />
                        </div>
                    </template>
                    <Column field="item_emissao" sortable style="width: 25%" exportHeader="Product Code" header="Date"></Column>
                    <Column field="item_name" sortable style="width: 25%" header="Meal name"></Column>
                    <Column field="quantidade" sortable style="width: 15%" header="Quantity"></Column>
                    <Column field="venda" sortable style="width: 15%" header="Sell"></Column>
                    <Column field="name" sortable style="width: 25%" header="Waiter"></Column>
                </DataTable>
            </div>
        </div>
    </div>
</template>
<script>
import InputText from 'primevue/inputtext';
import bb, {area, areaSpline, bar, line, donut} from 'billboard.js';
import Dropdown from 'primevue/dropdown';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from "primevue/button";
import Toolbar from "primevue/toolbar";
import Calendar from "primevue/calendar";
import { exportCSV } from './../csv.js';

export default {
    name: 'SellInteligence',

    components: {
        InputText,
        DataTable,
        Column,
        Button,
        Toolbar,
        Calendar,
        Dropdown
    },

    data() {

        return {
            waiterSell: [],
            waiterAvg: [],
            waiterName: [],
            itemsCollection: null,
            typesCollection: [],
            typeName: [],
            dataTable: null,
            couverts: null,
            type:{
                starter: 0,
                principal: 0,
                drinks: 0,
                dessert: 0,
                def: 0,
                wine: 0,
                fastFood: 0,
            },
            barchart:{
                date: [],
                sell: []
            },
            dateFilter: {
                start: null,
                end: null,
                user: null,
                item: null
            },
            monthlySell:{
                currentMonth: null,
                lastMonth: null,
                totalDay: null
            },
            monthlyComparaison: null,
            cardClass: {
                cardClassThis: null,
                cardClassLast: null
            },
            users: null,
            menuItems: null,
            chart: {
                waiterSell: null,
            }
        }
    },

    methods: {
        getGeneralStat() {
            this.barchart.date = [];
            this.barchart.sell = [];
            this.typesCollection = [];
            this.type.starter = 0;
            this.type.principal = 0;
            this.type.wine = 0;
            this.type.dessert = 0;
            this.type.drinks = 0;
            axios.get('/api/bi/general-stat', {params: {start: this.dateFilter.start, end: this.dateFilter.end, user: this.dateFilter.user, item: this.dateFilter.item}})
            .then((response) => {
                console.log("response")
                console.log(response)
                for (let data of response.data){
                    let sell = Number(data.total)
                    this.barchart.date.push(data.item_emissao);
                    this.barchart.sell.push(sell)
                }

                this.SetDoubleBarChart();
                this.get_type_waiter_dash()
                this.waiterChart()
            })
        },

        async get_type_waiter_dash(){
            this.type.fastFood = null;
            this.type.dessert = null;
            this.type.principal = null;
            this.type.wine = null;
            this.type.starter = null;
            axios.get('/api/bi/dash-type-waiter', {params: {start: this.dateFilter.start, end: this.dateFilter.end, user: this.dateFilter.user, item: this.dateFilter.item}})
            .then((response) => {
                this.monthlySell.currentMonth = response.data.thisMonth;
                this.monthlySell.lastMonth = response.data.lastMonth;
                this.monthlySell.totalDay = response.data.totalDay;
                this.dataTable = response.data.itemsCollection;
                this.couverts = response.data.couverts;
                this.chart.waiterSell = response.data.waiter;
                for (let typename of response.data.type){
                    if (this.typesCollection.indexOf(typename.type) === -1){
                         this.typesCollection.push(typename.type)
                    }
                    switch(typename.type) {
                        case "ENTRADA":
                            this.type.starter += Number(typename.typevenda);
                            break;
                        case "PRINCIPAL":
                            this.type.principal += Number(typename.typevenda);
                            break;
                        case "DRINKS":
                            this.type.drinks += Number(typename.typevenda)
                            break;
                        case "VINHO":
                            this.type.wine += Number(typename.typevenda)
                            break;
                        case "SOBREMESA":
                            this.type.dessert += Number(typename.typevenda);
                            break;
                        case "FASTFOOD":
                            this.type.fastFood += Number(typename.typevenda);
                            break;
                        default:
                            this.type.def += typename.typevenda;
                    }
                }
                this.waiterChart()
                this.donut()
                this.monthCompare(response.data)
            }).catch((errors) => {
                console.log(errors)
            })
        },

        //double bar chart
        SetDoubleBarChart(){
            const data = [{
                x: this.barchart.date,
                y: this.barchart.sell,
                type: "bar",
                orientation:"v",
                marker: {color:"rgba(0,0,255)"}
            }];
            const layout = {title: "Venda por dia"};
            Plotly.newPlot('Chart', data, layout)
        },
        //donut chart
        donut(){

            const data = [{
                values: [this.type.drinks, this.type.principal, this.type.wine, this.type.fastFood],
                labels: ["DRINKS", "PRINCIPAL", "VINHO", "FASTFOOD"],
                hole: .4,
                hoverinfo: 'label+percent',
                textinfo: 'label+percent',
                type: 'pie'
            }]
            const layout = {title: "Venda por tipo", annotations: [{showarrow: false, text: 'TP'}]}
            Plotly.newPlot('charDonut', data, layout)
        },
        waiterChart(){
            if (this.chart.waiterSell !== null){
                var waiter = [];
                var sell = [];
                for (let obj of this.chart.waiterSell){
                    waiter.push(obj.name);
                    sell.push(Number(obj.venda))
                }

                const data = [{
                    x: sell,
                    y: waiter,
                    type: "bar",
                    orientation:"h",
                    marker: {color:"rgba(0,0,255)"}
                }];
            const layout = {title: "Venda por Garçom"};
            Plotly.newPlot('waiterChart', data, layout);
            }
        },
       monthCompare(data){
            for (let i = 0; i <= data.lastMonth.length; i++){
                console.log(data.lastMonth[i].total)
                console.log(data.thisMonth[i].total)

                if (data.lastMonth[i].total > data.thisMonth[i].total){
                    this.monthlyComparaison = true
                }else{
                    this.monthlyComparaison = false;
                }
            }
       },

       async getUsers(){
            const userResponse = await axios.get('/api/users');
            this.users = await userResponse.data
       },

       async getMenuItems(){
            const menuResponse = await this.axios.get('/api/menu-items');
            this.menuItems = await menuResponse.data;

       },
       limparFiltros(){
        this.dateFilter.user = null;
        this.dateFilter.item = null;
        this.dateFilter.start = null;
        this.dateFilter.end = null;
        return this.getGeneralStat();
       },
       limparFiltroMenu(){
        this.dateFilter.item = null;
        return this.getGeneralStat();
       },
       limparFiltroColaborador(){
        this.dateFilter.user = null;
        return this.getGeneralStat();
       },
       limparFiltroData(){
        this.dateFilter.start = null;
        this.dateFilter.end = null;
        return this.getGeneralStat();
       },

       resetVariable(){
        this.type.fastFood = null;
        this.type.dessert = null;
        this.type.principal = null;
        this.type.wine = null;
        this.type.starter = null;
       },
       downloadCsv(){
        exportCSV(this.dataTable);
       }
    },

    created(){
        // let start = new Date();
        // let end = new Date();
        // this.dateFilter.start = new Date();
        // this.dateFilter.end = new Date()
        // this.dateFilter.start = start.toLocaleDateString('pt-BR', {
        //     year: 'numeric',
        //     month: '2-digit',
        //     day: '2-digit',
        // })
        // this.dateFilter.end = start.toLocaleDateString('pt-BR')
    },
    mounted() {
        this.getGeneralStat();
        this.getUsers();
        this.getMenuItems();
        //this.monthCompare();
        //this.get_type_waiter_dash()
        //this.donut()
    }

}

</script>
<style>
.waiter-card {
    background-color: #f7f7f7;
    transition: .3s ease-in;
    border-radius: 16px;
    cursor: pointer;
}

#score {
    background-color: #f7f7f7;
}

.waiter-card:hover{
    background-color: #fff;
}

.waiter-ranking {
    height: 300px;
    overflow: scroll;
    background-color: #cccccc5f;
}

*{
    scrollbar-width: auto;
    scrollbar-color: #333 #ff00;
}

::-webkit-scrollbar-track {
    background-color: #fff;
}

*::-webkit-scrollbar {
    width: 10px;
  }

  *::-webkit-scrollbar-thumb {
    background-color: #333;
    border-radius: 10px;
    border: 3px solid #ffffff;
  }

  .position-fixed {
    left: 0;
  }

  #table {
    height: 290px;
    overflow: scroll;
  }
  .icon-filtro{
    cursor: pointer
  }
</style>
