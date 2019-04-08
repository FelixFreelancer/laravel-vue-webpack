<template lang="pug">
.shipments-wrap
	h3.dashboard__content__title Shipments
	b-alert(:show="loaded && shipments.length == 0" variant="warning") You have no items that are ready for shipping at the moment
	.shipments-box(v-for="item in shipments")
		.shipments__header
			.shipments-detail
				ul.ul-reset.shipments__info
					li
						label: b Parcel Sent:
						Span {{item.shipping_out_at}}
					li
						label.shipments__title: b {{item.name}}
						p.shipments__text {{item.parcel_desc}}
						//a(href="#nogo") View more...
					li
						label: b Dimensions:
						span.normal Length: {{item.dimension_length}} cm | width: {{item.dimension_width}} cm | Height : {{item.dimension_height}} cm
					li
						label: b Weight:
						span.normal {{item.parcel_weight}} kg
			.shipments-info
				ul.ul-reset.shipments__info
					li
						label: b Ship with
						img(:src="item.shipping_out_logo")
						span.normal.mt-2 {{item.shipping_out_company}}
					li
						label: b Tracking Number
						span.normal
							a(:href="item.shipping_tracking_link" target="_blank") {{item.shipping_out_tracking}}
		.shipments__content
			h3.dashboard__content__title Invoice Details
				.invoice__table__wrap
					table.invoice__table
						thead
							tr
								th Item Name
								th Qty
								th Price
								th Total Amount
						tbody
							tr(v-for="i in item.items")
								td {{i.name}}
								td {{i.qty}}
								td {{i.amount}}
								td {{i.total}}
						tfoot
							tr
								td(colspan="3")
								td
									label Total:
									span {{item.total}}
	pagination(v-if="shipments && shipments.length > 0" :nextData="getShipments" :pagination="pagination" :lastPage="lastPage" :page="page")
</template>

<script>
import m_common from "Mixins/common";
import pagination from "./Pagination.vue";

export default {
    components: {
        pagination
    },
    props: [],
    mixins: [m_common],
    data() {
        return {
            shipments: [],
            loaded: false,
            page: 1,
            lastPage: 0,
            pagination: {},
        };
    },
    created() {},
    mounted() {
        this.getShipments(this.page);
    },
    methods: {
        getShipments(page) {
            var _this = this;
            _this.apiGet({
                url: `${_this.apiUrl()}/shipments?page=${page}`,
                success(data) {
                    _this.shipments = data.shipment;
                    _this.loaded = true;
                    _this.lastPage = page - 1;
                    _this.page = data.page;
                    _this.pagination = data.pagination;
                }
            });
        }
    }
};
</script>

<style lang="css">
</style>
