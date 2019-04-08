<template lang="pug">
.shopper-wrap
	h3.dashboard__content__title Personal Shopper
	p.shopper__text Avoid the hassles of U.K. retailer checkout - let us do the shopping for you! Just provide us with some information below and leave the rest to us.
	.shopper__box
		h4.shopper__title Request Shopping
			form.shopper-form(id="quoteForm" @submit.prevent="getQuoteSubmit")
				section.shopper-form__wrap(v-for="n in requestForms" v-if="formsArray.indexOf(n) > -1")
					button.btn.btn-danger.removeItem(
						type="button"
						v-if="formsArray.length > 1"
						@click="removeRequest(n)") x
					ul.ul-reset
						li.gpf-input.one-half-wrap
							.one-half-input
								label Store Name
								input(type="text" :name="`store_name[${n}]`")
							.one-half-input
								label Item Direct Link
								input(type="text" :name="`direct_link[${n}]`")
						li.gpf-input.one-half-wrap
							.one-half-input
								label Item Name
								input(type="text" :name="`item_name[${n}]`")
							.one-half-input
								ul.ul-reset.sub-field
									li
										label Price
										.sub__box
											.sub__field
												select(:name="`user_price_currency[${n}]`")
													option(value="£") £
											.sub__field
												input(type="text" :name="`user_price[${n}]`")
									li
										label Color
										input(type="text" :name="`color[${n}]`")
									li
										label Qty
										input(type="text" :name="`quantity[${n}]`")
				.shopper-action
					button.button--primary(type="button" @click="addRequest") Add More
					button.button--accent(type="submit") Get A Quote
	.shopper__box
		h4.dashboard__content__title Quotation
		b-alert(:show="loaded && quotes.length == 0" variant="warning") You currently have no quotes.
		.shopper__table__wrap(v-for="quote in quotes")
			h4.shopper__title Quotation {{quote.quotation_number}}
			table.shopper__table
				thead
					tr
						th Shop name
						th Item Description
						th Direct Link
						th Price
						th Qty
						th Total Amount
				tbody
					tr(v-for="item in quote.items")
						td {{item.store_name}}
						td {{item.item_name}} {{item.color?'(Color:'+item.color+')':''}}
						td: a(:href="item.direct_link" target="_blank") Click Here
						td {{item.user_price}}
						td {{item.quantity}}
						td {{item.admin_price}}
				tfoot
					tr
						td(colspan="6" class="text-right")
							label(v-if="quote.total") Total Amount:
							span(v-if="quote.total")  {{quote.total}}
							.table__action
								a(v-if="quote.status==2" class="button button--accent" :href="siteUrl()+'/personal-shopper/'+quote.id+'/payment'") {{quote.status_text}}
								span(v-if="quote.status!=2") {{quote.status_text}}
		pagination(v-if="quotes && quotes.length > 0" :nextData="getQuotes" :pagination="pagination" :lastPage="lastPage" :page="page")
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
            requestForms: 1,
            formsArray: [1],
            quotes: [],
            page: 1,
            lastPage: 0,
            loaded: false,
            pagination: {},
        };
    },
    created() {
        this.getQuotes(this.page);
    },
    mounted() {},
    methods: {
        addRequest() {
            this.requestForms++;
            this.formsArray.push(this.requestForms);
        },
        removeRequest(n) {
            var index = this.formsArray.indexOf(n);
            this.formsArray.splice(index, 1);
        },
        getQuoteSubmit($event) {
            var _this = this;
            _this.formSubmit({
                url: `${_this.apiUrl()}/personal-shopper`,
                validator: _this.$validator,
                formId: $event.target.id,
                success(data) {
                    _this.formsArray=[1];
					document.getElementById("quoteForm").reset();
					_this.getQuotes();
                }
            });
        },
        getQuotes(page=1) {
            var _this = this;
            _this.apiGet({
                url: `${_this.apiUrl()}/personal-shopper?page=${page}`,
                success(data) {
                    _this.quotes = data.quotations;
                    _this.lastPage = page - 1;
					_this.loaded = true;
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
