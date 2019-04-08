<template lang="pug">
//📣 : Page Template
c_page.transaction.page--grey
	h3(slot="title") Transactions
	download_excel.btn.btn-sm.btn--icon.btn--round(:data="json_data" type="csv" :fields="json_fields" name="Transaction.xls" slot="quickAction")
		i.material-icons(title="Download") cloud_download

	//📣 : Page Content
	.transaction__container(slot='content')

		.page__card
			v-server-table(
				:url="`${apiUrl()}/transaction`"
				:columns="datatable.columns"
				:options="datatable.options"
				ref="transactionTable"
				class="transaction-table")

				span(slot="created_date" slot-scope="props") {{ timestamp(props.row.created_date) }}

				router-link(:to="'/app/customer/'+props.row.user_id" slot="name" slot-scope="props") {{props.row.name}}
				router-link(:to="'/app/shipments/'+props.row.entity_id" slot="entity_name" slot-scope="props") {{props.row.entity_name}}
</template>
<script>
import c_page from "Components/Page.vue";
import m_common from "Mixins/common";

export default {
    components: {
        c_page
    },
    props: [],
    mixins: [m_common],
    data() {
        var _this = this;
        return {
            toEdit: false,
            json_data: [],
            json_fields: {
                'Customer': {
			        field: 'name',
			        callback: (value) => {
			            return  (value) ? value : '';
			        }
			    },
                'Transaction ID': {
			        field: 'transaction_id',
			        callback: (value) => {
			            return  (value) ? value : '';
			        }
			    },
                'Entity': {
			        field: 'entity_name',
			        callback: (value) => {
			            return  (value) ? value : '';
			        }
			    },
                'Amount': {
			        field: 'amount',
			        callback: (value) => {
			            return  (value) ? value : '';
			        }
			    },
                'Status': {
			        field: 'payment_gateway_status',
			        callback: (value) => {
			            return  (value) ? value : '';
			        }
			    },
                'Payment Type': {
			        field: 'payment_type',
			        callback: (value) => {
			            return  (value) ? value : '';
			        }
			    },
                'Gateway': {
			        field: 'payment_gateway_type',
			        callback: (value) => {
			            return  (value) ? value : '';
			        }
			    },
                'Date': {
			        field: 'created_date',
			        callback: (value) => {
			            return  (value != '') ? _this.timestamp(value) : '';
			        }
			    }
            },
            datatable: {
                columns: ["name", "transaction_id", "entity_name", "amount", "payment_gateway_status", "payment_type", "payment_gateway_type", "created_date"],
                options: {
                    filterable: ["name", "transaction_id", "amount", "payment_type", "payment_gateway_type"],
                    perPage: 10,
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
                        payment_type: [],
                        payment_gateway_type: []
                    },
                    responseAdapter(resp) {
                        var paymentObject = [];
                        if (_this.datatable.options.listColumns.payment_type.length == 0) {
                            resp.data.payment_type.forEach(item => {
                                _this.datatable.options.listColumns.payment_type.push({
                                    id: item.key,
                                    text: item.value
                                });
                            });
                        }
                        if (_this.datatable.options.listColumns.payment_gateway_type.length == 0) {
                            resp.data.payment_gateway.forEach(item => {
                                _this.datatable.options.listColumns.payment_gateway_type.push({
                                    id: item.key,
                                    text: item.value
                                });
                            });
                        }
                        return {
                            data: resp.data.payment,
                            count: resp.data.count
                        };
                    },
                    headings: {
                        transaction_id: "Transaction ID",
                        entity_name: "Entity",
                        payment_gateway_status: "Status",
                        name: "Customer",
                        payment_type: "Payment Type",
                        created_date: "Date",
                        payment_gateway_type: "Gateway"
                    }
                }
            }
        };
    },
    created() {},
    mounted() {
        this.getTransaction();
    },
    methods: {
        getTransaction() {
            var _this = this;
            this.apiGet({
                url: `${this.apiUrl()}/transaction`,
                success(data) {
                    _this.json_data = data.payment;
                }
            });
        },
        add() {
            this.$root.$emit("bv::show::modal", "upsertModalUser");
        },
        modalClose() {
            this.$refs.userTable.refresh();
            this.$root.$emit("bv::hide::modal", "upsertModalUser");
        }
    }
};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";
.transaction__container {
    padding: 20px;
}
.transaction-table {
    font-size: 14px;
}
</style>
