<template lang="pug">
.page__card
	v-server-table(
		:url="`${apiUrl()}/shipment`"
		:columns="datatable.columns"
		:options="datatable.options"
		id="shipmentTable"
		ref="shipmentTable"
		class="shipment-table")

		h5(slot="status" slot-scope="props")
			span.badge(:style="{backgroundColor:statusColor.shipment[props.row.status]}") {{props.row.status_value}}

		span(slot="received_on" slot-scope="props") {{formatDate(props.row.received_on)}}

		router-link(:to="'/app/customer/'+props.row.user_id" slot="user_name" slot-scope="props") {{props.row.user_name}}

		router-link(:to="'/app/shipments/'+props.row.id" slot="name" slot-scope="props") {{props.row.name}}

		span(slot="size" slot-scope="props") {{props.row.dimension_width}}x{{props.row.dimension_height}}x{{props.row.dimension_length}}

		p.description(slot="parcel_desc" slot-scope="props") {{ props.row.parcel_display_desc }}
			button.button.btn.btn-primary.btn-sm(
				v-if="props.row.parcel_desc && props.row.parcel_display_desc ==  props.row.parcel_sub_desc && props.row.parcel_desc.length > 100"
				@click="toggleDescription(props.row,'show')") View More
			button.button.btn.btn-primary.btn-sm(
				v-if="props.row.parcel_desc && props.row.parcel_display_desc == props.row.parcel_desc && props.row.parcel_desc.length > 100"
				@click="toggleDescription(props.row,'hide')") View Less

		.table-actions(slot="actions" slot-scope="props")

			button(class="table-btn btn btn-light" title="View Progress" @click="view(props.row.id)")
				i.material-icons timeline

			router-link(class="table-btn btn btn-light" title="Edit" :to="'/app/shipments/'+props.row.id")
				i.material-icons edit
			button(class="table-btn btn btn-light" title="Delete" @click="deleteShipment(props.row.id)" v-if="access('shipment','delete')")
				i.material-icons delete
			//- button(class="table-btn btn btn-success" title="Mark As Payment Received" v-if="props.row.is_payment_done")
			//-     i.material-icons monetization_on
			//-
			//- button(class="table-btn btn btn-info" title="Mark As Shipped" v-if="props.row.is_ready_for_shipment")
			//-     i.material-icons local_shipping
			//-
			//- button(class="table-btn btn btn-success" title="Mark As Delivered" v-if="props.row.is_delivered")
			//-     i.material-icons check
	alert(:title="title" :id="toView" :cancel="cancel" :ok="ok")
		p(slot="desc") Are you sure you want to delete this?
	c_view(:toView="toView" :table="$refs.shipmentTable")
</template>
<script>
import c_view from "./View.vue";
import m_common from "Mixins/common";
import alert from "Components/common/Alert.vue";

export default {
    components: {
        c_view,
        alert
    },
    props: ["customerId", "customerDetails"],
    mixins: [m_common],
    data() {
        var _this = this;
        return {
            title: "Shipment",
            toView: false,
            datatable: {
                columns: ["parcel_number", "name", "status", "user_name", "parcel_desc", "size", "parcel_weight", "postal_company", "received_on", "actions"],
                options: {
                    filterable: ["name", "status", "parcel_number", "parcel_desc", "postal_company", "user_id"],
                    initFilters: {
                        user_id: _this.customerId ? _this.customerId : ""
                    },
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
                            for (var key in resp.data.shipment_status) {
                                _this.datatable.options.listColumns.status.push({
                                    id: key,
                                    text: resp.data.shipment_status[key]
                                });
                            }
                        }
                        return {
                            data: resp.data.shipments,
                            count: resp.data.count
                        };
                    },
                    headings: {
                        size: "Size(WxHxL)",
                        postal_company: "Postal Company",
                        parcel_weight: "Weight",
                        parcel_number: "Parcel Number",
                        parcel_desc: "Desc",
                        received_on: "Received On",
                        user_name: "Customer"
                    }
                }
            }
        };
    },
    created() {},
    mounted() {},
    methods: {
        view(id) {
            this.$root.$emit("bv::show::modal", "viewShipmentModal");
            var _this = this;
            _this.apiGet({
                url: `${_this.apiUrl()}/shipping-progress/${id}`,
                success(data) {
                    _this.toView = data;
                }
            });
        },
				toggleDescription(parcel,method) {
					//console.log(parcel);
					if(method == "show"){
						parcel.parcel_display_desc = parcel.parcel_desc;
					}else{
						parcel.parcel_display_desc = parcel.parcel_sub_desc;
					}
				},
        deleteShipment(id) {
            this.toView = id;
            this.$root.$emit("bv::show::modal", "alertModal");
        },
        cancel() {
            this.$root.$emit("bv::hide::modal", "alertModal");
        },
        ok(id) {
            var _this = this;
            this.apiPost({
                url: `${this.apiUrl()}/shipment/${id}`,
                method: 'delete',
                formId: "shipmentTable",
                msg: {
                    success: 'Shipment Deleted Successfully'
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
@import "~Styles/vue-imports.scss";
.shipment__container {
    padding: 20px;
}
.shipment-table {
    font-size: 14px;
    .badge {
        color: #fff;
    }
}
</style>
