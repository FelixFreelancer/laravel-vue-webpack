<template lang="pug">
.page__card
	v-server-table(
		:url="`${apiUrl()}/quotations`"
		:columns="datatable.columns"
		:options="datatable.options"
		id="quotationTable"
		ref="quotationTable"
		class="quote-table")

		span(slot="status" slot-scope="props")
			h5: .badge(:style="{backgroundColor:statusColor.quotation[props.row.status]}") {{props.row.status_value}}

		span(slot="requested_on" slot-scope="props") {{ timestamp(props.row.requested_on) }}
		router-link(:to="'/app/customer/'+props.row.user_id" slot="user_name" slot-scope="props") {{props.row.user_name}}
		router-link(:to="'/app/quotations/'+props.row.quotation_number" slot="quotation_number" slot-scope="props") {{props.row.quotation_number}}

		.table-actions(slot="actions" slot-scope="props")
			router-link(class="table-btn btn btn-light" title="Edit" :to="'/app/quotations/'+props.row.quotation_number")
				i.material-icons remove_red_eye
			button(class="table-btn btn btn-light" title="Delete" @click="deleteQuotation(props.row.id)"  v-if="access('quotation','delete')")
				i.material-icons delete
	alert(:title="title" :id="toEdit" :cancel="cancel" :ok="ok")
		p(slot="desc") Are you sure you want to delete this?
</template>
<script>
import m_common from "Mixins/common";
import alert from "Components/common/Alert.vue";

export default {
    components: {
        alert
    },
    props: ["customerId", "customerDetails"],
    mixins: [m_common],
    data() {
        var _this = this;
        return {
            title: "Quotation",
            toEdit: false,
            datatable: {
                columns: ["quotation_number", "user_name", "total_items", "user_total_price", "status", "handled_by", "requested_on", "actions"],
                options: {
                    initFilters: {
                        user_id: _this.customerId ? _this.customerId : ""
                    },
                    filterable: ["quotation_number", "user_name", "user_total_price", "user_id", "status"],
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
                        status: []
                    },
                    responseAdapter(resp) {
                        if (_this.datatable.options.listColumns.status.length == 0) {
                            for (var key in resp.data.quotation_status) {
                                _this.datatable.options.listColumns.status.push({
                                    id: key,
                                    text: resp.data.quotation_status[key]
                                });
                            }
                        }
                        return {
                            data: resp.data.quotation,
                            count: resp.data.count
                        };
                    },
                    headings: {
                        quotation_number: "Quotation Number",
                        user_name: "Customer",
                        user_total_price: "Price",
                        status_value: "Status"
                    }
                }
            }
        };
    },
    created() {},
    mounted() {},
    methods: {
        deleteQuotation(id) {
            this.toEdit = id;
            this.$root.$emit("bv::show::modal", "alertModal");
        },
        cancel() {
            this.$root.$emit("bv::hide::modal", "alertModal");
        },
        ok(id) {
            var _this = this;
            this.apiPost({
                url: `${this.apiUrl()}/quotations/${id}`,
                method: 'delete',
                formId: "quotationTable",
                msg: {
                    success: 'Quotation Deleted Successfully'
                },
                success(data) {
                    _this.$refs.quotationTable.refresh();
                }
            });
        },
    }
};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";
.quote-table {
    font-size: 14px;
    .badge {
        color: #fff;
    }
}
</style>
