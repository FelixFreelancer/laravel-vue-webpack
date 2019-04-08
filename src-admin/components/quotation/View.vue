<template lang="pug">
c_page.quote.page--grey(backLink="/app/quotations")
	h3(slot="title") Quotation : {{toView}}

	.quote__container(slot='content')
		.row
			.col-3
				.card
					.card-header: b Quotation {{toView}}
					ul.list-group.list-group-flush.quote__user
						li.list-group-item
							label Status
							h5: .badge(:style="{backgroundColor:statusColor.quotation[quoteData.status]}") {{statusText}}
						li.list-group-item
							label Created At
							p {{formatDate(quoteData.created_at)}}
						li.list-group-item
							label Last Updated
							p {{formatDate(quoteData.updated_at)}}
				.card.mt-2
					.card-header Requested By
					router-link.user-card-link(:to="'/app/customer/'+userData.id")
						c_userCard.user-card--large
							span(slot="name"): b {{userData.first_name}} {{userData.last_name}}
							span(slot="attr") {{userData.customer_code}}
					ul.list-group.list-group-flush.quote__user
						li.list-group-item
							label Email
							p {{userData.email}}
						li.list-group-item
							label Contact No
							p {{userData.cd_phone}}

			.col-9
				.page__card
					h4.quote__title Reply To Quote Request
					form(id="quoteSubmit" @submit.prevent="submit($event)")
						v-server-table(
							:url="`${apiUrl()}/quotations/${toView}`"
							:columns="datatable.columns"
							:options="datatable.options"
							ref="quoteTable"
							class="quote-table")

							a(slot="item_name" slot-scope="props" :href="props.row.direct_link" target="_blank") {{props.row.item_name}}

							span(slot="admin_price" slot-scope="props")
								div(v-if="isQuoteEditable") 	£
									input(
										type="text"
										class="quote__price"
										v-validate="'required'"
										data-vv-as="'Price'"
										v-model="props.row.admin_price"
										:name="'admin_price['+props.row.id+']'")
									div(v-html="formError(errors,'admin_price['+props.row.id+']')")
								div(v-if="!isQuoteEditable") £{{props.row.admin_price}}

						.form-footer
							button.btn.btn-primary(type="submit") Send
</template>
<script>
import c_page from "Components/Page.vue";
import m_common from "Mixins/common";
import c_userCard from "Components/common/UserCard.vue";

export default {
    components: {
        c_page,
        c_userCard
    },
    props: ["toView"],
    mixins: [m_common],
    data() {
        var _this = this;
        return {
            toEdit: false,
            userData: "",
            isQuoteEditable: false,
            quoteData: "",
            statusText: "",
            datatable: {
                columns: ["store_name", "item_name", "quantity", "user_price", "admin_price"],
                options: {
                    filterable: [],
                    perPage: 10,
                    pagination: {
                        dropdown: false
                    },
                    perPageValues: [],
                    sortable: ["id"],
                    orderBy: {
                        ascending: false
                    },
                    filterByColumn: false,
                    skin: "table table-hover",
                    responseAdapter(resp) {
                        _this.userData = resp.data.user;
                        _this.isQuoteEditable = resp.data.is_editable;
                        _this.quoteData = resp.data.quotations[0];
                        _this.statusText = resp.data.status;
                        return {
                            data: resp.data.quotation_items.items,
                            count: resp.data.quotation_items.count
                        };
                    },
                    headings: {
                        store_name: "Store",
                        user_price: "User Quote",
                        admin_price: "GPF Quote"
                    }
                }
            }
        };
    },
    created() {},
    mounted() {},
    methods: {
        submit(event) {
            this.formSubmit({
                formId: event.target.id,
                validator: this.$validator,
                url: `${this.apiUrl()}/quotations/${this.toView}`,
                msg: {
                    success: "Quotation updated successfully."
                },
                success(data) {}
            });
        }
    }
};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";
.quote-table {
    font-size: 14px;
}
.quote__container {
    padding: 30px;
}
.quote__user {
    .badge {
        color: #fff;
    }
    li {
        justify-content: center;
        display: flex;
        flex-direction: column;
    }
    label {
        font-size: 13px;
        color: $grey-500;
        margin: 0;
        display: inline-block;
    }
    p {
        margin: 0;
        font-size: 13px;
    }
}
.quote__price {
    width: 100px;
}
.quote__title {
    margin-bottom: 30px;
}
</style>
