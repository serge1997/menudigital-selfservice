<template>
    <div class="col-md-3 m-auto mt-5">
        <h5 class="text-center">Casino Bar</h5>
        <h6 class="text-center">Bill</h6>
        <table class="table">
            <thead>
                <tr>
                    <th class="fw-medium fs-7">Item</th>
                    <th class="fw-medium fs-7">Quantidade</th>
                    <th class="fw-medium fs-7">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="bill in billItem">
                    <td class="fs-6 fw-light">{{ bill.item_name }}</td>
                    <td class="fs-6 fw-light">{{ bill.item_quantidade }}</td>
                    <td class="fs-6 fw-medium">R${{ bill.item_total }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: 'Bill',

    data() {
        return {
            billItem: null
        }
    },

    methods:{
        getBillItems() {
            axios.get(`/api/get/bill/${this.$route.params.id}`).then((response) =>{
                console.log(response.data)
                this.billItem = response.data
            }).catch((errors) => {
                console.log(errors)
            })
        }
    },
    mounted(){
        this.getBillItems()
    }
}

</script>