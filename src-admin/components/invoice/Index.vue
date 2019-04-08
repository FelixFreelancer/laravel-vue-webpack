<template lang="pug">
c_page.country.page--grey
	h3(slot="title") Invoices
	download_excel.btn.btn-sm.btn--icon.btn--round(:data="json_data" type="csv" :fields="json_fields" name="Invoice.xls" slot="quickAction")
		i.material-icons(title="Download") cloud_download
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
            json_fields: {
                'Invoice ID': {
			        field: 'invoice_number',
			        callback: (value) => {
			            return  (value) ? value : '';
			        }
			    },
                'Customer': {
			        field: 'user_name',
			        callback: (value) => {
			            return  (value) ? value : '';
			        }
			    },
				'Type':  {
			        field: 'type',
			        callback: (value) => {
			            return  (value) ? value : '';
			        }
			    },
                'Total': {
			        field: 'total',
			        callback: (value) => {
			            return  (value) ? value : '';
			        }
			    },
                'Status': {
			        field: 'status',
			        callback: (value) => {
			            return  (value) ? value : '';
			        }
			    },
                'Invoice Date': {
                    field: 'invoice_date',
                    callback: (value) => {
                        return (value) ? this.timestamp(value) : '';
                    }
                }
            },
            datatable: {
                columns: ["invoice_number", "user_name", "type", "total"],
                options: {
                    filterable: ["invoice_number", "user_name", "type"],
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
                        type: [{
                                id: "1",
                                text: "Shipment"
                            },
                            {
                                id: "2",
                                text: "Quotation"
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
    mounted() {
        this.getInvoice();
    },
    methods: {
        getInvoice() {
            var _this = this;
            this.apiGet({
                url: `${this.apiUrl()}/invoices`,
                success(data) {
                    _this.json_data = data.invoice;
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
