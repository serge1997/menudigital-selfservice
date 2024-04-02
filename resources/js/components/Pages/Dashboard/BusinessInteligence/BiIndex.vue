<template>
    <SideBarComponent />
    <div class="container-fluid">
        <TabView :scrollable="true">
            <TabPanel :header="`${ $t('bi.painels_title.one') }`">
                <SellInteligence />
            </TabPanel>
            <TabPanel :header="`${ $t('bi.painels_title.two') }`">
                <CostIntelligence />
            </TabPanel>
            <TabPanel :header="`${ $t('bi.painels_title.three') }`">
                <ExpenseIntelligence />
            </TabPanel>
        </TabView>
    </div>
</template>
<script>
import SellInteligence from './SellInteligence.vue';
import SideBarComponent from "../SideBarComponent.vue";
import CostIntelligence from './CostIntelligence.vue';
import ExpenseIntelligence from './ExpenseIntelligence.vue';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
export default {
    name: 'BiIndex',

    components: {
        SellInteligence,
        CostIntelligence,
        ExpenseIntelligence,
        TabPanel,
        TabView,
        SideBarComponent
    },
}
</script>
<style scoped>
.p-tabview-panels{
    color: #333;
}
</style>
