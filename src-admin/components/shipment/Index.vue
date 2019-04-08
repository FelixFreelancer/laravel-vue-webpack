<template lang="pug">
    c_page.shipment.page--grey
        h3(slot="title") Shipments
        router-link.btn.btn-sm.btn--icon.btn--round(
            slot="quickAction"
            to="/app/shipments/add"
            title="Add New Shipment"): i.material-icons add
        .shipment__container(slot='content')
            c_ShipmentTable
</template>
<script>
import c_page from "Components/Page.vue";
import m_common from "Mixins/common";
import c_ShipmentTable from "./DataTable.vue";

export default {
	components: {
		c_page,
		c_ShipmentTable
	},
	props: [],
	mixins: [m_common],
	data() {
		var _this = this;
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
.shipment-table {
	font-size: 14px;
}
</style>
