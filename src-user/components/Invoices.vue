<template lang="pug">
section.invoice-wrap
	h3.dashboard__content__title Invoices
	b-alert(:show="loaded && invoices.length == 0" variant="warning") You currently have no invoices.
	ul.ul-reset.invoice__list
		li(v-for="item in invoices")
			.invoice__box
				.invoice__header
					.invoice__info
						ul.ul-reset.invoice__maininfo
							li
								label Date:
								span {{item.created_at}}
							li
								label Invoice:
								span: a(:href="item.invoice" target="_blank") {{'#'+item.invoice_number}}
						.invoice__title
							label {{item.entity_type_text}}
					.invoice__status
						a(href="#nogo" class="button paid") {{item.is_paid?'PAID':'UNPAID'}}
				.invoice__table__wrap
					table.invoice__table(v-if="item.entity_type == 3")
						thead
							tr
								th Description
								th Total
						tbody
							tr
								td {{item.desc}}
								td {{item.total}}
						tfoot
							tr
								td
								td(class="text-right")
									label Total:
									span {{item.total}}
					table.invoice__table(v-if="item.entity_type == 2")
						thead
							tr
								th Quotation Number
								th Name
								th Qty
								th Price
								th Subtotal
						tbody
							tr(v-for="invoice_item in item.items")
								td {{'#'+invoice_item.quotation_number}}
								td {{invoice_item.item_name}}
								td {{invoice_item.quantity}}
								td {{invoice_item.rate}}
								td {{invoice_item.subtotal}}
						tfoot
							tr
								td
								td
								td
								td
								td(class="text-right")
									label Total:
									span {{item.items[0].total}}
					table.invoice__table(v-if="item.entity_type == 1")
						thead
							tr
								th Shipment Number
								th Shipped By
								th Rate
								th Subtotal
						tbody
							tr
								td {{item.shipment.parcel_number}}
								td {{item.shipment.shipped_by}}
								td {{item.shipment.total}}
								td {{item.shipment.total}}
						tfoot
							tr
								td
								td
								td
								td(class="text-right")
									label Total:
									span {{item.shipment.total}}
	pagination(v-if="invoices && invoices.length > 0" :nextData="getInvoice" :pagination="pagination" :lastPage="lastPage" :page="page")
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
            invoices: [],
            loaded: false,
            page: 1,
            lastPage: 0,
			pagination:{},
        };
    },
    created() {},
    mounted() {
        this.getInvoice(this.page);
    },
    methods: {
        getInvoice(page) {
            var _this = this;
            _this.apiGet({
                url: `${_this.apiUrl()}/invoice?page=${page}`,
                success(data) {
                    _this.invoices = data.invoice;
					_this.lastPage = page-1;
                    _this.page = data.page;
                    _this.pagination = data.pagination;
                    _this.loaded = true;
                }
            });
        }
    }
};
</script>

<style lang="css">
</style>
