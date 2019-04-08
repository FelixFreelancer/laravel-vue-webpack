<template lang="pug">
.page__card
	v-server-table(
		:url="`${apiUrl()}/invoices`"
		:columns="datatable.columns"
		:options="datatable.options"
		class="invoice-table")

		a(slot="invoice_number" slot-scope="props" :href="props.row.invoice" target="_blank") {{props.row.invoice_number}}

		router-link(:to="'/app/customer/'+props.row.user_id" slot="user_name" slot-scope="props") {{props.row.user_name}}

		span(slot="invoice_date" slot-scope="props" ) {{timestamp(props.row.invoice_date)}}

		h5(slot="status" slot-scope="props")
			span.badge(:style="{backgroundColor:statusColor.payment_status[props.row.status],color:'#fff'}") {{props.row.status}}

		.table-actions(slot="actions" slot-scope="props")
			
			button(class="table-btn btn btn-light" title="Edit" @click="edit(props.row)")
				i.material-icons edit
</template>
<script>
import c_page from "Components/Page.vue";
import m_common from "Mixins/common";

export default {
    components: {
        c_page
    },
    props: ["customerId", "customerDetails"],
    mixins: [m_common],
    data() {
        var _this = this;
        return {
            toEdit: false,
            datatable: {
                columns: ["invoice_number", "user_name", "type", "total","status","invoice_date"],
                options: {
                    initFilters: {
                        user_id: _this.customerId ? _this.customerId : ""
                    },
                    filterable: ["invoice_number", "user_name", "type", "user_id"],
                    perPage: 25,
                    pagination: {
                        dropdown: false
                    },
                    perPageValues: [],
                    sortable: ["id"],
                    orderBy: {
                        ascending: false
                    },
                    filterByColumn: true,
                    skin: "table table-hover",
                    listColumns: {
                        type: [{
                                id: "1",
                                text: "Shipment"
                            },
                            {
                                id: "2",
                                text: "Quotation"
                            },
                            {
                                id: "3",
                                text: "Membership"
                            }
                        ]
                    },
                    responseAdapter(resp) {
                        return {
                            data: resp.data.invoice,
                            count: resp.data.count
                        };
                    },
                    headings: {
                        invoice_number: 'Invoice ID',
                        user_name: 'Customer'
                    }
                }
            }
        };
    },
    created() {},
    mounted() {}
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
