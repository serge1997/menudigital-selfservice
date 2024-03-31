<template>
    <SideBarComponent />
    <div class="container-fuid d-flex justify-content-between">
        <div class="container">
            <div class="row">
                <div class="col-8 m-auto">
                    <form @submit.prevent="SaveType" class="w-100 p-4">
                        <div class="row p-0">
                            <div class="form-header p-2 text-capitalize shadow-lg rounded-3 text-white w-100">
                                <h6>new category item</h6>
                                <p>Save new category</p>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="d-flex flex-column gap-2">
                                    <label for="type-name">Menu type</label>
                                    <InputText :class="invalid" type="text" id="type-name" v-model="type.desc_type" aria-describedby="type desc" placeholder="Menu type"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <label for="name" class="fs-5">Imagem do prato : </label>
                                <input type="file" class="form-control rounded-0 border-secondary" @change="ImgHandle" placeholder="nome do prato">
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

export default {
    name: 'NewMenuType',

    components: {
        SideBarComponent,
        InputText,
        Button
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
            this.axios.post('/api/meal-type', type).then((response) => {
               this.type.desc_type = ""
               this.$toast.success(response.data);
            }).catch((errors) => {
                console.log(errors)
                errors.response.status === 500 ||  errors.response.status === 400? this.$swal.fire({text: errors.response.data, icon: "warning"}): "";
                //this.$toast.error(errors.response.data)
            })
        }
    }
}

</script>
