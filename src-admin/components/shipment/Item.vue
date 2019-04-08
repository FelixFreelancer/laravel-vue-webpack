<template lang="pug">
.page__card
	router-link.btn.btn-primary.add-item-button(:to="'/app/shipments/'+shipmentId+'/items/add'") Add An Item
	v-server-table(
		:url="`${apiUrl()}/shipment/${shipmentId}/shipment-items`"
		:columns="datatable.columns"
		:options="datatable.options"
		ref="shipmentTable"
		id="shipmentTable"
		class="shipment-table")

		span(slot="status" slot-scope="props") {{props.row.status_value}}
		span(slot="received_on" slot-scope="props") {{formatDate(props.row.received_on)}}
		span(slot="size" slot-scope="props") {{props.row.dimension_width}}x{{props.row.dimension_height}}x{{props.row.dimension_length}}

		.table-actions(slot="actions" slot-scope="props")
			router-link(class="table-btn btn" title="Edit" :to="'/app/shipments/'+shipmentId+'/items/'+props.row.id")
				i.material-icons remove_red_eye
			button(class="table-btn btn btn-light" title="Delete" @click="deleteItem(props.row.id)" v-if="access('shipment_item','delete')")
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
    props: ["shipmentId"],
    mixins: [m_common],
    data() {
        var _this = this;
        return {
            title: 'Shipment Item',
            toEdit: false,
            datatable: {
                columns: ["item_name", "qty", "amount", "desc", "size", "weight", "tracking_number", "actions"],
                options: {
                    filterable: ["item_name", "desc", "tracking_number"],
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
                        status: []
                    },
                    responseAdapter(resp) {
                        return {
                            data: resp.data.items,
                            count: resp.data.count
                        };
                    },
                    headings: {}
                }
            }
        };
    },
    created() {},
    mounted() {},
    methods: {
        deleteItem(id) {
            this.toEdit = id;
            this.$root.$emit("bv::show::modal", "alertModal");
        },
        cancel() {
            this.$root.$emit("bv::hide::modal", "alertModal");
        },
        ok(id) {
            var _this = this;
            this.apiPost({
                url: `${this.apiUrl()}/shipment-items/${id}`,
                method: 'delete',
                formId: "shipmentTable",
                msg: {
                    success: 'Item Deleted From Shipment Successfully'
                },
                success(data) {
                    _this.$refs.shipmentTable.refresh();
                }
            });
        },
    }
};
</script>

<style lang="scss">
.add-item-button {
    margin-bottom: 30px;
}
</style>
