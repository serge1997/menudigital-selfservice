<template>
    <div class="container-fluid p-4">
        <h4 class="text-center text-capitalize p-4">Your Food Cart !</h4>
        <div class="row">
            <div v-if="ShowCard" class="col-lg-4 col-md-12 position-relative">
                <div class="card rounded-0">
                    <div class="card-header border-0">
                    </div>
                    <div class="card-body">
                        <ul class="list-group w-75">
                            <li class="list-group-item border-0 d-flex justify-content-between">items: 
                                <span class="p-1 px-3">14</span>
                            </li>
                            <li class="list-group-item border-0 d-flex justify-content-between">Subtotal: 
                                <span class="p-1 px-1">{{ total }}</span>
                            </li>
                             <li class="list-group-item border-0 d-flex justify-content-between">Total: 
                                <span class="p-1 px-1">{{ total }}</span>
                             </li>
                        </ul>
                        <div class="p-2 mt-2">
                            <input type="text" v-model="orderData.ped_customerName" class="form-control rounded-0 w-75 border-secondary" placeholder="Nome do cliente">
                            <input type="hidden" v-model="orderData.user_id">
                        </div>
                        <div class="p-2">
                            <button @click="confirmOrder" class="btn btn-single-style rounded-0">Confirmar Pedido</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-12">
                <div v-for="item in cart" :key="item.id" class="mb-4 m-auto">
                    <div class="card border-0 border-bottom rounded-0 p-0">
                        <div class="card-body d-flex justify-content-between p-0">
                            <div class="py-0 d-flex p-1 w-50">
                                <div>
                                    <img class="rounded-0 card-img-top" src="img/banner.jpg" alt="" style="width: 8rem;">
                                </div>
                                <div class="d-flex flex-column align-items-start p-2">
                                    <h5 class="fs-6">{{ item.item_name }}</h5>
                                    <span class="text-secondary fs-6">{{ item.item_desc }}</span>
                                </div>
                            </div>
                            <div class="w-50 d-flex justify-content-between align-items-center align-items-start p-2">
                                <h6 class="text-center text-secondary">Subtotal:
                                    <span class="text-primary">R${{ item.total }}</span>
                                </h6>
                                <h6 class="text-center text-secondary">Quantidade :
                                    <span class="text-primary">{{ item.quantity }}</span>
                                </h6>
                            </div>
                            <div class="d-flex align-items-center">
                                <button @click="DeleteFromCart(item.cartID)" class="btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" 
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                        class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: 'CustomerOrderList',

    components: {

    },

    data() {
        return {
            cart: null,
            tableNumber: localStorage.getItem('table'),
            total: null,
            orderData: {
                ped_tableNumber: localStorage.getItem('table'),
                ped_customerName: null,
                user_id: null
            },
            ShowCard: true
            
        }
    },

    methods: {
        async getCartList(table = this.tableNumber) {
            await axios.get('/api/cart/items/' + table).then((response) => {
                this.cart = response.data.items
                this.total = response.data.total
                console.log(response.data.items)
                if (!this.cart.length) {
                    return this.ShowCard = false
                }
                console.log(response)
            })
        },
        DeleteFromCart(cartID, table) {
            table = this.tableNumber
            axios.get(`/api/delete/item/${cartID}/${table}`).then((response) => {
               return this.getCartList()
            }).catch((errors) => {
                console.log(errors)
            })
        },

        confirmOrder() {
            axios.post('/api/order', this.orderData).then((response) => {
                console.log(response.data)
                this.$router.push('/menu')
                this.$toast.success("Pedido confirmado !")
            }).catch((errors) => {
                console.log(errors)
            })
        }
    },

    mounted() {
        window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`
        console.log(localStorage)
        this.getCartList()

        axios.get('/api/user').then((response) => {
            if (response.data.id){
                this.orderData.user_id = response.data.id
            }
        })
    }
}


</script>

<style scoped>
   
</style>