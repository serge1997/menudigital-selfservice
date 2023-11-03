<template>
    <div class="container">
        <div class="modal fade" id="ModelItemSetting" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Menu Edition</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="w-100">
                            <div class="col-md-10 m-auto d-flex justify-content-between p-3">
                                <input type="search" placeholder="Search item here..." v-model="search" class="form-control rounded-0 search-input">
                                <button class="btn border rounded-0" id="search-icon"> 
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" 
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search">
                                        <circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="w-100 m-auto d-flex justify-content-between flex-wrap">
                            <button v-for="type in MenuType" class="type-btn border border-success px-4 alert alert-success w-25 rounded-0 py-4 shadow d-flex justify-content-between">
                                <div>
                                    <img class="type-img" :src="'/img/type/'+ type.foto_type" alt="">
                                    {{ type.desc_type }} 
                                </div> 
                                <span class="fw-medium px-2 border border-secondary py-1">{{ type.item_qty }}</span>
                            </button>
                            <button class="border border-primary px-4 alert alert-primary w-25 rounded-0 py-4 shadow">Principal</button>
                        </div>
                        <div v-if="ShowForm" class="w-100 edit-form">
                            <div class="">
                                <p class="text-center"><span class="fw-bold">Edit item do menu</span> <br>
                                    <small class="text-center">Status do item, ative caso o item não está mais disponivel no estoque</small>
                                </p>
                            </div>
                            <div class="form w-100">
                                <form class="d-flex flex-column justify-content-center w-100 p-2" v-for="item in itemToEdit">
                                    <div class="form-group w-100 d-flex flex-column align-items-center p-2">
                                        <input type="hidden" :value="item.id" id="item-id">
                                        <div class="w-100">
                                            <p class="text-danger w-75 m-auto" v-if="errMsg" v-for="msg in errMsg.item_name" v-text="msg"></p>
                                            <input type="text" class="m-auto form-control w-75 rounded-0 border-secondary" :value="item.item_name" id="item-name">
                                        </div>
                                        <div class="w-100">
                                            <p class="text-danger w-75 m-auto" v-if="errMsg" v-for="msg in errMsg.item_price" v-text="msg"></p>
                                            <input type="text" class="m-auto mt-2 form-control w-75 rounded-0 border-secondary" :value="item.item_price" id="item-price">
                                        </div>
                                    </div>
                                    <div class="form-group w-100 d-flex flex-column justify-content-center align-items-center p-2">
                                        <p class="text-danger w-75 m-auto" v-if="errMsg" v-for="msg in errMsg.item_desc" v-text="msg"></p>
                                       <textarea class="form-control w-75 rounded-0 m- border-secondary" id="item-desc" cols="30" rows="5" :value="item.item_desc"></textarea>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="border-top">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Item ID</th>
                                        <th>Item name</th>
                                        <th>Item Price</th>
                                        <th>Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody v-if="!SearchResult">
                                    <tr v-for="item in MenuItems">
                                        <td v-if="item.item_rupture" :class="isRuptureClass">{{ item.id }}</td>
                                        <td v-else>{{ item.id }}</td>
                                        <td v-if="item.item_rupture" :class="isRuptureClass">{{ item.item_name }}</td>
                                        <td v-else>{{ item.item_name }}</td>
                                        <td v-if="item.item_rupture" :class="isRuptureClass">{{ item.item_price }}</td>
                                        <td v-else>{{ item.item_price }}</td>
                                        <td v-if="item.item_rupture" :class="isRuptureClass">{{ item.desc_type }}</td>
                                        <td v-else>{{ item.desc_type }}</td>
                                        <td>
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
                                                            <button v-if="item.item_rupture" @click.prevent="SetRupture(item.id)" class="btn alert alert-success p-2 w-100 rounded-0">Desactive Rupture</button>
                                                            <button v-else="item.item_rupture" @click.prevent="SetRupture(item.id)" class="btn alert alert-warning p-2 w-100 rounded-0">Active Rupture</button>
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
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr v-for="item in SearchResult">
                                        <td v-if="item.item_rupture" :class="isRuptureClass">{{ item.id }}</td>
                                        <td v-else>{{ item.id }}</td>
                                        <td v-if="item.item_rupture" :class="isRuptureClass">{{ item.item_name }}</td>
                                        <td v-else>{{ item.item_name }}</td>
                                        <td v-if="item.item_rupture" :class="isRuptureClass">{{ item.item_price }}</td>
                                        <td v-else>{{ item.item_price }}</td>
                                        <td v-if="item.item_rupture" :class="isRuptureClass">{{ item.desc_type }}</td>
                                        <td v-else>{{ item.desc_type }}</td>
                                        <td>
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
                                                            <button v-if="item.item_rupture" @click.prevent="SetRupture(item.id)" class="btn alert alert-success p-2 w-100 rounded-0">Desactive Rupture</button>
                                                            <button v-else="item.item_rupture" @click.prevent="SetRupture(item.id)" class="btn alert alert-warning p-2 w-100 rounded-0">Active Rupture</button>
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
export default {
    name: 'SettingMenuComponent',

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
            errMsg: null
        }
    },

    methods: {
        ShowEditForm(id){
            axios.get(`/api/edit/menu-item/${id}`).then((response) => {
                console.log(response.data);
                this.itemToEdit = response.data
                this.ShowForm = !this.ShowForm;
            }).catch((errors) => {
                console.log(errors);
            })
        },

        getItem(){
            axios.get('/api/menu/items').then((response) => {
                this.MenuItems = response.data.items
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

        SetRupture(id){
            axios.post(`/api/set-rupture/${id}`).then((response) => {
                console.log(response.data)
                return this.getItem();
            }).catch((errors) => {
                console.log(errors)
            })
        },

        ToDelete(id){
            this.$swal.fire({
                title: 'Deseja realmente apagar o item do Menu ?',
                text: 'O item não vai estar mais disponivel no cardapio. A recuperação desse item é possivel com o fornecedor do sistema',
                showCancelButton: true,
                confirmButtonColor: '#42b883',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Deletar item',
                cancelButtonText: 'Cancelar'
            }).then((result) =>{
                if (result.isConfirmed){
                    axios.post(`/api/delete/menu-item/${id}`).then((response) => {
                        this.$swal(response.data);
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
            
            axios.post('/api/item-menu/update', this.updateItems).then((response) => {
                this.$toast.success(response.data)
                this.ShowForm = !this.ShowForm;
            }).catch((errors) => {
                this.errMsg = errors.response.data.errors;
            })
        },

        async getSearchResult(){
            await axios.get('/api/search/', {params: {search: this.search}}).then((response) =>{
                this.SearchResult = response.data.items
                console.log(response.data)
                if (this.SearchResult.length < 1){
                    this.notFound = "Não há item corespondante";
                }else{
                    this.notFound = ""
                }
                
                console.log(response.data);
            })
        }
    },

    mounted(){

        axios.get('/api/get/menutype').then((response) => {
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
    background-color: #fff3cd;
}

</style>