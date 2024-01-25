<template>
    <div class="container-fluid">
        <SideBarComponent />
        <TabView :scrollable="true">
            <TabPanel [headerStyleClass]="tab-header" header="VENDA">
                <SellInteligence />
            </TabPanel>
            <TabPanel header="PRODUTO">
                <p class="m-0">Hello</p>
            </TabPanel>
        </TabView>
    </div>
</template>
<script>
import SellInteligence from './SellInteligence.vue';
import SideBarComponent from "../SideBarComponent.vue";
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
export default {
    name: 'BiIndex',

    components: {
        SellInteligence,
        TabPanel,
        TabView,
        SideBarComponent
    },
}
</script>
