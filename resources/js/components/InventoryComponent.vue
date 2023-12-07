<template>
    <div class="container">
        <div class="modal fade" id="InventoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 text-capitalize" id="staticBackdropLabel">Product Inventory</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table w-100 table-active">
                            <thead>
                                <tr class="text-capitalize">
                                    <th>Item</th>
                                    <th>Saldo inicial</th>
                                    <th>Saldo Final</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="saldo in inventory">
                                    <td>{{ saldo.prod_name }}</td>
                                    <td>{{ saldo.saldoinicial.toFixed(2) }}</td>
                                    <td>{{ saldo.saldofinal.toFixed(2) }}</td>
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
export default {
    name : 'InventoryComponent',

    data(){
        return {
            inventory: null
        }
    },

    mounted(){
        axios.get('/api/inventory').then((response) => {
            console.log(response.data)
            this.inventory = response.data
        }).catch((errors) => {
            console.log(errors)
        })
    }
}
</script>
