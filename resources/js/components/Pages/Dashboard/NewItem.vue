<template>
    <div class="container-fuid d-flex justify-content-between">
        <div class="position-fixed">
            <SideBarComponent class="text-center"></SideBarComponent>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-8 m-auto">
                    <h5 class="text-center p-2">Salvar novo item do menu</h5>
                    <form @submit.prevent="SaveItem" class="shadow w-100 p-4">
                        <div class="row">
                            <div class="col-lg-6 col-md-10 d-flex flex-column">
                                <input type="text" class="form-control rounded-0 border-secondary" v-model="menuItem.item_name" placeholder="nome do prato">
                                <p class="text-danger" v-if="errMsg" v-for="item_name in errMsg.item_name" v-text="item_name"></p>
                            </div>
                            <div class="col-lg-6 col-md-10 d-flex flex-column">
                                <input type="text" class="form-control rounded-0 border-secondary" v-model="menuItem.item_price" placeholder="Valor do prato">
                                <p class="text-danger" v-if="errMsg" v-for="item_price in errMsg.item_price" v-text="item_price"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <label for="tipo" class="fs-5">Tipo do Prato</label>
                                <select class="form-select border-secondary rounded-0" v-model="menuItem.type_id">
                                    <option v-for="item in mealType" :key="item.id_type" :value="item.id_type">{{ item.desc_type }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <label for="name" class="fs-5">Imagem do prato : </label>
                                <input type="file" class="form-control rounded-0 border-secondary" placeholder="nome do prato">
                            </div>
                        </div>
                        <label class="fs-5 mt-3" for="">Status : </label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="radio" name="isative" v-model="menuItem.item_status" value="1">
                            <label class="form-check-label text-uppercase" for="flexRadioDefault1">
                                ativado
                            </label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="radio" name="isative" v-model="menuItem.item_status" value="0" checked>
                            <label class="form-check-label text-uppercase" for="flexRadioDefault2">
                                não ativado
                            </label>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <label for="tipo" class="fs-5">Descrição do parto</label>
                                <textarea class="form-control border-secondary rounded-0" v-model="menuItem.item_desc" placeholder="Descrição do prato.." id="" cols="30" rows="10"></textarea>
                                <p class="text-danger" v-if="errMsg" v-for="item_desc in errMsg.item_desc" v-text="item_desc"></p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn bg-primary rounded-0 px-4">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SideBarComponent from './SideBarComponent.vue'

export default {
    name: 'NewItem',

    components: {
        SideBarComponent
    },

    data() {
        return {
            menuItem: {
                item_desc: null,
                item_price: null,
                tem_image: null,
                type_id: null,
                item_desc: null,
                item_status: null
            },
            mealType: null,
            errMsg: null
        }
    },

    methods: {
        SaveItem() {
           axios.post('/api/save/meal', this.menuItem).then((response) => {
            console.log(response.data)
            if (response.data.status == 500) {
                this.$toast.error(response.data.error);
            } else {
                this.$toast.success(response.data.success);
            }
           }).catch((errors) => {
             console.log(errors.response.data.errors)
             this.errMsg = errors.response.data.errors
           })
        },

        getMealType() {
            axios.get('/api/menu/type').then((response) => {
                console.log(response.data)
                this.mealType = response.data.type
            }).catch((errors) => {
                console.log(errors)
            })
        }
    },

    mounted() {
        this.getMealType()
    }
}

</script>