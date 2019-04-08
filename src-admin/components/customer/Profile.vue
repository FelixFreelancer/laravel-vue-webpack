<template lang="pug">
section(v-if="customerDetails.id")
	.card
		.card-body
			.row.align-items-center
				.col-3
					c_userCard.user-card--profile
						span(slot="name") {{customerDetails.first_name+' '+customerDetails.last_name}}
						span(slot="attr") {{customerDetails.customer_code}}
				.col.justify-content-end
					.customer-attrs
						.customer-attr(v-if="access('twofa','create') && customerDetails.twofa")
							i.material-icons(title="2FA") lock_open
							p
								input.tgl.tgl-light(
									type="checkbox"
									:id="'2FA'+customerDetails.id"
									:checked="customerDetails.twofa"
									:value="1"
									name="twofa"
									@click="toggle2FA(customerDetails.id,$event)")
								label.tgl-btn(:for="'2FA'+customerDetails.id")
						.customer-attr( v-if="access('deactive','create')" )
							i.material-icons(title="Active / Deactive") block
							p
								input.tgl.tgl-light(
									type="checkbox"
									:id="'active'+customerDetails.id"
									:checked="customerDetails.is_active"
									:value="1"
									name="is_active"
									@click="toggleStatus(customerDetails.id,$event)")
								label.tgl-btn(:for="'active'+customerDetails.id")

						.customer-attr
							i.material-icons(title="User Verification") done
							p
								input.tgl.tgl-light(
									type="checkbox"
									:id="'user'+customerDetails.id"
									:checked="customerDetails.verify"
									:value="1"
									name="verify"
									@click="toggleUserVerification(customerDetails.id,$event)")
								label.tgl-btn(:for="'user'+customerDetails.id")
						.customer-attr
							i.material-icons(title="Email") email
							p {{customerDetails.email}}
						.customer-attr
							i.material-icons(title="Gender") face
							p {{customerDetails.gender}}
						//- .customer-attr
						//-     i.material-icons {{$t('icon.shipment')}}
						//-     p
						//-         b 4&nbsp;
						//-         span Pending Shipments
						//- .customer-attr
						//-     i.material-icons {{$t('icon.invoice')}}
						//-     p
						//-         b 2&nbsp;
						//-         span Pending Invoices
	.card-deck.address-cards
		//Contact
		.card
			.card-header: h5.mb-0 Contact Details
			.card-body
				.address__block
					table.table.table-sm.customer-table--v
						tr
							td Address
							td {{customerDetails.cd_address}}
						tr
							td City
							td {{customerDetails.cd_city}}
						tr
							td State
							td {{customerDetails.cd_state}}
						tr
							td Country
							td {{customerDetails.cd_country_name}}
						tr
							td Postal Code
							td {{customerDetails.cd_postalcode}}
						tr
							td Contact
							td {{customerDetails.contact_number}}
		//Billing
		.card
			.card-header: h5.mb-0 Billing Address
			.card-body
				.address__block
					table.table.table-sm.customer-table--v
						tr
							td Address
							td {{customerDetails.ba_address}}
						tr
							td City
							td {{customerDetails.ba_city}}
						tr
							td State
							td {{customerDetails.ba_state}}
						tr
							td Country
							td {{customerDetails.cd_country_name}}
						tr
							td Postal Code
							td {{customerDetails.ba_postalcode}}
						tr
							td Contact
							td {{customerDetails.contact_no}}
		//Subscription
		.card
			.card-header: h5.mb-0 Subscription Details
			.card-body
				.address__block
					table.table.table-sm.customer-table--v
						tr
							td Registered On
							td {{timestamp(customerDetails.registered_on)}}
						tr
							td Company Name
							td {{customerDetails.company_name}}
						tr
							td Suite Number
							td {{customerDetails.suite_number}}
						tr
							td Plan Type
							td: h4: .mb-0.badge.badge-primary {{customerDetails.plan_type.toUpperCase()}}
						tr(v-if="customerDetails.plan_type.toUpperCase()!='FREE'")
							td Validity
							td(v-if="customerDetails.membership_validity != ''") {{timestamp(customerDetails.membership_validity)}}
						tr(v-if="customerDetails.plan_type.toUpperCase()!='FREE'")
							td Auto Renew
							td {{customerDetails.auto_renew}}

	.card-deck.m-0.mt-2(v-if="metadata")
		.card.m-0
			.card-header: h6.m-0 Shipment Overview
			.card-body
				table.table.table-sm.metadata-table
					tr
						th(v-for="(item,key) in metadata.shipment.counter") {{key}}
					tr
						td(v-for="(item,key) in metadata.shipment.counter") {{item}}
		.card.m-0.ml-2
			.card-header: h6.m-0 Quotations Overview
			.card-body
				table.table.table-sm.metadata-table
					tr
						th(v-for="(item,key) in metadata.quotation.counter") {{key}}
					tr
						td(v-for="(item,key) in metadata.quotation.counter") {{item}}
	.card-deck.address-cards(v-if="access('security_questions','index') && customerDetails.security_question != ''")
		.card.m-0
			.card-header: h6.m-0 Security Questions
			.card-body
				.address__block
					table.table.table-sm.customer-table--v
						tr(v-for="object in customerDetails.security_question")
							td  {{ object.questions }}
							td  {{ object.answer }}
</template>
<script>
import c_page from "Components/Page.vue";
import m_common from "Mixins/common";
import c_edit from "./Edit.vue";
import c_userCard from "Components/common/UserCard.vue";

export default {
    components: {
        c_page,
        c_edit,
        c_userCard
    },
    props: ["customerDetails", "metadata"],
    mixins: [m_common],
    data() {
        return {};
    },
    created() {},
    mounted() {},
    methods: {
        toggleStatus(id, event) {
            var _this = this;
            var status = event.target.checked ? 1 : 0;
            _this.apiPost({
                url: `${_this.apiUrl()}/user/status`,
                data: {
                    user_id: id,
                    status: status,
                }
            });
        },
        toggle2FA(id, event) {
            var _this = this;
            var status = event.target.checked ? 'enable' : 'disable';
            _this.apiPost({
                url: `${_this.apiUrl()}/twofactor/${status}`,
                data: {
                    user_id: id
                }
            });
        },
        toggleUserVerification(id, event) {
            var _this = this;
            var status = event.target.checked ? 'verify' : 'unverify';
            _this.apiPost({
                url: `${_this.apiUrl()}/user/${status}`,
                data: {
                    user_id: id
                },
                success(data) {
                    _this.$root.$emit("bv::hide::modal", "viewShipmentModal");
                }
            });
        }
    }
};
</script>

<style lang="scss">
@import "~ScssConfig";

.metadata-table {
    margin: 0;
    th {
        border-top: none;
        font-size: 10px;
        text-transform: uppercase;
        font-weight: normal;
        color: $grey-500;
        font-weight: 700;
    }
    td {
        font-size: 24px;
        font-weight: bold;
    }
}
.customer-attrs {
    display: flex;
    justify-content: flex-end;
}
.customer-attr {
    display: flex;
    align-items: center;
    margin-left: 20px;
    p {
        margin: 0;
        font-size: 14px;
    }
    i {
        flex: 0 0 auto;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: $blue-100;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        color: $blue-400;
    }
}
.address-cards {
    table {
        margin-bottom: 0;
    }
    tr:first-child {
        td {
            border-top: 0;
        }
    }
    margin: 10px -5px 0;
    .card {
        margin: 0 5px;
    }
}
.user-card--profile {
    border-radius: 0;
    border: none;
    padding: 0;
    display: flex;
    .user-card__photo {
        flex: 0 0 70px;
        height: 70px;
    }
    .user-card__name {
        font-size: 24px;
        font-weight: bold;
    }
    .user-card__attr {
        font-size: 16px;
        font-weight: bold;
        margin-top: 7px;
    }
}
.customer__container {
    padding: 20px;
}
@import "~ScssConfig";
.customer-table--h {
    margin-bottom: 0;
    th {
        font-weight: normal;
        color: $grey-500;
        border: none;
        padding-top: 0;
        padding-bottom: 0;
    }
    td {
        border: none;
        font-weight: bold;
        padding-top: 0;
    }
}
.customer-table--v {
    th {
        font-weight: normal;
    }
    td {
        color: $grey-500;
        &:nth-child(2) {
            color: #000;
        }
    }
}

.address {
    margin-top: 20px;
}
.address__title {
    text-transform: uppercase;
    font-size: 14px;
    font-weight: normal;
    margin: 0;
    padding: 10px 15px;
    background: $grey-200;
    color: $grey-500;
}
.address__block {}
</style>
