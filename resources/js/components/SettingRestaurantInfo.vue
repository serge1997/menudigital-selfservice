<template>
    <div class="modal fade" id="ModalRestaurantinfo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content rounded-0">
                <div class="modal-header form-header text-white rounded-0">
                    <h5 class="modal-title" id="exampleModalLabel">Registe The General Restaurant Information</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    <div class="modal-body p-4">
                        <div class="input-group fw-medium">
                            <div class="w-50">
                                <label for="">Restaurant Name: </label>
                            </div>
                            <div class="w-50">
                                <label class="px-2 fw-medium"  for="">Restaurant E-mail: </label>
                            </div>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control border-secondary rounded-0 col-2">
                            <div class="px-2"></div>
                            <input type="text" class="form-control border-secondary rounded-0 col-2">
                        </div>
                        <div class="input-group mt-2">
                            <div class="w-50">
                                <label class="text-capitalize fw-medium" for="">City : </label>
                            </div>
                            <div class="w-50">
                                <label class="px-2 text-capitalize fw-medium"  for="">neighborhoods: </label>
                            </div>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control border-secondary rounded-0 col-2">
                            <div class="px-2"></div>
                            <input type="text" class="form-control border-secondary rounded-0 col-2">
                        </div>
                        <div class="input-group mt-3 p-1">
                            <label class="fw-medium">Logo: </label>
                            <span class="px-2"></span>
                            <input type="file" class="form-control border-secondary rounded-0 col-2">
                        </div>
                        <div class="input-group mt-2">
                            <div class="w-50">
                                <label class="text-capitalize fw-medium" for="">Margem custo fixo (%) : </label>
                            </div>
                            <div class="w-50">
                                <label class="px-2 text-capitalize fw-medium"  for="">Margem custo variavel (%): </label>
                            </div>
                        </div>
                        <div class="input-group">
                            <input type="number" class="form-control border-secondary rounded-0 col-2">
                            <div class="px-2"></div>
                            <input type="number" class="form-control border-secondary rounded-0 col-2">
                        </div>
                        <div class="input-group mt-2">
                            <div class="w-50">
                                <label class="text-capitalize fw-medium" for="">Hora abertura : </label>
                            </div>
                            <div class="w-50">
                                <label class="px-2 text-capitalize fw-medium"  for="">hora fechamento: </label>
                            </div>
                        </div>
                        <div class="input-group">
                            <input type="time" class="form-control border-secondary rounded-0 col-2">
                            <div class="px-2"></div>
                            <input type="time" class="form-control border-secondary rounded-0 col-2">
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn text-white bg-dark rounded-0">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'SettingRestaurantInfo',

    data(){
        return{

        }
    },

    methods: {

    }
}
</script>
