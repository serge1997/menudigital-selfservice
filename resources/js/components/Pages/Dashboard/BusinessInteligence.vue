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
import bb, {area, areaSpline, bar, donut} from 'billboard.js'
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
            }
        }
    },

    methods: {

        getWaiterStats(){
            axios.get('/api/waiter/stats').then((response) => {
                this.typesCollection = response.data.type
                this.itemsCollection = response.data.itemsCollection


                for(const w of response.data.waiter){
                    this.waiterAvg.push(w.mediaVenda)
                    this.waiterName.push(w.name)
                    this.waiterSell.push(w.venda)
                }

                const TypeSet = new Set();
                const TypeCollection = [];
                for(const t of response.data.type){
                    TypeSet.add(t.type)
                    //this.typeSell.push(t.typevenda)
                }
                
                TypeSet.forEach(function(e){
                    TypeCollection.push(e)
                })

                for(const t of response.data.type){
                    if (t.type == "ENTRADA"){
                        t.typevenda = Number(t.typevenda)
                        this.type.starter += t.typevenda
                    }else{
                        if (t.type == "PRINCIPAL"){
                            t.typevenda = Number(t.typevenda)
                            this.type.principal += t.typevenda
                        }
                    }

                }

                console.log(this.type.principal)
                

                bb.generate({
                    data: {
                        columns: [
                            ["VENDA", ...this.waiterSell],
                            ["MEDIA VENDA", ...this.waiterAvg]
                        ],
                        type: bar(),
                    },
                    axis:{
                        x:{
                            type: "category",
                            categories: [...this.waiterName]
                        }
                    },

                    bar: {
                        width: {
                            ratio: 0.5
                        }
                    },
                    bindto: "#bar"
                });

                var chart = bb.generate({
                    data: {
                        columns: [
                            [TypeCollection[0], this.type.starter],
                            [TypeCollection[1], this.type.principal],
                        ],
                        type: donut(), // for ESM specify as: donut()
                    },
                    arc: {
                        cornerRadius: {
                        ratio: 0.2
                        }
                    },
                    bindto: "#charDonut"
                    });
            }).catch((errors) =>{
                console.log(errors)
            })
        },


        MontChartBar() {
            
       },

       MontChartLine(){
            var chart = bb.generate({
            data: {
                columns: [
                ["data1", 300, 350, 300, 0, 0, 0],
                ["data2", 130, 100, 140, 200, 150, 50]
                ],
                types: {
                data1: area(), // for ESM specify as: area()
                data2: areaSpline(), // for ESM specify as: areaSpline()
                }
            },
                bindto: "#Chart"
            });
       },

       MountChartDonut() {
            
       }
    },

    mounted(){
        this.MontChartBar()
        this.MontChartLine()
        this.MountChartDonut()
        this.getWaiterStats()
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