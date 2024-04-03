<template>
    <div class="container ">
        <h5 class="p-2 text-center">Item Cart</h5>
        <div class="row shadow p-3">
            <div v-if="cartItem" v-for="item in cartItem" :key="item.id" class="col-lg-5 col-md-12 mb-4 m-auto">
                <div class="card rounded-0">
                    <div class="card-body d-flex p-0">
                        <div class="col-6">
                            <img class="w-100" src="/img/banner.jpg" alt="">
                        </div>
                        <div class="w-100 d-flex flex-column justify-content-between p-2">
                            <h5 class="text-center">{{ item.item_name }}</h5>
                            <small class="text-center text-secondary">{{ item.item_desc }}</small>
                            <h6 v-if="total" class="col-lg-4 text-center m-auto text-white py-2 px-2 shadow rounded-4 price">R$ {{ total }} </h6>
                            <h6 v-else class="col-lg-4 text-center m-auto text-white py-2 px-2 shadow rounded-4 price">R$ {{ item.total }} </h6>
                            <div class="order-btn-box text-white mt-3 d-flex justify-content-center">
                                <router-link @click="ReduceQuantity(tab)" :to="{ name: 'ItemCart', params: {id:item.id}}" class="btn border card-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#fff"
                                        stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus"><line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                </router-link>
                                <div class="px-1"></div>
                                <span v-if="!quantity"  class="text-secondary px-3 fs-5 fw-medium border border-secondary">{{ item.quantity }}</span>
                                <span v-else class="text-secondary px-3 fs-5 fw-medium border border-secondary">{{ quantity }}</span>
                                <div class="px-1"></div>
                                <router-link @click="AddQuantity(tab)" :to="{ name: 'ItemCart', params: {id:item.id}}" class="btn border card-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="4" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-3 d-flex flex-column">
                    <h6>Opção :</h6>
                    <label v-for="option in options" for="">
                        <input type="checkbox" name="option" v-model="cart.options" :value="option.option_name">
                        {{ option.option_name }}
                    </label>
                </div>
            </div>
            <div class="col-lg-6 col-md-10">
                <div class="info-customer col-md-10">
                    <label for="" class="fs-5">Comentario sobre o pedido?</label>
                    <textarea v-model="cart.comments" class="form-control rounded-0" placeholder="Exemplo : 'O bife, quero mal passado '" cols="5" rows="4"></textarea>
                </div>
                <button @click="SetCartOptions" class="btn btn-single-style m-auto rounded-0 mt-2 text-capitalize">adicionar</button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'

export default {
    name: 'ItemCart',

    components: {

    },

    data() {
        return {
            cartItem: null,
            options: null,
            table: {
                tableNumb: localStorage.getItem('table')
            },

            cart: {
                comments: null,
                options: [],
                tableNumber: localStorage.getItem('table')
            },
            quantity: null,
            total: null
        }
    },

    methods: {
        showCart(table = this.table.tableNumb) {
            axios.get(`/api/cart-item/${this.$route.params.id}/${table}`).then((response) => {
                //console.log(response.data)
                this.cartItem = response.data.cartitem
                this.options = response.data.options
            }).catch((errors) => {
                console.log(errors)
            })
        },

        SetCartOptions() {
            axios.post('/api/set/option/' + this.$route.params.id , this.cart).then((response) => {
                console.log(response.data)
                this.$router.push('/menu')
                this.$toast.success("Item adicionado com sucesso");
            }).catch((errors) => {
                console.log(errors)
            })
        },

        AddQuantity(table = this.cart.tableNumber) {
            axios.post(`/api/add-quantity/${this.$route.params.id}/${table}`).then((response) => {
                this.quantity = response.data.quantity
                this.total = response.data.total
                console.log(response.data.total)
            }).catch((errors) => {
                console.log(errors)
            })
        },

        ReduceQuantity(table = this.cart.tableNumber) {
            axios.post('/api/reduce-quantity/' + this.$route.params.id + '/' + table).then((response) => {
                this.quantity = response.data.quantity
                this.total = response.data.total
                console.log(response.data.total)
            }).catch((errors) => {
                console.log(errors)
            })
        }

    },

    mounted() {
        axios.get(`/api/cart-item/${this.$route.params.id}/${this.table.tableNumb}`).then((response) => {
                this.cartItem = response.data.cartitem
                this.options = response.data.options
                console.log(this.cartItem)
            }).catch((errors) => {
                console.log(errors)
            })
    }
}

</script>

<style scoped>



</style>
