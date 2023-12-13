<template>
    <!-- Button trigger modal -->
    <button type="button" @click="$emit('getTransfertItems', id)" class="btn" data-bs-toggle="modal" data-bs-target="#transfertModal">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-divide-square">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="8" y1="12" x2="16" y2="12"></line>
            <line x1="12" y1="16" x2="12" y2="16"></line><line x1="12" y1="8" x2="12" y2="8"></line>
        </svg>
    </button>

    <!-- Modal -->
    <div class="modal fade" id="transfertModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Transferir Item de Pedido</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="d-flex justify-content-between p-3">
                <p class="fs-4">Itens do Pedido:</p>
                <div class="form-check form-switch d-flex flex-column align-items-center w-50" role="group" aria-label="Basic checkbox toggle button group">
                    <div class="mt-3 w-75" v-for="item in transfertItems">
                        <input type="hidden" id="pedido" :value="item.item_pedido">
                        <input class="form-check-input border-black" type="checkbox" :value="item.item_name + item.item_id" id="flexCheckDefault" v-model="options">
                        <label class="form-check-label fw-medium" for="flexCheckDefault">
                            {{ item.item_name }}
                        </label>
                    </div>
                </div>
                <div class="w-50 shadow p-3">
                    <label for="">Mesa de destino: </label>
                    <select class="form-select w-50 rounded-0 border-secondary" v-model="transfert.ped_tableNumber">
                        <option v-for="tab in tables" :value="tab.table">{{ tab.table }}</option>
                    </select>
                    <div class="btn-group d-flex flex-column w-50" role="group" aria-label="Basic checkbox toggle button group">
                        <div class="mt-2 d-flex flex-column" v-for="(op, index) in options">
                            <label class="btn btn-outline-primary rounded-0 fw-medium" for="btncheck1">{{ op.replace(/[0-9]/g, '') }}</label>
                            <input class="form-control rounded-0 border-secondary mt-2" type="number" v-model="transfert.item_quantidade[index]" :placeholder="op.replace(/[0-9]/g, '') + ' quantidade'">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn modal-btn text-white rounded-0" @click="postTransfert">Transferir</button>
        </div>
        </div>
    </div>
    </div>
</template>
<script>
import VueAxios from 'vue-axios'

export default {
    name: 'TransfertComponent',

    props:['transfertItems', 'tables'],

    watch:{
        item_quantidade(after, before){
            this.quantidade.push(this.transfert.item_quantidade)
            this.Insert()
        }
    },

    data(){
        return {
            options: [],
            quantidade: ["QUantidade"],
            count: 0,
            transfert:{
                item_pedido: null,
                item_id: [],
                ped_tableNumber: null,
                item_quantidade: []
            },
        }
    },

    methods:{
        postTransfert(){
            this.transfert.item_pedido = document.getElementById('pedido').value
            for(let x of this.options){
                this.transfert.item_id.push(x.replace(/[^0-9]/g, ''))
            }
            axios.post('/api/post/transfert', this.transfert).then((response) => {
                this.$toast.success(response.data)
            }).catch((errors) => {
                console.log(errors)
                this.$toast.error(errors.response.data)
            })
        },
        Insert() {
            this.transfert.item_quantidade = new Array(this.options.length).fill('')
            //this.quantidade.push(this.transfert.item_quantidade[index])
        }
    },

    mounted(){

    }
}
</script>
