<template>
    <div class="container-fuid d-flex justify-content-between">
        <div class="position-fixed">
            <SideBarComponent class="text-center"></SideBarComponent>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-8 m-auto">
                    <form @submit.prevent="SaveItem" class="w-100 p-4">
                        <div class="form-header p-2 text-capitalize shadow-lg rounded-3 text-white w-100">
                            <h6>create a new menu item</h6>
                            <p>menu item</p>
                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-6 col-md-10 d-flex flex-column">
                                <div class="d-flex flex-column gap-2">
                                    <label for="item-name">Item name</label>
                                    <InputText :class="invalid" type="text" id="item-name" v-model="menuItem.item_name" aria-describedby="item name" />
                                    <small class="text-danger" v-if="errMsg" v-for="item_name in errMsg.item_name" id="item-name-err"  v-text="item_name"></small>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-10 d-flex flex-column">
                                <div class="d-flex flex-column gap-2">
                                    <label for="item-price">Item price</label>
                                    <InputText :class="invalid" type="text" id="item-price" v-model="menuItem.item_price" aria-describedby="item-price" />
                                    <small class="text-danger" v-if="errMsg" v-for="item_price in errMsg.item_price" id="item-price-err"  v-text="item_price"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row p-3">
                            <Dropdown v-model="menuItem.type_id" option-value="id_type" :options="mealType" optionLabel="desc_type" placeholder="Select a food group" class="w-full md:w-14rem" />
                            <small class="text-danger" v-if="errMsg" v-for="type_id in errMsg.type_id" id="item-price-err"  v-text="type_id"></small>
                        </div>
                        <div class="d-flex p-2 mt-2">
                            <div class="d-flex align-items-center">
                                <RadioButton type="radio"  id="isactive-true" class="" name="isactive" value="1" v-model="menuItem.item_status" />
                                <span class="px-1"></span>
                                <label for="isactive-true" class="">Activate</label>
                            </div>
                            <span class="px-1"></span>
                            <div class="d-flex justify-content-between">
                                <RadioButton type="radio" class="" id="isactive-false" name="isactive" value="0" v-model="menuItem.item_status" />
                                <span class="px-1"></span>
                                <label for="isactive-false" class="">Desactivate</label>
                            </div>
                        </div>
                        <div class="row d-flex flex-colum mt-2 p-1">
                            <label for="item-desc">Item description</label>
                            <Textarea v-model="menuItem.item_desc" rows="5" cols="30" id="item-desc" />
                            <small class="text-danger" v-if="errMsg" v-for="item_desc in errMsg.item_desc" id="item-desc-err"  v-text="item_desc"></small>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <label for="name" class="fs-5">Imagem do prato : </label>
                                <input type="file" class="form-control rounded-0 border-secondary" placeholder="nome do prato">
                            </div>
                        </div>
                        <div class="mt-3">
                            <Button type="submit" label="Salvar"/>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import SideBarComponent from './SideBarComponent.vue'
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import Dropdown from "primevue/dropdown"
import Textarea from "primevue/textarea";
import RadioButton from "primevue/radiobutton";
import Dialog from "primevue/dialog";
export default {
    name: 'NewItem',

    components: {
        SideBarComponent,
        InputText,
        Button,
        Dropdown,
        Textarea,
        RadioButton,
        Dialog
    },

    data() {
        return {
            menuItem: {
                item_name: null,
                item_desc: null,
                item_price: null,
                tem_image: null,
                type_id: null,
                item_status: null
            },
            mealType: null,
            errMsg: null,
            invalid: null,
            measure: [
                { un: 'cl' },
                { un: 'bt' },
                { un: 'kg' },
                { un: 'g' },
                { un: 'bt' }
            ],
            visible: false
        }
    },

    methods: {
        SaveItem() {
           axios.post('/api/menu-items', this.menuItem).then((response) => {
                this.menuItem.item_desc = "";
                this.menuItem.item_price = "";
                this.menuItem.type_id = "";
                this.menuItem.item_name = ""
                this.errMsg = null
               this.$toast.success(response.data);
           }).catch((errors) => {
             console.log(errors.response.data.errors)
             this.errMsg = errors.response.data.errors
             errors.response.status === 500 ? this.$swal.fire({text: errors.response.data, icon: 'warning'}): null
           })
        },


        async loadMealType(){
            let mealTypeResponse = await axios.get('/api/meal-type');
            this.mealType = await mealTypeResponse.data;

        }
    },

    async mounted() {
        await this.loadMealType();
    }
}

</script>
