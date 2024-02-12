<template>
    <div class="container-fluid">
        <div class="col-md-10 m-auto py-2 d-flex">
            <Toolbar class="w-100">
                <template #start>
                    <div class="d-flex gap-2">
                        <Calendar v-model="dateFilter.start" showIcon placeholder="start"/>
                        <Calendar v-model="dateFilter.end" showIcon iconDisplay="input" placeholder="end"/>
                        <Button @click.prevent="getGeneralStat" icon="pi pi-filter"></Button>
                    </div>
                </template>
                <template #end>
                    <div>
                        <Button @click="$router.push({ name: 'OperadorPanel'})" label="Panel" data-bs-dismiss="modal" icon="pi pi-plus"/>
                    </div>
                </template>
            </Toolbar>
        </div>
        <div class="col-md-8 m-auto d-flex justify-content-between p-2">
            <div class="col-md-3 shadow p-2" id="score">
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
            <div class="col-md-3 shadow p-2" id="score">
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
                           <i v-if="monthlyComparaison" class="pi pi-caret-up" style="color: green; font-weight: 400; font-size: 1.1rem"></i>
                           <i v-else class="pi pi-caret-down" style="color: #e63958; font-weight: 400; font-size: 1.1rem"></i>
                       </div>
                       <small class="text-success px-1 fw-medium">+53%</small>
                   </div>
                </div>
            </div>
            <div class="col-md-3 shadow p-2" id="score">
                <div class="d-flex justify-content-between border-0 bg-white" id="score">
                    <div class="d-flex justify-content-center alert alert-danger py-0 rounded-circle px-2 py-2">
                        <i class="pi pi-dollar" style="font-size: 1.2rem; color: #e63958;"></i>
                    </div>
                </div>
                <div class="d-flex justify-content-between p-0 bg-white">
                    <div class="d-flex flex-column">
                        <p class="d-flex flex-column" v-for="sell in monthlySell.lastMonth">
                            <span class="fw-medium">{{ sell.total == null ? '00 ' + 'R$' : sell.total + ' R$'}}</span>
                            <small>Venda mes anetrior</small>
                        </p>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="d-flex justify-content-center align-items-center rounded-circle" :class="cardClass.cardClassLast">
                            <i v-if="!monthlyComparaison" class="pi pi-caret-up" style="color: green; font-weight: 400; font-size: 1.1rem"></i>
                            <i v-else class="pi pi-caret-down" style="color: #e63958; font-weight: 400; font-size: 1.1rem"></i>
                        </div>
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
            <div class="col-md-12">
                <DataTable v-model:filters="filters" ref="dt" :value="dataTable" selectionMode="single" dataKey="id" editMode="cell" @cell-edit-complete="onCellEditComplete"  filterDisplay="row"  paginator :rows="40" tableStyle="min-width: 50rem">
                    <div class="position-absolute" :class="{ 'place': placeh}"></div>
                    <template #header>
                        <div style="text-align: left">
                            <Button icon="pi pi-external-link" label="Export" @click="exportCSV($event)" />
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
import bb, {area, areaSpline, bar, line, donut} from 'billboard.js'
//import SideBarComponent from './SideBarComponent.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from "primevue/button";
import Toolbar from "primevue/toolbar";
import Calendar from "primevue/calendar";

export default {
    name: 'SellInteligence',

    components: {
        InputText,
        DataTable,
        Column,
        Button,
        Toolbar,
        Calendar
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
                end: null
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
            this.dateFilter.start == null ? this.dateFilter.start = new Date() : ''
            this.dateFilter.end == null ? this.dateFilter.end = new Date() : ''
            let start = this.dateFilter.start.toString();
            let end = this.dateFilter.end.toString();
            const params = [
                {'start': start.substr(0, 15),},
                {'end': end.substr(0, 15)}
            ]
            axios.get(`/api/bi/general-stat/${params[0].start}/${params[1].end}`).then((response) => {
                console.log("response")
                console.log(response)
                for (let data of response.data){
                    let sell = Number(data.total)
                    this.barchart.date.push(data.item_emissao);
                    this.barchart.sell.push(sell)
                }

                this.SetDoubleBarChart();
                this.get_type_waiter_dash(params[0].start, params[1].end)
            })
        },

        async get_type_waiter_dash(start, end){
            this.dateFilter.start == null ? this.dateFilter.start = new Date() : ''
            this.dateFilter.end == null ? this.dateFilter.end = new Date() : ''
            start = this.dateFilter.start.toString();
            end = this.dateFilter.end.toString();
            const params = [
                {'start': start.substr(0, 15),},
                {'end': end.substr(0, 15)}
            ]
            axios.get(`/api/bi/dash-type-waiter/${params[0].start}/${params[1].end}`).then((response) => {
                this.monthlySell.currentMonth = response.data.thisMonth;
                this.monthlySell.lastMonth = response.data.lastMonth;
                this.monthlySell.totalDay = response.data.totalDay
                this.dataTable = response.data.itemsCollection
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
                            console.log(this.type.drinks)
                            break;
                        case "VINHO":
                            this.type.wine += Number(typename.typevenda)
                            console.log(this.type.wine)
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
                this.donut()
                this.monthCompare(response.data)
            }).catch((errors) => {
                console.log(errors)
            })
        },

        //double bar chart
        SetDoubleBarChart(){
            var chart = bb.generate({
                data: {
                    x: "x",
                    columns: [
                        ["x", ...this.barchart.date],
                        ["sample", ...this.barchart.sell],
                    ],
                    type: bar(), // for ESM specify as: line()
                },
                axis: {
                    x: {
                        type: "timeseries",
                        tick: {
                            count: 4,
                            format: "%Y-%m-%d"
                        }
                    }
                },
                bindto: "#Chart"
            });
        },

        //donut chart
        donut(){
            var chart = bb.generate({
                data: {
                    columns: [
                        ["DRINKS", this.type.drinks],
                        ["PRINCIPAL", this.type.principal],
                        ["VINHO", this.type.wine],
                        ["FASTFOOD", this.type.fastFood]
                    ],
                    type: donut(), // for ESM specify as: donut()
                    onclick: function(d, i) {
                        console.log("onclick", d, i);
                    },
                    onover: function(d, i) {
                        console.log("onover", d, i);
                    },
                    onout: function(d, i) {
                        console.log("onout", d, i);
                    }
                },
                donut: {
                    title: "Food group KPI"
                },
                bindto: "#charDonut"
            })
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
                return this.monthlyComparaison = result
            }
        //    console.log(thisMonth + ' - ' + lastMonth);
        //    if (result){
        //        this.cardClass.cardClassThis = 'alert alert-success';
        //        this.cardClass.cardClassLast = 'alert alert-danger'
        //    }else{
        //        this.cardClass.cardClassThis = 'alert alert-danger';
        //        this.cardClass.cardClassLast = 'alert alert-success'
        //    }
        //    //result === false ? this.cardClass = 'alert alert-danger' : this.cardClass = 'alert alert-success'
        //    this.monthlyComparaison = result;
       }
    },

    mounted() {
        this.getGeneralStat();
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
</style>
