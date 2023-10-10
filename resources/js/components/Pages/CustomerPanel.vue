<template>
    <div class="col-lg-12 col-md-10 d-flex justify-content-center">
        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>quantidade</th>
                    <th>Valor</th>
                    <th>Valor Total</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in order">
                    <td>{{ item.item_name }}</td>
                    <td>{{ item.item_quantidade }}</td>
                    <td>{{ item.item_price }}</td>
                    <td>{{ item.item_total }}</td>
                    <button class="btn p-0" @click="AddNewItem(item.pedido_id)">New item</button>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import VueAxios from 'vue-axios';
import _ from 'lodash'

export default {
    name: 'CustomerPanel',

    data() {
        return {
            order: null,
            pedido_id: ''
        }
    },

    watch: {
        order: _.debounce(function(newOrder){
            this.getOrderList();
        }, 10000)
    },

   methods: {
    async getOrderList() {
        const table = localStorage.getItem('table')
        const response = await axios.get('/api/order/list/' + table)
        this.order = response.data
        console.log(response.data)
    }
   },
   created() {
    this.getOrderList()
    
   }
}

</script>