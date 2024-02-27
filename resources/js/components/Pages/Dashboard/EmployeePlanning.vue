<template>
  <div class="container-fluid">
    <SideBarComponent />
    <div class="container">
      <div class="row">
        <h4 class="fw-normal d-flex gap-2 align-items-center"><i class="pi pi-calendar"></i>Escala do colaborador</h4>
      </div>
      <div class="col-md-8 d-flex flex-column m-auto">
          <small v-if="load" class="text-center">Carregando...</small>
          <ProgressBar v-if="load" mode="indeterminate" style="height: 6px"></ProgressBar>
      </div>
      <div class="row text-center d-flex">
        <div class="alert alert-secondary day-box d-flex">
            <div v-for="(day, index) in week" class="col p-0">
                <div class="">
                    <p class="text-uppercase fw-medium p-0">{{ day.day }}</p>
                </div>
            </div>
        </div>
        <div v-for="(day, index) in week" class="col">
            <div class="d-flex gap-2 user-name-box mt-3" v-for="(tal, index) in users">
                <button :class="planningClass" :id="`btn-${index}-${day.day}`" class="btn border form-control d-flex flex-column align-items-center btn-new-modal" data-bs-toggle="modal" data-bs-target="#exampleModal" @click="createID(index, day.day)"></button>
               <div class="d-flex flex-column">
                    <span style="cursor: pointer;" @click="deleteUserWorkday(`btn-${index}-${day.day}`)" :data-id="`btn-${index}-${day.day}`" class="delete-btn text-danger"></span>
                    <span data-bs-toggle="modal" data-bs-target="#ModalUpdate" @click="getPlanning(`btn-${index}-${day.day}`)" style="cursor: pointer;" class="action-find"></span>
               </div>
            </div>
        </div>
      </div>
      <div v-if="Object.values(managerAcess).includes(`${position_id}`)" class="row p-3 mt-3 d-flex gap-2">
        <Button @click="createPlaning" class="col-md-4 m-auto" label="Criar Escala" />
        <Button @click="clearPlaning" class="col-md-4 m-auto" label="Limpar Escala" severity="danger"/>
      </div>
      <div class="modal fade" id="exampleModal" aria-hidden="true" aria-labelledby="exampleModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content rounded-0 border-0">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Escolhe Colaborador</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <select v-model="selectedUser" class="p-2 border form-select p-2 create-select">
                <option v-for="user in users" :value="user.name">{{ user.name }}</option>
              </select>
              <div class="col-sm-12">
                <div class="d-flex gap-2 mt-3">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-success text-white" id="basic-addon1">Entrada</span>
                        <input v-model="hour_in" type="time" class="form-control">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text input-badge" id="basic-addon1">Saída</span>
                        <input v-model="hour_out" type="time" class="form-control">
                    </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <Button @click="save" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal" label="Ok"/>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="ModalUpdate" aria-hidden="true" aria-labelledby="ModalUpdate" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content rounded-0 border-0">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Editar</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" v-model="updateData.id">
                <span id="user-name-sp"></span>
              <div class="col-sm-12">
                <div class="d-flex gap-2 mt-3">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-success text-white" id="basic-addon1">Entrada</span>
                        <input v-model="updateData.hour_in" type="time" class="form-control">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text input-badge" id="basic-addon1">Saída</span>
                        <input v-model="updateData.hour_out" type="time" class="form-control">
                    </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <Button @click="update" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal" label="Editar"/>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SideBarComponent from "./SideBarComponent.vue";
import Button from 'primevue/button';
import ProgressBar from 'primevue/progressbar';
import { randTime } from './../../../rand';
import { getAuth } from "./../auth.js";
import Calendar from 'primevue/calendar'
export default {
  name: 'EmployeePlanning',
  components: {
    SideBarComponent,
    Button,
    ProgressBar,
    Calendar
  },
  data(){
    return {
      week: [
        {day: "seg"},
        {day: "ter"},
        {day: "qua"},
        {day: "qui"},
        {day: "sex"},
        {day: "sab"},
        {day: "dom"},
      ],
      users: null,
      planning: [],
      selectedUser: null,
      id_collection: [],
      users_name: [],
      planningData: null,
      load: false,
      planningClass: null,
      managerAcess: localStorage.getItem('managerAccess').split(','),
      position_id: null,
      hour_in: null,
      hour_out: null,
      updateData: {
        hour_in: null,
        hour_out: null,
        id: null
      },
      planningTime: {
        hour_in: [],
        hour_out: []
      },
    }
  },
  methods: {
    async getUsers(){
      const userResponse = await axios.get('/api/users');
      this.users = await userResponse.data;

    },
    createID(index, day){
      localStorage.removeItem('id')
      localStorage.setItem("id", `btn-${index}-${day}`);
      console.log(localStorage.getItem('id'))
    },

    save(){
      let id = localStorage.getItem('id');
      let dynamic = document.getElementById(`${id}`);
      dynamic.setAttribute("class", "")
      this.id_collection.push(id);
      this.users_name.push(this.selectedUser)
      this.planning.push(this.selectedUser)
      this.planningTime.hour_in.push(this.hour_in)
      this.planningTime.hour_out.push(this.hour_out)
      for (let i = 0; i < this.planning.length; i++){
        dynamic.textContent = this.planning[i]
        dynamic.setAttribute("class", 'btn btn-warning border mt-4 p-3 form-control')
      }
    },
    createPlaning(){
        const data = {
            users_name: this.users_name,
            html_id: this.id_collection,
            hour_in: this.planningTime.hour_in,
            hour_out: this.planningTime.hour_out
        }
        this.load = true;
        return new Promise(resolve => {
            setTimeout(() => {
                axios.post('/api/planning', data).then((response) => {
                    this.$swal.fire({text: response.data, icon: 'success'});
                    resolve(true);
                })
                .catch(errors => errors.response.status === 500 && this.$swal.fire({text: errors.response.data, icon: 'warning'}))
                .finally(this.load = false);
            }, randTime())
        })
    },

    loadPlanning(){
        this.load = true;
        return new Promise(resolve => {
            setTimeout(() => {
                axios.get('/api/planning').then( async (planningResponse) => {
                    this.planningData = await planningResponse.data;
                    let box = document.querySelectorAll('.user-name-box button');
                    for (let dt of this.planningData){
                        let dynamic_id = document.getElementById(`${dt.html_id}`);
                        dynamic_id.innerHTML = `${dt.user_name}<span><small>Entrada ${dt.hour_in}</small></span><span><small>Saída ${dt.hour_out}</small></span>`
                        dt.html_id.includes('seg') && dynamic_id.classList.add('bg-primary', 'text-white');
                        dt.html_id.includes('ter') && dynamic_id.classList.add('bg-warning')
                        dt.html_id.includes('sex') && dynamic_id.classList.add('alert','alert-secondary')
                        dt.html_id.includes('qui') && dynamic_id.classList.add('alert','alert-secondary')
                        dt.html_id.includes('sab') && dynamic_id.classList.add('alert','alert-secondary')
                        dt.html_id.includes('qua') && dynamic_id.classList.add('bg-danger', 'text-white')
                        dt.html_id.includes('dom') && dynamic_id.classList.add('bg-success', 'text-white')
                        console.log(dt.html_id)
                    }
                    box.forEach((el, index) => {
                        let span = document.querySelectorAll('.delete-btn');
                        let span_get = document.querySelectorAll('.action-find');
                        let newBtn = document.querySelectorAll('.btn-new-modal');
                        if (el.textContent){
                            span[index].innerHTML = `<i style="font-size: 12px;" class="pi pi-trash"></i>`;
                            span_get[index].innerHTML = `<i style="font-size: 12px;" class="pi pi-pencil text-primary"></i>`;
                            newBtn[index].removeAttribute('data-bs-target')
                        }
                    })
                    resolve(true);
                })
                .catch(errors => console.log(errors))
                .finally(this.load = false);
            }, randTime())
        })
    },
    clearPlaning(){
        this.$swal.fire({
            text: 'Está sendo renicializando a escala. Clique em ok para continuar',
            icon: 'warning',
            showCancelButton: true
        }).then(result => {
            if (result.isConfirmed){
                this.load = true;
                return new Promise(resolve => {
                    setTimeout(() => {
                        axios.delete('/api/planning').then((response) => {
                            this.$swal.fire({text: response.data, icon: 'success'});
                            resolve(true)
                        })
                        .catch(errors => errors.response.status === 500 && this.$swal.fire({text: errors.response.data, icon: 'warning'}))
                        .finally(this.load = false)
                    }, randTime())
                })
            }
        })
    },
    deleteUserWorkday(id){
        this.load = true;
        return new Promise(resolve => {
           setTimeout(() => {
                axios.delete('/api/planning-user/' + id)
                .then((response) => {
                    this.$toast.success(response.data);
                    this.loadPlanning();
                    resolve(true);
                })
                .catch(errors => console.log(errors))
                .finally(() => {
                    this.load = false;
                })
           }, 2000)
        })
    },
    getPlanning(id){
        return new Promise(resolve => {
            axios.get('/api/planning/user/' + id)
            .then((response) => {
                let select = document.querySelector('#user-name-sp');
                for (let dt of response.data){
                    select.innerHTML = dt.user_name
                    this.updateData.hour_in = dt.hour_in;
                    this.updateData.hour_out = dt.hour_out
                    this.updateData.id = dt.id;
                }
                resolve(true);
            })
        })
    },

    update(){
        axios.put('/api/planning/' + this.updateData.id, this.updateData)
        .then((response) => {
            this.$toast.success(response.data)
        })
        .catch(errors => {
            errors.response.status === 500 && this.$swal.fire({text: errors.response.data, icon: 'warning'})
        })
    }
  },
  mounted(){
    this.loadPlanning();
    this.getUsers();
    getAuth().then(result => {
        this.position_id = result.position_id;
    });

  }
}
</script>

<style scoped>
    .input-badge {
        background-color: #e63958;
        color: #fff;
    }

</style>
