<template>
    <div class="col-lg-6 col-md-10" @click="reload">
        <Button v-if="!rupture" icon="pi pi-cart-plus" @click="visibleRight = true; $emit('AddToCart', id)" />
        <Button v-else icon="pi pi-cart-plus" @click="visibleRight = true; $emit('AddToCart', id, rupture)" disabled />
        <Sidebar v-model:visible="visibleRight" header="Cart" position="right" class="col-lg-8 col-md-12">
            <Accordion class="p-accordion" :activeIndex="0">
                <AccordionTab v-for="item in cartItems" class="p-accordion-header" :header="item.item_name" style="color: #fff">
                    <div class="col-md-12 d-flex justify-content-evenly">
                        <div class="col-md-2 item-img d-flex justify-content-start">
                            <img alt="item menu image" class="img-thumbnail w-100" src="img/banner.jpg">
                        </div>
                        <div class="col-md-6 d-flex justify-content-center align-items-center">
                            <div class="col-md-2">
                                <Button class="w-100" @click="AddQuantity(item.cartID)" icon="pi pi-plus" />
                            </div>
                            <div class="col-md-4 d-flex flex-column">
                                <InputText class="text-center w-100" type="text" :value="item.quantity"/>
                            </div>
                            <div class="col-md-2">
                                <Button class="w-100" @click="ReduceQuantity(item.cartID)" icon="pi pi-minus" />
                            </div>
                            <div class="w-100 d-flex justify-content-center bg-white">
                                <span class="fw-medium">{{ item.total}} <small>R$</small></span>
                            </div>
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                            <Button @click="DeleteFromCart(item.cartID)" severity="danger" icon="pi pi-times"/>
                        </div>
                    </div>
                    <div>
                        <InputText v-model="cart.comments" class="w-75 p-3 mt-3" placeholder="customer comment" />
                    </div>
                    <div class="w-100 mt-3 p-3">
                        <div class="d-flex flex-wrap gap-3">
                            <div v-for="option in options" class="flex align-items-center gap-2">
                                <RadioButton v-model="cart.options" name="pizza" :value="option.option_name" />
                                <label for="ingredient1" class="ml-2">{{ option.option_name }}</label>
                            </div>
                        </div>
                    </div>
                </AccordionTab>
            </Accordion>
            <div class="w-100 p-3">
                <h6 class="text-uppercase text-center p-2">Order information</h6>
                <div class="w-100">
                    <label for="customer-name">Customer name</label>
                    <InputText class="w-100" id="customer-name" v-model="cart.ped_customerName" type="text" placeholder="customer name" />
                    <small class="text-danger" v-if="errMsg" v-text="errMsg[0]"></small>
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
import RadioButton from "primevue/radiobutton";
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
        Column,
        RadioButton
    },
    props:['id', 'rupture'],
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
            options: null,
            errMsg: null
        }
    },

    methods:{
        getCartItem(){
            axios.get('/api/cart-itens/'+ this.table).then((response) => {
                this.cartItems = response.data.items
                this.options = response.data.options
            }).catch((errors) => {
                console.log(errors)
            })
        },
        reload(){
            return this.getCartItem()
        },
        AddQuantity(id) {
            axios.put(`/api/cart-add/quantity/${id}`).then((response) => {
                this.quantity = response.data.quantity
                this.total = response.data.total
                console.log(response.data.total)
                return this.getCartItem();
            }).catch((errors) => {
                console.log(errors)
            })
        },

        ReduceQuantity(id) {
            axios.put('/api/cart-reduce/quantity/' + id).then((response) => {
                this.quantity = response.data.quantity
                this.total = response.data.total
                console.log(response.data.total)
                return this.getCartItem()
            }).catch((errors) => {
                console.log(errors)
            })
        },
        DeleteFromCart(cartID, table) {
            table = this.table
            axios.delete(`/api/cart-item/${cartID}/${table}`).then((response) => {
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
                //this.$toast.error(errors.response.data)
                if (errors.response.status === 500){
                    this.visibleRight = false
                    this.$swal.fire({
                        text: !errors.response.data.message ? errors.response.data : errors.response.data.message  ,
                        icon: "error"
                    })
                }
                this.errMsg = errors.response.data.errors.ped_customerName
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
