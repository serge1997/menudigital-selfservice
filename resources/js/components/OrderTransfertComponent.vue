<template>
    <!-- Button trigger modal -->
    <div class="d-flex align-items-center">
        <Button
            icon="pi pi-arrow-right-arrow-left" type="button"
            @click="$emit('getTransfertItems', id)" title="transferir item"
            text data-bs-toggle="modal" data-bs-target="#transfertModal"
        />
    </div>

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
                        <input class="form-check-input border-black" type="checkbox" :value="item.item_name + '-'+ item.item_id" id="flexCheckDefault" v-model="options">
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
                            <label class="btn btn-outline-primary rounded-0 fw-medium" for="btncheck1">{{ formatItemName(op) }}</label>
                            <input class="form-control rounded-0 border-secondary mt-2" type="number" v-model="transfert.item_quantidade[index]" :placeholder="op.replace(/[0-9]/g, '') + ' quantidade'">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <Button @click="postTransfert" label="Trasnferir" />
        </div>
        </div>
    </div>
    </div>
</template>
<script>
import Button from "primevue/button";
export default {
    name: 'TransfertComponent',

    components: {
        Button
    },

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
            preg_1: /-\d$/,
            preg_2: /-\d\d$/,
            preg_3: /-\d\d\d$/,

        }
    },

    methods:{
        postTransfert(){
            this.transfert.item_pedido = document.getElementById('pedido').value
            var item_id = [];
            var regex_item_id = null;
            for(let x of this.options){

                if (this.preg_1.test(x)){
                    regex_item_id = x.match(this.preg_1).join('').replace('-', '')
                }
                if (this.preg_2.test(x)){
                    regex_item_id = x.match(this.preg_2).join('').replace('-', '');
                }
                if (this.preg_3.test(x)){
                    regex_item_id = x.match(this.preg_3).join('').replace('-', '');
                }

                this.transfert.item_id.push(regex_item_id);
            }
            console.log(this.transfert);
            axios.post('/api/order-transert-itens', this.transfert).then((response) => {
                this.$toast.success(response.data)
                this.transfert.item_id = []
                this.transfert.item_quantidade = []
                this.options = []
            }).catch((errors) => {
                console.log(errors)
                this.$toast.error(errors.response.data)
            })
        },
        Insert() {
            this.transfert.item_quantidade = new Array(this.options.length).fill('')
            //this.quantidade.push(this.transfert.item_quantidade[index])
        },
        formatItemName(item_name){
            if (this.preg_1.test(item_name)){
                return item_name.replace(/-\d$/, '');
            }
            if(this.preg_2.test(item_name)){
                return item_name.replace(/-\d\d$/, '')
            }
            if (this.preg_3.test(item_name)){
                return item_name.replace(/-\d\d\d$/, '')
            }
        }
    },

    mounted(){
        console.log("preg_response " + this.preg_2.test("anos -1"));
        console.log(this.formatItemName())
    }
}
</script>
