<template>
    <div class="container-fluid">
        <SideBarComponent />
        <TabView :scrollable="true">
            <TabPanel header="ANALISE DE VENDA">
                <SellInteligence />
            </TabPanel>
            <TabPanel header="ANALISE DE CUSTO">
                <CostIntelligence />
            </TabPanel>
        </TabView>
    </div>
</template>
<script>
import SellInteligence from './SellInteligence.vue';
import SideBarComponent from "../SideBarComponent.vue";
import CostIntelligence from './CostIntelligence.vue';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
export default {
    name: 'BiIndex',

    components: {
        SellInteligence,
        CostIntelligence,
        TabPanel,
        TabView,
        SideBarComponent
    },
}
</script>
<style scoped>
.p-tabview{
    text-decoration: none #ff0000;
    color: #ff0000;
}
</style>
