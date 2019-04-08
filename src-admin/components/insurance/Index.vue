<template lang="pug">
c_page.country.page--grey
	h3(slot="title") Insurance
	.country__container(slot='content')
		c_dataTable
</template>
<script>
import c_page from "Components/Page.vue";
import m_common from "Mixins/common";
import c_dataTable from "./DataTable.vue";

export default {
    components: {
        c_page,
        c_dataTable
    },
    props: [],
    mixins: [m_common],
    data() {
        return {
            toEdit: false,
            json_data: [],
						datatable: {
								columns: ["shipment_name", "user_name","insurance_charge","created_at"],
								options: {
										// initFilters: {
										//     user_id: _this.customerId ? _this.customerId : ""
										// },
										filterable: ["shipment_name", "user_name"],
										perPage: 25,
										pagination: {
												dropdown: false
										},
										perPageValues: [],
										sortable: ["shipment_id"],
										orderBy: {
												ascending: false
										},
										filterByColumn: true,
										skin: "table table-hover",
										responseAdapter(resp) {
												return {
														data: resp.data.insurance,
														count: resp.data.count
												};
										},
										headings: {
												shipment_name: 'Shipment Name',
												user_name: 'Customer Name',
												insurance_charge: 'Insurance Charge',
												created_at: 'Date'
										}
								}
						}
        };
    },
    created() {},
    mounted() {
        this.getInsurance();
    },
    methods: {
        getInsurance() {
            var _this = this;
            this.apiGet({
                url: `${this.apiUrl()}/insurance`,
                success(data) {
                    _this.json_data = data.insurance;
                }
            });
        },
    }
};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";
.country__container {
    padding: 20px;
}
.country-table {
    font-size: 14px;
}
</style>
