<template lang="pug">
section
	h3.dashboard__content__title Action Box
	b-alert(:show="loaded && shipment.length == 0" variant="warning") You're all good here. No tasks on your to do list!
	.parcel(v-for="(parcel,index) in shipment" v-if="shipmentIds.indexOf(parcel.id) > -1")
		//PARCEL DETAILS
		.parcel_detail

			.parcel__slider
				//Multiple Photos
				b-carousel(
					v-if="parcel.image && parcel.image.length > 1"
					indicators
					v-model="slide[index]"
					:interval="20000")
					b-carousel-slide(v-for="(image,index) in parcel.image" :key="index")
						.parcel__image(slot="img" :style="`background-image:url(${image})`")
				//Only 1 Photo Available
				.parcel__image(v-if="parcel.image.length == 1" :style="`background-image:url(${parcel.image})`")
				//No Photo Available
				.parcel__slider-no-image(v-if="parcel.image.length == 0")
					span Image Not Available

			.parcel__received
				.parcel__column
					.parcel__attr
						b: span Received:
						span {{parcel.received_on}}
					.parcel__attr
						b: span Parcel Number:
						span {{parcel.parcel_number}}
					.parcel__from
						b: label {{parcel.name}}
						p.description {{ parcel.parcel_display_desc }}
						div(v-if="parcel.parcel_desc && parcel.parcel_desc.length > 100")
							button.button.button--accent.button--table(
								v-if="parcel.parcel_display_desc == parcel.parcel_sub_desc"
								@click="toggleDescription(parcel,'show')") View More
							button.button.button--accent.button--table(
								v-if="parcel.parcel_display_desc == parcel.parcel_desc"
								@click="toggleDescription(parcel,'hide')") View Less
				.parcel__column
					.dimensions
						h3.dimensions__title Dimensions
						table.parcel__attr-table
							tr
								td Length
								td {{parcel.dimension_length}} cm
							tr
								td Width
								td {{parcel.dimension_width}} cm
							tr
								td Height
								td {{parcel.dimension_height}} cm
					.weight
						.parcel__attr
							b: span Weight:
							span {{parcel.parcel_weight}} kg
					.postal-company
						label Postal Company
						span {{parcel.postal_company}}

		b-alert(show variant="warning" v-if="parcel.items && parcel.items.length == 0") No Items Available In This Package!
		section(v-if="parcel.items && parcel.items.length > 0")
			//ITEM LIST : DESKTOP
			.parcel__table.res-table.parcel-desktop
				table
					tr
						th(style="width:130px") Items Name
						th(style="width:40px") Qty
						th(style="width:60px") Amount
						th(style="width:200px") Description
						th(style="width:110px") Tracking Number
						th(style="width:120px") Dimensions
					tr(v-for="item in parcel.items")
						td
							.item-name
								span {{item.name}}
								button.button.button--accent.button--table(
									:id="`rp-${parcel.id}-${item.id}`"
									v-if="!item.image || item.image.length <= 0"
									@click="requestPhoto($event,parcel.id,item.id)") Request Photo
								button.button.button--accent.button--table(
									v-if="item.image && item.image.length > 0"
									@click="viewPhotos(item.image)") View Photos

						td {{item.qty}}
						td {{item.amount}}
						td
							.pt-desc-edit
								p {{ item.desc }}
								//- button.button.button--edit
								//- 	i.material-icons edit
						td {{item.tracking_number}}
						td
							table.parcel__attr-table
								tr
									td Length
									td {{item.dimension_length}} cm
								tr
									td Width
									td {{item.dimension_width}} cm
								tr
									td Height
									td {{item.dimension_height}} cm
								tr
									td Weight
									td {{item.weight}} kg
			//ITEM LIST:MOBILE
			.parcel__table.res-table.parcel-mobile
				.pm
					h3.pm__title Your Items
					.pm__item(v-for="item in parcel.items")
						.pm__head
							.pm__name
								span {{item.name}}
								.pm__amt
									.parcel__attr
										span Qty:
										span {{item.qty}}
									.parcel__attr
										span Amount:
										span {{item.amount}}
								button.button.button--accent.button--table(
									:id="`rp-${parcel.id}-${item.id}`"
									v-if="!item.image || item.image.length <= 0"
									@click="requestPhoto($event,parcel.id,item.id)") Request Photo
								button.button.button--accent.button--table(
									v-if="item.image && item.image.length > 0"
									@click="viewPhotos(item.image)") View Photos
						.pt-desc-edit.pm__desc
							p {{item.desc}}
							button.button.button--edit
								i.fas.fa-pencil-alt
						.pm__details
							table.parcel__attr-table
								tr
									td Tracking Number
									td {{item.tracking_number}}
								tr
									td Dimensions
									td L:{{item.dimension_length}} W:{{item.dimension_width}} H:{{item.dimension_height}}
								tr
									td Weight
									td {{item.weight}}
			//SHIPPING OPTIONS

			.shipping-options.res-table
				h3.shipping-options__title Shipping Options
				.shipping-options__loader(v-if="!shippingOptions[parcel.id]")
					img(src="~Images/loader.svg" width="30px")
					span Loading available shipping options. Please Wait...
				b-alert(show
					variant="warning"
					v-if="shippingOptions[parcel.id] && shippingOptions[parcel.id].length == 0") No shipping options available right now. Please contact support.

				//SHIPPING OPTIONS:DESKTOP
				table.shipping-options-desktop(v-if="shippingOptions[parcel.id]")
					tr(
						v-for="(item,shipIndex) in shippingOptions[parcel.id]"
						:class="shipmentToMove.shipmentId == parcel.id && shipmentToMove.shippingIndex == shipIndex?'active':''")
						td
							img(:src='item.logo' width="150")
							.namerate
								span {{item.name}}
								//+rating(5)
						td
							.so-price
								p £{{item.total_price}}
								span {{item.estimated_delivery_date}}
						td
							ul.shipping-options__features(v-html="item.features")
						td
							button.button.button--accent.button--table(
								type="button"
								@click="selectShipping(parcel.id,shipIndex)") Choose

				//SHIPPING OPTIONS : MOBILE
				.shipping-options-mobile(
					v-for="(item,shipIndex) in shippingOptions[parcel.id]"
					:class="shipmentToMove.shipmentId == parcel.id && shipmentToMove.shippingIndex == shipIndex?'active':''")
					.so
						.so__header
							.so__name
								img(:src='item.logo')
								.namerate
									span {{item.name}}
									//+rating(5)
							.so__details
								ul.shipping-options__features(v-html="item.features")
						.so-price.so__price
							p £{{item.total_price}}
							span {{item.estimated_delivery_date}}
						button.button.button--accent.button--table(type="button" @click="selectShipping(parcel.id,shipIndex)") Choose
			.send-to-w(v-if="shippingOptions[parcel.id] && shippingOptions[parcel.id].length > 0")
				.send-input
					div.insurance__checkbox(v-if="show")
						input(type="checkbox" :id="'insuranceOption'+index" v-model="insuranceOption[index]")
						label(:for="'insuranceOption'+index")  £{{parcel.insurance_charges}} -  Want To Add Insurance?
					div
						input(type="checkbox" :id="'acceptAction'+index" v-model="acceptAction[index]")
						label(:for="'acceptAction'+index") I accept all information about the package, price, dimension, etc are correct and acceptable
				button.button.button--accent(type="button" id="sendToWarehouseButton" @click="moveToWarehouse(parcel.id,index)") Send to warehouse

	//ITEM PHOTOS MODAL
	b-modal(id="modal1" title="Item Photos" hide-footer)
		.parcel__slider.parcel__slider--item
			//Multiple Photos
			b-carousel(
				v-if="photoGallery.length > 1"
				indicators
				v-model="itemSlide"
				:interval="20000")
				b-carousel-slide(v-for="(image,index) in photoGallery" :key="index")
					.parcel__image(slot="img" :style="`background-image:url(${image})`")
			//Only 1 Photo Available
			.parcel__image(v-if="photoGallery.length == 1" :style="`background-image:url(${photoGallery[0]})`")
	pagination(v-if="shipment && shipment.length > 0" :nextData="getActions" :pagination="pagination" :lastPage="lastPage" :page="page")
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
			shipment: [],
			slide: [],
			itemSlide: "",
			shippingOptions: {},
			shipmentIds: [],
			acceptAction: [],
		  insuranceOption: [],
			show:false,
			shipmentToMove: {
				shipmentId: "",
				shipmentProvider: "",
				shippingIndex: ""
			},
			photoGallery: [],
			loaded: false,
			page: 1,
			lastPage: 0,
			pagination:{},
		};
	},
	created() {
		this.getActions(this.page);
	},
	mounted() {},
	methods: {
		requestPhoto($event, parcelId, itemId) {
			var _this = this;
			_this.apiPost({
				url: `${_this.apiUrl()}/user/shipment/${parcelId}/shipment-item/${itemId}/request-photo`,
				buttonId: $event.target.id,
				success(data) {
					console.log(data);
				}
			});
		},
		viewPhotos(images) {
			var _this = this;
			_this.photoGallery = images;
			this.$root.$emit("bv::show::modal", "modal1");
		},
		toggleDescription(parcel,method) {
			if(method == "show"){
				parcel.parcel_display_desc = parcel.parcel_desc;
			}else{
				parcel.parcel_display_desc = parcel.parcel_sub_desc;
			}
		},
		selectShipping(parcelId, shippingIndex) {
			var _this = this;
			_this.show=true;
			_this.shipmentToMove.shipmentId = parcelId;
			_this.shipmentToMove.shipmentProvider = _this.shippingOptions[parcelId][shippingIndex];
			_this.shipmentToMove.shippingIndex = shippingIndex;
		},
		moveToWarehouse(parcelId, index) {
			var _this = this;
			_this.shipmentToMove.shipmentProvider.insuranceOption = _this.insuranceOption[index];
			if (_this.shipmentToMove.shipmentId && _this.shipmentToMove.shipmentId == parcelId) {
				if (_this.acceptAction[index]) {
					_this.apiPost({
						url: `${_this.apiUrl()}/shipment/${parcelId}/shipping-option`,
						data: _this.shipmentToMove.shipmentProvider,
						buttonId: "sendToWarehouseButton",
						success(data) {
							var index = _this.shipmentIds.indexOf(parcelId);
							if (index > -1) {
								_this.shipmentIds.splice(index, 1);
							}
							_this.$router.push('/my-warehouse');
						}
					});
				} else {
					alert("Please read & accept terms & conditions.");
				}
			} else {
				alert("Please select shipment provider.");
			}
		},
		getActions(page) {
			var _this = this;
			_this.apiGet({
				url: `${_this.apiUrl()}/action-box?page=${page}`,
				success(data) {
					_this.shipment = data.shipment;
					_this.shipmentIds = [];
					data.shipment.forEach((item, index) => {
						_this.shipmentIds.push(item.id);
					});
					if(_this.shipmentIds.length > 0){
						_this.getShipments(_this.shipmentIds);
					}
					_this.loaded = true;
					_this.lastPage = page-1;
					_this.page = data.page;
					_this.pagination = data.pagination;
				}
			});
		},
		getShipments(shipmentIds) {
			var _this = this;
			_this.apiPost({
				url: `${_this.apiUrl()}/shipment/get-shipping-option`,
				data: {
					id: shipmentIds
				},
				success(data) {
					_this.shippingOptions = data;
				}
			});
		}
	}
};
</script>

<style lang="scss">

</style>
