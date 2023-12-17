<template>
    <div class="container-fluid">
        <div class="position-fixed z-3">
            <SideBarComponent/>
        </div>
        <div class="col-md-8 m-auto d-flex justify-content-between p-2">
            <div class="card col-2">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between">
                        <h6 class="text-center">Venda do dia</h6>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="#d9534f" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-arrow-up-right"><line x1="7" y1="17" x2="17" y2="7"></line>
                                <polyline points="7 7 17 7 17 17"></polyline>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                </div>
            </div>
            <div class="card col-2" id="score">
                <div class="card-header" id="score">
                    <h6 class="text-center">Media venda / dia</h6>
                </div>
            </div>
            <div class="card col-2">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between">
                        <h6 class="text-center">Venda Total</h6>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="#d9534f" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-arrow-up-right"><line x1="7" y1="17" x2="17" y2="7"></line>
                                <polyline points="7 7 17 7 17 17"></polyline>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                </div>
            </div>
            <div class="card col-2">
                <div class="card-header bg-white">
                    <h6 class="text-center">Venda mes anterior</h6>
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
                <DataTable v-model:filters="filters" ref="dt" :value="dataTable" selectionMode="single" dataKey="id" editMode="cell" @cell-edit-complete="onCellEditComplete"  filterDisplay="row"  paginator :rows="5" tableStyle="min-width: 50rem">
                    <div class="position-absolute" :class="{ 'place': placeh}"></div>
                    <template #header>
                        <div style="text-align: left">
                            <Button icon="pi pi-external-link" label="Export" @click="exportCSV($event)" />
                        </div>
                    </template>
                    <Column field="item_emissao" sortable style="width: 25%" exportHeader="Product Code" header="Date"></Column>
                    <Column field="item_name" sortable style="width: 25%" header="Meal name"></Column>
                    <Column field="quantidade" sortable style="width: 25%" header="Quantity"></Column>
                    <Column field="venda" sortable style="width: 25%" header="Sell"></Column>
                    <Column field="name" sortable style="width: 25%" header="Waiter"></Column>
                </DataTable>
            </div>
        </div>
    </div>
</template>
<script>
import InputText from 'primevue/inputtext';
import bb, {area, areaSpline, bar, line, donut} from 'billboard.js'
import SideBarComponent from './SideBarComponent.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from "primevue/button";

export default {
    name: 'BusinessInteligence',

    components: {
        SideBarComponent,
        InputText,
        DataTable,
        Column,
        Button
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
                def: 0
            },
            barchart:{
                date: [],
                sell: []
            }
        }
    },

    methods: {
        getGeneralStat() {
            axios.get('/api/bi/general-stat').then((response) => {
                console.log(response.data)
                for (let data of response.data){
                    let sell = Number(data.total)
                    this.barchart.date.push(data.item_emissao);
                    this.barchart.sell.push(sell)
                }
                this.SetDoubleBarChart()
                //this.barchart.sell = [...this.waiterSell];
                console.log(this.barchart.sell)
            })
        },

        get_type_waiter_dash(){
            axios.get('/api/bi/dash-type-waiter').then((response) => {
                console.log(response.data)
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
                        case "DESSERT":
                            this.type.dessert += Number(typename.typevenda);
                            break;
                        default:
                            this.type.def += typename.typevenda;
                    }
                }
                this.donut()
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
                        [this.typesCollection[0], this.type.drinks],
                        [this.typesCollection[1], this.type.starter],
                        [this.typesCollection[2], this.type.principal]
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
        }
    },

    mounted() {
        this.getGeneralStat()
        this.get_type_waiter_dash()
        //this.donut()
        console.log(this.typesCollection)
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
