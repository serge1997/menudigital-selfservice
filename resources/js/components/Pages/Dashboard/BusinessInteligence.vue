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
                    <div class="col-lg-5 col-md-6 d-flex flex-column p-2 waiter-ranking">
                        <h6>Top waiter ranking</h6>
                        <div class="border shadow-sm waiter-card mt-3 col-md-12" v-for="type in typesCollection">
                            <div class="header d-flex justify-content-between">
                                <div class="user-icon p-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2">
                                        </path><circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <p class="mt-1">{{ type.type }}</p>
                                </div>
                                <div class="user-value p-1">
                                    <h6 class="text-danger">{{ type.typevenda }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-6 m-auto">
                        <div id="charDonut"></div>
                    </div>
                </div>
                <div class="border">
                    <table class="table">
                            <thead>
                                <tr>
                                    <th>Emissao</th>
                                    <th>Item name</th>
                                    <th>Quantidade</th>
                                    <th>Venda</th>
                                </tr>
                            </thead>
                        </table>
                    <div id="table">
                        <table class="table table-striped">
                            <tbody>
                                <tr v-for="itens in itemsCollection">
                                    <td>{{ itens.item_emissao }}</td>
                                    <td>{{ itens.item_name }}</td>
                                    <td>{{ itens.quantidade }}</td>
                                    <td>{{ itens.venda }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import bb, {area, areaSpline, bar, line, donut} from 'billboard.js'
import SideBarComponent from './SideBarComponent.vue';

export default {
    name: 'BusinessInteligence',

    components: {
        SideBarComponent
    },

    data() {

        return {
            waiterSell: [],
            waiterAvg: [],
            waiterName: [],
            itemsCollection: null,
            typesCollection: null,
            typeName: [],
            type:{
                starter: 0,
                principal: 0
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
        }
    },

    mounted() {
        this.getGeneralStat()
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
