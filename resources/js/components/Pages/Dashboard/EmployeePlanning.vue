<template>
  <div class="container-fluid">
    <SideBarComponent />
    <div class="container">
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
          <button v-for="(tal, index) in users" :id="`btn-${index}-${day.day}`" class="btn bg-white border mt-4 p-3 form-control" data-bs-toggle="modal" data-bs-target="#exampleModal" @click="createID(index, day.day)"></button>
        </div>
      </div>
      <div class="row p-3 mt-3 d-flex gap-2">
        <Button @click="createPlaning" class="col-md-4 m-auto" label="Criar Escala" />
        <Button @click="clearPlaning" class="col-md-4 m-auto" label="Limpar Escala" severity="danger"/>
      </div>
      <div class="modal fade" id="exampleModal" aria-hidden="true" aria-labelledby="exampleModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Escolhe usuario</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <select v-model="selectedUser" class="p-2 form-control">
                <option v-for="user in users" :value="user.name">{{ user.name }}</option>
              </select>
            </div>
            <div class="modal-footer">
              <button @click="save" class="btn btn-primary" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal">Ok</button>
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

export default {
  name: 'EmployeePlanning',
  components: {
    SideBarComponent,
    Button,
    ProgressBar
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
      users: [
        {name: "Pedro"},
        {name: "David"},
        {name: "Silva"},
        {name: "Matheus"},
        {name: "Diana"},
      ],
      planning: [],
      selectedUser: null,
      id_collection: [],
      users_name: [],
      planningData: null,
      load: false
    }
  },
  methods: {
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
      for (let i = 0; i < this.planning.length; i++){
        dynamic.textContent = this.planning[i]
        dynamic.setAttribute("class", 'btn btn-warning border mt-4 p-3 form-control')
      }
      console.log(this.users_name);
      console.log(this.id_collection)
    },
    createPlaning(){
        const data = {
            users_name: this.users_name,
            html_id: this.id_collection
        }
        this.load = true;
        return new Promise(resolve => {
            setTimeout(() => {
                this.axios.post('/api/planning', data).then((response) => {
                    this.$swal.fire({text: response.data, icon: 'success'});
                })
                .catch(errors => console.log(errors))
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
                    for (let dt of this.planningData){
                        let dynamic_id = document.getElementById(`${dt.html_id}`);
                        dynamic_id.textContent = dt.user_name
                        console.log(dt.html_id)
                    }
                    resolve(true);
                })
                .catch(errors => console.log(errors))
                .finally(this.load = false);

                console.log(this.planningData)
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
                        .catch(errors => console.log(errors))
                        .finally(() => {
                            this.load = false;
                            return this.loadPlanning()
                        })
                    }, randTime())
                })
            }
        })
    }
  },
  mounted(){
    this.loadPlanning();
  }
}
</script>

<style>

</style>
