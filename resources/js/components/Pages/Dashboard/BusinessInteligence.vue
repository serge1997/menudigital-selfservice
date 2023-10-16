<template>
    <div class="row">
        <div class="col-2">
            <SideBarComponent/>
        </div>
        <div class="col-8">
            <div class="card-box d-flex justify-content-between">
                <div class="card">
                    <div class="card-header">
                        <h6>qty cliente</h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h6>qty total meal</h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h6>Total canceled</h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h6>Venda Total</h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h6>Venda Entrada</h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h6>Venda Prato</h6>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h6>Venda sobremesa</h6>
                    </div>
                </div>
            </div>
            <div class="body">
                grafic
            </div>
        </div>
    </div>
</template>
<script>

import SideBarComponent from './SideBarComponent.vue';
export default {
    name: 'BusinessInteligence',

    components: {
        SideBarComponent
    },

    data() {

        return {

        }
    }
}

</script>