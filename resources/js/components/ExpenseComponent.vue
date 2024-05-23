<template>
    <Button @click="visibleExpenseModal = true;" :label="`${$t('purchase.toolbar.two')}`" icon="pi pi-plus" text/>
    <Dialog v-model:visible="visibleExpenseModal" maximizable modal header="Nova requisição de compra" :style="{ width: '75rem', }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div class="row p-5">
            <div class="col-md-12 d-flex justify-content-between align-items-center">
                <div class="col-md-6">
                    <Button class="col-md-8" @click="showProductForm = !showProductForm;" label="Despesa de produto" />
                </div>
                <div class="col-md-6">
                    <Button @click="showItemForm = !showItemForm" class="col-md-8" label="Despesa de item do menu" />
                </div>
            </div>
        </div>
        <div class="row">
            <div v-if="!showItemForm && !showProductForm" class="col-md-6"></div>
            <div class="col-md-6">
                <div v-if="showProductForm" class="col-md-12 d-flex flex-column justify-content-center align-items-center">
                    <div class="col-md-12 d-flex flex-column">
                        <label for="producto">Selecione o produto</label>
                        <Dropdown v-model="expenseData.product_id" :options="products" option-value="id" option-label="prod_name" class="w-100 md:w-14rem" />
                        <small class="text-danger" v-if="errMessageBag" v-for="errProduct in errMessageBag.product_id" v-text="errProduct"></small>
                    </div>
                    <div class="col-md-12 d-flex flex-column mt-2">
                        <label for="producto">Quantidade despesa</label>
                        <InputText type="number" class="col-md-12" v-model="expenseData.quantity" />
                        <small class="text-danger" v-if="errMessageBag && expenseData.item_id == null" v-for="errQuantity in errMessageBag.quantity" v-text="errQuantity"></small>
                    </div>
                    <div class="col-md-12 d-flex flex-column mt-2">
                        <label for="observation">Observação</label>
                        <Textarea type="number" class="col-md-12" v-model="expenseData.observation" />
                        <small class="text-danger" v-if="errMessageBag && expenseData.item_id == null" v-for="errObservation in errMessageBag.observation" v-text="errObservation"></small>
                    </div>
                    <div class="w-100 d-flex mt-2">
                        <Button v-if="showItemForm" disabled label="Salvar" />
                        <Button @click="createProductExpense" v-else label="Salvar" />
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div v-if="showItemForm" class="col-md-12 d-flex flex-column justify-content-center align-items-center">
                    <div class="col-md-12 d-flex flex-column">
                        <label for="producto">Selecione o item</label>
                        <Dropdown v-model="expenseData.item_id" :options="items" option-value="id" option-label="item_name" class="w-100 md:w-14rem" />
                        <small class="text-danger" v-if="errMessageBag" v-for="errItem in errMessageBag.item_id" v-text="errItem"></small>
                    </div>
                    <div class="col-md-12 d-flex flex-column mt-2">
                        <label for="producto">Quantidade despesa</label>
                        <InputText type="number" class="col-md-12" v-model="expenseData.quantity" />
                        <small class="text-danger" v-if="errMessageBag && expenseData.product_id == null" v-for="errQuantity in errMessageBag.quantity" v-text="errQuantity"></small>
                    </div>
                    <div class="col-md-12 d-flex flex-column mt-2">
                        <label for="observation">Observação</label>
                        <Textarea type="number" class="col-md-12" v-model="expenseData.observation" />
                        <small class="text-danger" v-if="errMessageBag && expenseData.product_id == null" v-for="errObservation in errMessageBag.observation" v-text="errObservation"></small>
                    </div>
                    <div class="w-100 d-flex mt-2">
                        <Button v-if="showProductForm" disabled label="Salvar" />
                        <Button @click="createMenuItemExpense" v-else label="Salvar" />
                    </div>
                </div>
            </div>
        </div>
    </Dialog>
</template>
<script>
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
export default {
    name: 'ExpenseComponent',

    components: {
        Button,
        Dropdown,
        Dialog,
        InputText,
        Textarea
    },

    data(){
        return {
            visibleExpenseModal: false,
            items: null,
            products: null,
            expenseData: {
                product_id: null,
                item_id : null,
                quantity: null,
                observation: null
            },
            showItemForm: false,
            showProductForm: false,
            errMessageBag: null
        }
    },

    methods: {
        toaster(){
            const Toast = this.$swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            return Toast
        },
        async loadMenuItems(){
            const menuResponse = await axios.get('/api/menu-items');
            this.items = await menuResponse.data;
        },

        async loadProducts(){
            const productResponse = await axios.get('/api/products');
            this.products = await productResponse.data.filter(
                product => product.is_delete !== 1
            );
        },
        createProductExpense(){
            return new Promise(resolve => {
                setTimeout(() => {
                    axios.post('/api/expense-product', this.expenseData)
                    .then((response) => {
                        this.toaster().fire({
                            text: response.data,
                            icon: 'success'
                        })
                        this.expenseData.product_id = "";
                        this.expenseData.observation = "";
                        this.expenseData.quantity = "";
                        resolve(true)
                    })
                    .catch((errors) => {
                        this.errMessageBag = errors.response.data.errors
                        //errors.response.status === 422 ? this.errMessageBag = errors.response.data.errors : null;
                        console.log(this.errMessageBag)
                        errors.response.status === 500 ?? this.$swal.fire({text: errors.response.data, icon: 'error'});
                    })
                })
            })
        },

        createMenuItemExpense(){
            return new Promise(resolve => {
                setTimeout(() => {
                    axios.post('/api/expense-menu-item', this.expenseData)
                    .then((response) => {
                        this.toaster().fire({
                            text: response.data,
                            icon: 'success'
                        })
                        this.expenseData.observation = "";
                        this.expenseData.item_id = "";
                        this.expenseData.quantity = "";
                        resolve(true)
                    })
                    .catch((errors) => {
                        errors.response.status === 500 && this.$swal.fire({text: errors.response.data, icon: 'error'});
                    })
                })
            })
        }
    },
    mounted(){
        this.loadProducts();
        this.loadMenuItems();
    }

}

</script>
