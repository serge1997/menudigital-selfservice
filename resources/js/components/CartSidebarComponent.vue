<template>
    <div class="col-md-10" @click="reload">
        <Button icon="pi pi-cart-plus" @click="visibleRight = true; $emit('AddToCart', id)" />
        <Sidebar v-model:visible="visibleRight" header="Cart" position="right" class="w-50">
            <Accordion class="p-accordion" :activeIndex="0">
                <AccordionTab v-for="item in cartItems" class="p-accordion-header" :header="item.item_name" style="color: #fff">
                    <div class="d-flex justify-content-between">
                        <div class="col-md-3 item-img d-flex flex-column align-items-start">
                            <img alt="item menu image" class="img-thumbnail w-50" src="img/banner.jpg">
                        </div>
                        <div class="d-flex w-50">
                            <div>
                                <Button @click="AddQuantity(item.cartID)" icon="pi pi-plus" />
                            </div>
                            <div>
                                <InputText class="text-center" type="text" :value="item.quantity"/>
                            </div>
                            <div>
                                <Button @click="ReduceQuantity(item.cartID)" icon="pi pi-minus" />
                            </div>
                        </div>
                        <div class="p-2 position-relative">
                            <Chip class="">
                                <span class="fw-medium">{{ item.total}} <small>R$</small></span>
                            </Chip>
                        </div>
                        <div>
                            <Button @click="DeleteFromCart(item.cartID)" icon="pi pi-times"/>
                        </div>
                    </div>
                    <div>
                        <InputText v-model="cart.comments" class="w-75 p-3 mt-3" placeholder="customer comment" />
                    </div>
                </AccordionTab>
            </Accordion>
            <div class="w-100 p-3">
                <h6 class="text-uppercase text-center p-2">Order information</h6>
                <div class="w-100">
                    <label for="customer-name">Customer name</label>
                    <InputText class="w-100" id="customer-name" v-model="cart.ped_customerName" type="text" placeholder="customer name" />
                </div>
                <div class="w-100 p-3">
                    <DataTable :value="cartItems">
                        <Column field="item_name" header="Item name"></Column>
                        <Column field="quantity" header="Quantity"></Column>
                        <Column field="unit_price" header="Price"></Column>
                        <Column field="total" header="Subtotal"></Column>
                    </DataTable>
                </div>
            </div>
            <div class="w-100 d-flex mt-3 justify-content-end">
                <Button @click="confirmOrder" label="Confirm order"/>
            </div>
        </Sidebar>
    </div>
</template>
<script>
import Sidebar from "primevue/sidebar";
import Button from "primevue/button";
import Accordion from "primevue/accordion";
import AccordionTab from "primevue/accordiontab";
import InputText from "primevue/inputtext";
import Chip from "primevue/chip";
import axios from "axios";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import authuser from "@/components/Pages/auth.js";
export default {
    name: 'CartSideBarComponent',
    components: {
        Sidebar,
        Button,
        Accordion,
        AccordionTab,
        InputText,
        Chip,
        DataTable,
        Column
    },
    props:['id'],
    data(){
        return {
            visibleRight: false,
            table: localStorage.getItem('table'),
            cartItems: null,
            cart: {
                comments: null,
                options: [],
                tableNumber: localStorage.getItem('table'),
                ped_tableNumber: localStorage.getItem('table'),
                user_id: null,
                ped_customerName: null
            },
        }
    },

    methods:{
        getCartItem(){
            axios.get('/api/cart/items/'+ this.table).then((response) => {
                this.cartItems = response.data.items
            }).catch((errors) => {
                console.log(errors)
            })
        },
        reload(){
            return this.getCartItem()
        },
        AddQuantity(id) {
            axios.post(`/api/add-quantity/${id}`).then((response) => {
                this.quantity = response.data.quantity
                this.total = response.data.total
                console.log(response.data.total)
                return this.getCartItem();
            }).catch((errors) => {
                console.log(errors)
            })
        },

        ReduceQuantity(id) {
            axios.post('/api/reduce-quantity/' + id).then((response) => {
                this.quantity = response.data.quantity
                this.total = response.data.total
                console.log(response.data.total)
                return this.getCartItem()
            }).catch((errors) => {
                console.log(errors)
            })
        },
        DeleteFromCart(cartID, table) {
            table = this.tableNumber
            axios.get(`/api/delete/item/${cartID}/${table}`).then((response) => {
                return this.getCartItem();
            }).catch((errors) => {
                console.log(errors)
            })
        },

        confirmOrder() {
            axios.post('/api/order', this.cart).then((response) => {
                console.log(response.data)
                this.$router.push('/dashboard/garcom')
                this.$toast.success(response.data)
            }).catch((errors) => {
                console.log(errors)
                this.$toast.error(errors.response.data)
            })
        }
    },
    async mounted() {
        this.getCartItem()
        await authuser.then(user => this.cart.user_id = user.id)

    }
}
</script>
<style>
.p-accordion-header{
    text-decoration: none;
}
</style>
