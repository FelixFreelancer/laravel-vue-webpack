<template lang="pug">
section
	.shipments-wrap
		h3.dashboard__content__title Ready for Shipping
		b-alert(:show="loaded && shipping.length == 0" variant="warning") You have no items that are ready for shipping at the moment
		.shipments-box.shipping--box(v-for="item in shipping")
			.shipments__header
				.shipments-info.shipping--first
					ul.ul-reset.shipments__info
						li
							label.shipments__title {{item.name}}
							p.shipments__text {{item.parcel_desc}}
							//a(href="#nogo") View more...
						li
							label: b Dimensions:
							span.normal Length: {{item.dimension_length}} cm | width: {{item.dimension_width}} cm | Height : {{item.dimension_height}} cm
						li
							label: b Weight:
							span.normal {{item.parcel_weight}} kg
				.shipments-info.shipping--middle
					ul.ul-reset.shipments__info
						li
							label Ship with
							img(:src="item.shipping_out_logo"  width="150")
							span.normal {{item.shipping_out_company}}
						li
							.labelspan
								label Shipping cost:
								span {{item.total}}
							a(href="javascript:;" class="button shipments__status paid") Paid
				.shipments-info.shipping--last
					ul.ul-reset.shipments__info
						li
							label Status:
							a(href="javascript:;" class="button shipments__status") {{item.status_text}}
						//- li
						//- 	label Deliver Date
						//- 	span February 20, 2018
						//- li
						//- 	label Tracking Number
						//- 	span.normal PLF23847238
	pagination(v-if="shipping && shipping.length > 0" :nextData="getShipping" :pagination="pagination" :lastPage="lastPage" :page="page")
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
			shipping: [],
			loaded: false,
			page: 1,
            lastPage: 0,
            pagination: {},
		};
	},
	created() {},
	mounted() {
		this.getShipping(this.page);
	},
	methods: {
		getShipping(page) {
			var _this = this;
			_this.apiGet({
				url: `${_this.apiUrl()}/ready-for-shipping?page=${page}`,
				success(data) {
					_this.shipping = data.shipment;
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
