<template>
    <div class="container-fuid d-flex justify-content-between">
        <div class="position-fixed">
            <SideBarComponent class="text-center"></SideBarComponent>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-8 m-auto">
                    <h5 class="text-center p-2">Salvar novo Tipo do menu</h5>
                    <form @submit.prevent="SaveType" class="shadow w-100 p-4">
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <label for="name" class="fs-5">Tipo do menu : </label>
                                <input type="text" class="form-control rounded-0 border-secondary" v-model="type.desc_type" placeholder="Tipo do menu">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <label for="name" class="fs-5">Imagem do prato : </label>
                                <input type="file" class="form-control rounded-0 border-secondary" @change="ImgHandle" placeholder="nome do prato">
                            </div>
                        </div>
                        <label class="fs-5 mt-3" for="">Status : </label>
                        <!--<div class="form-check form-switch">
                            <input class="form-check-input" type="radio" name="isative" value="1">
                            <label class="form-check-label text-uppercase" for="flexRadioDefault1">
                                ativado
                            </label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="radio" name="isative" value="0" checked>
                            <label class="form-check-label text-uppercase" for="flexRadioDefault2">
                                não ativado
                            </label>
                        </div>-->
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
    name: 'NewMenuType',

    components: {
        SideBarComponent
    },

    data() {
        return {
          type: {
            desc_type: null,
            foto_type: null
          }
        }
    },

    methods: {
        ImgHandle(event){
            this.type.foto_type = event.target.files[0];
        },

        SaveType() {
            let type = new FormData()
            type.append('desc_type', this.type.desc_type)
            type.append('foto_type', this.type.foto_type)
            this.axios.post('/api/save/type', type).then((response) => {
               this.type.desc_type = ""
               this.$toast.success(response.data);
            }).catch((errors) => {
                console.log(errors)
            })
        }
    }
}

</script>