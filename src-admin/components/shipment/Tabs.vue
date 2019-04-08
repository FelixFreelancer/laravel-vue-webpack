<template lang="pug">
    //📣 : Page Template
    c_page.page--grey.shipment(backLink="/app/shipments")
        h3(slot="title")
            span {{shipmentId?'View Shipment':'Add New Shipment'}}
            span {{itemId?' / View Items':''}}
        ul.nav.nav-pills.nav--page(slot="action" v-if="shipmentId")
            li.nav-item
                router-link.nav-link(:to="'/app/shipments/'+shipmentId")
                    i.material-icons info
                    span Shipment Info
            li.nav-item
                router-link.nav-link(:to="'/app/shipments/'+shipmentId+'/items'")
                    i.material-icons widgets
                    span Items in Shipment
        //📣 : Page Content
        .shipment__container(slot='content')
            router-view
</template>
<script>
import c_page from "Components/Page.vue";
import m_common from "Mixins/common";
export default {
	components: {
		c_page
	},
	props: ["shipmentId", "itemId"],
	mixins: [m_common],
	data() {
		return {};
	},
	created() {},
	mounted() {},
	methods: {}
};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";
.shipment__container {
	padding: 20px;
}
</style>
