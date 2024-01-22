<template>
    <div class="container">
        <div class="modal fade" id="ModelItemSetting" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Menu Edition</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="w-100 p-3 d-flex justify-content-center gap-2">
                            <div v-for="type in MenuType" class="col-md-2 shadow">
                                <div class="w-100 card">
                                    <div class="card-header text-center border-0">
                                        <h6 class="text-uppercase">{{ type.desc_type }}</h6>
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="w-100 d-flex gap-2">
                                            <small>Total item</small>
                                            <Badge :value="type.item_qty"></Badge>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-100 py-2 d-flex">
                            <Toolbar class="w-100">
                                <template class="w-100 bg-dark" #start>
                                    <Button @click="$router.push({ name: 'Stock'})" label="Stock panel" data-bs-dismiss="modal" icon="pi pi-external-link"/>
                                    <Button @click="$router.push({ name: 'OperadorPanel'})" label="Principal panel" data-bs-dismiss="modal" icon="pi pi-external-link"/>
                                </template>
                                <template class="w-100 bg-dark" #center>
                                    <span class="w-100 p-input-icon-left">
                                        <i class="pi pi-search" />
                                        <InputText icon="pi pi-search" type="search" style="width: 26rem" placeholder="Search item here..." v-model="search"/>
                                    </span>
                                </template>
                                <template #end>
                                    <div>
                                        <Button @click="$router.push({ name: 'NewItem'})" label="New" data-bs-dismiss="modal" icon="pi pi-plus"/>
                                    </div>
                                </template>
                            </Toolbar>
                        </div>
                        <div v-if="ShowForm" class="w-100 edit-form">
                            <div class="">
                                <p class="text-center"><span class="fw-bold">Edit item do menu</span> <br>
                                    <small class="text-center">Status do item, ative caso o item não está mais disponivel no estoque</small>
                                </p>
                            </div>
                            <div class="form w-100">
                                <form class="d-flex flex-column justify-content-center w-100 p-2">
                                    <div class="form-group w-100 d-flex flex-column align-items-center p-2">
                                        <input type="hidden" :value="itemToEdit.id" id="item-id">
                                        <div class="w-100">
                                            <p class="text-danger w-75 m-auto" v-if="errMsg" v-for="msg in errMsg.item_name" v-text="msg"></p>
                                            <input type="text" class="m-auto form-control w-75 rounded-0 border-secondary" :value="itemToEdit.item_name" id="item-name">
                                        </div>
                                        <div class="w-100">
                                            <p class="text-danger w-75 m-auto" v-if="errMsg" v-for="msg in errMsg.item_price" v-text="msg"></p>
                                            <input type="text" class="m-auto mt-2 form-control w-75 rounded-0 border-secondary" :value="itemToEdit.item_price" id="item-price">
                                        </div>
                                    </div>
                                    <div class="form-group w-100 d-flex flex-column justify-content-center align-items-center p-2">
                                        <p class="text-danger w-75 m-auto" v-if="errMsg" v-for="msg in errMsg.item_desc" v-text="msg"></p>
                                       <textarea class="form-control w-75 rounded-0 m- border-secondary" id="item-desc" cols="30" rows="5" :value="itemToEdit.item_desc"></textarea>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="border-top">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="p-3">Item ID</th>
                                        <th class="p-3">Item name</th>
                                        <th class="p-3">Item Price</th>
                                        <th class="p-3">Type</th>
                                        <th class="p-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody v-if="!SearchResult">
                                    <tr v-for="item in MenuItems">
                                        <td class="p-3" v-if="item.item_rupture" :class="isRuptureClass">{{ item.id }}</td>
                                        <td class="p-3" v-else>{{ item.id }}</td>
                                        <td class="p-3" v-if="item.item_rupture" :class="isRuptureClass">{{ item.item_name }}</td>
                                        <td class="p-3" v-else>{{ item.item_name }}</td>
                                        <td class="p-3" v-if="item.item_rupture" :class="isRuptureClass">{{ item.item_price }}</td>
                                        <td class="p-3" v-else>{{ item.item_price }}</td>
                                        <td class="p-3" v-if="item.item_rupture" :class="isRuptureClass">{{ item.desc_type }}</td>
                                        <td class="p-3" v-else>{{ item.desc_type }}</td>
                                        <td class="p-3">
                                            <button class="btn" @click="ShowEditForm(item.id)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                                </svg>
                                            </button>

                                            <div class="btn-group">
                                                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                </button>
                                                <ul class="dropdown-menu p-0 rounded-0 shadow">
                                                    <h6 class="text-capitalize fw-medium p-2">Item Status</h6>
                                                    <div class="">
                                                        <li>
                                                            <button v-if="item.item_rupture" @click.prevent="SetRupture(item.id, item.item_rupture)" class="btn alert alert-success p-2 w-100 rounded-0">Desactive Rupture</button>
                                                            <button v-else="item.item_rupture" @click.prevent="SetRupture(item.id, item.item_rupture)" class="btn alert alert-warning p-2 w-100 rounded-0">Active Rupture</button>
                                                            <button class="btn"></button>
                                                        </li>
                                                    </div>
                                                </ul>
                                            </div>
                                            <button @click="ToDelete(item.id)" class="btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#dc3545"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line>
                                                </svg>
                                            </button>
                                            <button @click="$emit('ShowTechnicalFiche', item.id)" class="btn" data-bs-toggle="modal" data-bs-target="#ModalSohwFiche">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr v-for="item in SearchResult">
                                        <td class="p-3" v-if="item.item_rupture" :class="isRuptureClass">{{ item.id }}</td>
                                        <td class="p-3" v-else>{{ item.id }}</td>
                                        <td class="p-3" v-if="item.item_rupture" :class="isRuptureClass">{{ item.item_name }}</td>
                                        <td class="p-3" v-else>{{ item.item_name }}</td>
                                        <td class="p-3" v-if="item.item_rupture" :class="isRuptureClass">{{ item.item_price }}</td>
                                        <td class="p-3" v-else>{{ item.item_price }}</td>
                                        <td class="p-3" v-if="item.item_rupture" :class="isRuptureClass">{{ item.desc_type }}</td>
                                        <td class="p-3" v-else>{{ item.desc_type }}</td>
                                        <td class="p-3">
                                            <button class="btn" @click="ShowEditForm(item.id)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                                </svg>
                                            </button>
                                            <div class="btn-group">
                                                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                </button>
                                                <ul class="dropdown-menu p-0 rounded-0 shadow">
                                                    <h6 class="text-capitalize fw-medium p-2">Item Status</h6>
                                                    <div class="">
                                                        <li>
                                                            <button v-if="item.item_rupture" @click.prevent="SetRupture(item.id, item.item_rupture)" class="btn alert alert-success p-2 w-100 rounded-0">Desactive Rupture</button>
                                                            <button v-else="item.item_rupture" @click.prevent="SetRupture(item.id, item.item_rupture)" class="btn alert alert-warning p-2 w-100 rounded-0">Active Rupture</button>
                                                            <button class="btn"></button>
                                                        </li>
                                                    </div>
                                                </ul>
                                            </div>
                                            <button @click="ToDelete(item.id)" class="btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#dc3545"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line>
                                                </svg>
                                            </button>
                                            <button @click="$emit('ShowTechnicalFiche', item.id)" class="btn" data-bs-toggle="modal" data-bs-target="#ModalSohwFiche">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button @click="updatedMenuItem" type="button" class="btn btn-primary rounded-0">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SellReportComponent from "@/components/SellReportComponent.vue";
import Toolbar from "primevue/toolbar";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Badge from "primevue/badge";

export default {
    name: 'SettingMenuComponent',
    components: {
        SellReportComponent,
        Toolbar,
        Button,
        InputText,
        Badge
    },

    watch: {
        search(before, after){
            this.getSearchResult()
        }
    },

    data() {
        return {
            MenuType: null,
            MenuItems: null,
            ShowForm: false,
            itemToEdit: null,
            isRupture: false,
            isRuptureClass: null,
            updateItems: {
                item_id: null,
                item_name: null,
                item_price: null,
                item_desc: null
            },
            search: null,
            SearchResult: null,
            errMsg: null,
            products: null
        }
    },

    methods: {
        ShowEditForm(id){
            axios.get(`/api/menu-items/${id}`).then((response) => {
                console.log(response.data);
                this.itemToEdit = response.data
                this.ShowForm = !this.ShowForm;
            }).catch((errors) => {
                console.log(errors);
            })
        },

        getItem(){
            axios.get('/api/menu-items').then((response) => {
                this.MenuItems = response.data
                this.MenuItems.forEach(e => {
                    if (e.item_rupture){
                        this.isRuptureClass = "Myalert-warning"

                        console.log("e is" + e)
                    }
                });

            }).catch((errors) => {
                console.log(errors)
            })
        },

        SetRupture(id, item_rupture){
            let same = "clique em ok para continuar";
            let message_alter = item_rupture == true ? "Esta sendo habilitado esse item. " + same : "Está sendo desabilitado esse item. " + same ;
            this.$swal.fire({
                text: message_alter,
                showCancelButton: true,
                icon: 'warning'
            }).then((result) => {
                if (result.isConfirmed){
                    axios.put(`/api/menu-items-rupture/${id}`).then((response) => {
                        this.$swal({text: response.data})
                        return this.getItem();
                    }).catch((errors) => {
                        console.log(errors)
                     })
                }
            })
        },

        ToDelete(id){
            this.$swal.fire({
                text: 'O item não vai estar mais disponivel no cardapio. A recuperação desse item é possivel com o fornecedor do sistema',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#42b883',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Deletar item',
                cancelButtonText: 'Cancelar'
            }).then((result) =>{
                if (result.isConfirmed){
                    axios.delete(`/api/menu-items/${id}`).then((response) => {
                        this.$swal.fire({text: response.data, icon: 'success'});
                        this.getItem();
                    })
                }
            })
        },

        updatedMenuItem(){
            let id = document.getElementById('item-id').value;
            let item_name = document.getElementById('item-name').value;
            let item_price = document.getElementById('item-price').value;
            let item_desc = document.getElementById('item-desc').value;

            this.updateItems.item_desc = item_desc;
            this.updateItems.item_id = id;
            this.updateItems.item_price = item_price;
            this.updateItems.item_name = item_name;

            axios.put('/api/menu-items', this.updateItems).then((response) => {
                this.$toast.success(response.data)
                this.ShowForm = !this.ShowForm;
                return this.getItem()
            }).catch((errors) => {
                this.errMsg = errors.response.data.errors;
            })
        },

        getSearchResult(){
            axios.get('/api/menu-items-search/', {params: {search: this.search}}).then((response) =>{
                this.SearchResult = response.data
                console.log(response.data)
                if (this.SearchResult.length < 1){
                    this.notFound = "Não há item corespondante";
                }else{
                    this.notFound = ""
                }

                console.log(response.data);
            })
        },
    },

    mounted(){
        axios.get('/api/meal-types/menu-items').then((response) => {
            console.log(response.data)
            this.MenuType = response.data
        }).catch((errors) => {
            console.log(errors)
        });
        this.getItem();


    }
}
</script>

<style scoped>

.type-btn {
    margin-left: 2px;
}

.type-img {
    width: 30px;
}

.modal-body{
    width: 100%;
    height: 600px;
    overflow: scroll;
}

.edit-form {
    transition: all .3s ease;
}

.Myalert-warning{
    background-color: #f8d7da;
}

</style>
