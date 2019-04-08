<template lang="pug">
c_page.search-results.page--grey
	h3(slot="title") Search Results For&nbsp;
		b {{searchQuery}}

	.search-data(slot='content')
		.search-data__category
			.list-group(v-if="Object.keys(searchResult).length > 0")
				button.list-group-item.list-group-item-action(
					@click="searchActive='all'"
					:class="searchActive=='all'?'active':''") All
				button.list-group-item.list-group-item-action(
					v-for="(item,key) in searchResult"
					@click="searchActive=key"
					:class="searchActive==key?'active':''"
				) {{searchTitles[key].title}} ({{item.length}})

		.search-data__results
			.card(
				v-for="(item,key) in searchResult"
				v-if="item.length > 0"
				v-show="searchActive=='all' || searchActive==key")
				.card-header
					i.material-icons {{searchTitles[key].icon}}
					h5.m-0 {{searchTitles[key].title}}
				.card-body
					table.table.table-sm
						tr
							th(v-for="(subitem,subkey) in item[0]" v-if="avoidTh.indexOf(subkey)<0") {{$t(`db.${subkey}`)}}

						tr(v-for="subitem in item")
							td(v-for="(value,subkey) in subitem" v-if="avoidTh.indexOf(subkey)<0")

								//User Results
								div(v-if="key=='user'")
									span(v-if="subkey=='customer_code'"): router-link(:to="'/app/customer/'+subitem.id") {{value}}
									span(v-if="subkey=='name'"): router-link(:to="'/app/customer/'+subitem.id") {{value}}
									span(v-if="subkey=='email'"): a(:href="'mailto:'+value" target="_blank") {{value}}
									span(v-if="subkey=='contact_number'"): a(:href="'tel:'+value" target="_blank") {{value}}
									span(v-if="subkey=='country'") {{value}}
									span(v-if="subkey=='customer_status'") {{value}}

								//Shipment Items
								div(v-if="key=='shipment_item'")
									span(v-if="subkey=='item_name'"): router-link(:to="'/app/shipments/'+subitem.shipment_id+'/items'") {{value}} 
									span(v-if="subkey=='shipment_name'"): router-link(:to="'/app/shipments/'+subitem.shipment_id") {{value}} 
									span(v-if="subkey=='user_name'"): router-link(:to="'/app/customer/'+subitem.customer_id") {{value}}
									span(v-if="subkey=='customer_code'"): router-link(:to="'/app/customer/'+subitem.customer_id") {{value}}
									span(v-if="subkey=='tracking_number'") {{value}}
									span(v-if="subkey=='amount'") {{value}}

								//Shipments
								div(v-if="key=='shipment'")
									span(v-if="subkey=='parcel_number'") {{value}} 
									span(v-if="subkey=='name'"): router-link(:to="'/app/shipments/'+subitem.id") {{value}} 
									span(v-if="subkey=='customer_name'"): router-link(:to="'/app/customer/'+subitem.customer_id") {{value}}
									span(v-if="subkey=='received_on'") {{value}}
									span(v-if="subkey=='status'") {{value}}

								//Quotation
								div(v-if="key=='quotation'")
									span(v-if="subkey=='quotation_number'"): router-link(:to="'/app/quotations/'+subitem.id") {{value}} 
									span(v-if="subkey=='customer_name'"): router-link(:to="'/app/customer/'+subitem.customer_id") {{value}}
									span(v-if="subkey=='customer_code'"): router-link(:to="'/app/customer/'+subitem.customer_id") {{value}}
									span(v-if="subkey=='requested_on'") {{value}}
									span(v-if="subkey=='status'") {{value}}
									span(v-if="subkey=='total'") {{value}}

					router-link.btn.btn-primary.btn-sm(:to="searchTitles[key].url") View More Results
</template>
<script>
import c_page from "Components/Page.vue";
import m_common from "Mixins/common";
import { switchCase } from "babel-types";

export default {
	components: {
		c_page
	},
	props: ["searchQuery"],
	mixins: [m_common],
	data() {
		return {
			searchActive: "all",
			avoidTh: ["id", "shipment_id", "customer_id"],
			searchTitles: {
				user: {
					title: "Customers",
					icon: "people",
					url: "/app/customers"
				},
				shipment: {
					title: "Shipments",
					icon: "widgets",
					url: "/app/shipments"
				},
				quotation: {
					title: "Quotations",
					icon: "monetization_on",
					url: "/app/quotations"
				},
				shipment_item: {
					title: "Items",
					icon: "redeem",
					url: "/app/shipments"
				}
			},
			searchResult: []
		};
	},
	created() {
		this.loadResults();
	},
	mounted() {},
	watch: {
		searchQuery: function() {
			this.loadResults();
		}
	},
	methods: {
		loadResults() {
			var _this = this;
			var query = _this.searchQuery ? encodeURIComponent(_this.searchQuery) : "";
			_this.apiGet({
				url: `${_this.apiUrl()}/search?search=${query}`,
				success(data) {
					_this.searchResult = data;
				}
			});
		},
		tdData(key, row) {
			switch (key) {
				case "customer_code":
					return `<a href="#/app/customer/${row.id}">${row[key]}</a>`;
				case "name":
					return `<a href="#/app/customer/${row.id}">${row[key]}</a>`;
				case "name":
					return `<a href="#/app/customer/${row.id}">${row[key]}</a>`;
				default:
					return `${key}:${row[key]}`;
			}
		}
	}
};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";
.search-data {
	padding: 20px;
	display: flex;
}
.search-data__category {
	flex: 0 0 250px;
	button {
		outline: 0;
		cursor: pointer;
	}
}
.search-data__results {
	flex: 1 1 auto;
	margin-left: 20px;
	.card {
		margin-bottom: 30px;
	}
	.card-header {
		display: flex;
		align-items: center;
		i {
			margin-right: 10px;
			font-size: 28px;
			opacity: 0.5;
		}
	}
	.table {
		font-size: 14px;
		th {
			border-top: 0;
		}
		td {
		}
	}
}
</style>
