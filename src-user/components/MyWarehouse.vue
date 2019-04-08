<template lang="pug">
section
	h3.dashboard__content__title My Warehouse
	b-alert(:show="loaded && shipments.length == 0" variant="warning") You have no parcels in your warehouse at the moment, start shopping to fill up this space with goodies!
	.shipments-box.shipping--box.warehouse-box(v-for="(shipment,index) in shipments")
		.shipments__header
			.shipments-info.shipping--first
				ul.ul-reset.shipments__info
					li
						label.shipments__title {{shipment.name}}
						p.shipments__text {{shipment.parcel_desc}}
						//a(href="#nogo") View more...
					li
						label: b Dimensions:
						span.normal Length: {{shipment.dimension_length}} cm | width: {{shipment.dimension_width}} cm | Height : {{shipment.dimension_height}} cm
					li
						label: b Weight:
						span.normal {{shipment.parcel_weight}} kg

			.shipments-info.shipping--middle
				ul.ul-reset.shipments__info
					li
						label Receipts
						a(:href="shipment.invoice" class="pdf-button" target="_blank")
							img(src="~Images/pdf-icon.jpg")
							span invoice.pdf
			.shipments-info.shipping--last
				ul.ul-reset.shipments__info
					li
						label Ship with
						img(:src="shipment.shipping_out_logo"  width="150")
						span.normal {{shipment.shipping_out_company}}
					li
						label Shipping cost:
						span £{{shipment.total}} (not paid yet)
					li
						a(:href="siteUrl()+'/shipments/'+shipment.id+'/payment'" class="button button--accent") Pay shipping cost
		//ITEMS DESKTOP
		.parcel__table.res-table.parcel-desktop
			table
				tr
					th Items Name
					th(style="width:40px") Qty
					th(style="width:60px") Amount
					th(style="width:200px") Description
					th Tracking Number
					th Dimensions
				tr(v-for="item in shipment.items")
					td
						.item-name
							span {{item.name}}
					td {{item.qty}}
					td {{item.amount}}
					td
						.pt-desc-edit
							p {{item.desc}}
					td {{item.tracking_number}}
					td
						.labelspan
							label Length:
							span {{item.dimension_length}} cm
						.labelspan
							label Width:
							span {{item.dimension_width}} cm
						.labelspan
							label Height:
							span {{item.dimension_height}} cm
						.labelspan
							label Weight:
							span {{item.weight}} kg
		//ITEMS MOBILE
		.parcel__table.res-table.parcel-mobile(v-for="item in shipment.items")
			.pm
				h3.pm__title Your Items
				.pm__item
					.pm__head
						.pm__name
							span {{item.name}}
							.pm__amt
								.labelspan
									label Qty:
									span {{item.qty}}
								.labelspan
									label Amount:
									span {{item.amount}}
					.pt-desc-edit.pm__desc
						p {{item.desc}}
					.pm__details
						.labelspan
							label Tracking Number:
							span {{item.tracking_number}}
						.labelspan
							label Dimensions:
							span L:{{item.dimension_length}} cm W:{{item.dimension_width}} cm H:{{item.dimension_height}} cm
						.labelspan
							label Weight:
							span {{item.weight}} kg
		//- .dashboard__footer.all-center
		//- 	.checkbox.ship__text
		//- 		input(type="checkbox" id="ship-check")
		//- 		label(for="ship-check") All informations above are correct and packages ready to ship
		//- 	button.button--accent.button--ship(type="button" @click="shipNow" id="shipNowButton") Ship Now
	pagination(v-if="shipments && shipments.length > 0" :nextData="getWarehouse" :pagination="pagination" :lastPage="lastPage" :page="page")
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
			pagination:{},
		};
	},
	created() {},
	mounted() {
		this.getWarehouse(this.page);
	},
	methods: {
		shipNow() {},
		getWarehouse(page) {
			var _this = this;
			_this.apiGet({
				url: `${_this.apiUrl()}/warehouse?page=${page}`,
				success(data) {
						data.shipment.forEach((item, index) => {
							item.total=Number.parseFloat(item.total).toFixed(2);
						});
					_this.shipments = data.shipment;
					_this.loaded = true;
					_this.lastPage = page-1;
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
