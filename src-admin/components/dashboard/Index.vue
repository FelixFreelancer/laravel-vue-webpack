<template lang="pug">
	//📣 : Page Template
	c_page.dashboard.page--grey
		h3(slot="title") Dashboard
		div(slot="action")
			v-date-picker.dashboard__duration(
				mode='range'
				:attributes='dashboardRange'
				v-model='selectedDate'
				is-double-paned
				popoverAlign="right"
				popover-visibility="focus"
				@input="durationChange"
				:max-date='maxDate')

		//📣 : Page Content
		.dashboard__content(slot='content')
			.dashcards
				.row
					.col
						c_dashcard.dashcard--green(:cardData="cardData.green")
					.col
						c_dashcard.dashcard--red(:cardData="cardData.red")
					.col
						c_dashcard.dashcard--blue(:cardData="cardData.blue")
					.col
						c_dashcard.dashcard--purple(:cardData="cardData.purple")
			.attention.mt-4
				//h5 Needs Attention!
				.card-deck.pending
					.card
						.card-header: h5.mb-0 Pending Shipments
						.card-body
							table.table.table-sm
								tr
									th #Parcel
									th Customer
									th Status
									th Received On
								tr(v-for="item in pending.shipments")
									td: router-link(:to="'/app/shipments/'+item.id") {{item.parcel_number}}
									td: router-link(:to="'/app/customer/'+item.user_id") {{item.username}}
									td: span(:style="{color:statusColor.shipment[item.status]}") {{item.status_text}}
									td {{timestamp(item.received_on)}}
						.card-footer
							router-link.button.btn.btn-primary.btn-sm(to="/app/shipments") View All
					.card
						.card-header: h5.mb-0 Pending Quote Requests
						.card-body
							table.table.table-sm
								tr
									th #Quotation
									th Customer
									th Price
									th Requested On
								tr(v-for="item in pending.quotations")
									td: router-link(:to="'/app/quotations/'+item.quotation_number") {{item.quotation_number}}
									td: router-link(:to="'/app/customer/'+item.user_id") {{item.username}}
									td {{item.price}}
									td {{timestamp(item.requested_on)}}
						.card-footer
							router-link.btn.btn-primary.btn-sm(to='/app/quotations') View All
			.attention.mt-4
				//h5 Needs Attention!
				.card-deck.pending
					.card
						.card-header: h5.mb-0 Invoices
						.card-body
							table.table.table-sm
								tr
									th #Invoice
									th Customer
									th Type
									th Price
									th Status
									th Invoice Date
								tr(v-for="item in pending.invoices")
									td: a(:href="item.invoice" target="_blank") {{item.invoice_number}}
									td: router-link(:to="'/app/customer/'+item.user_id") {{item.user_name}}
									td {{item.type}}
									td {{item.total}}
									td(:style="{color:statusColor.payment_status[item.status]}") {{item.status}}
									td {{timestamp(item.invoice_date)}}
						.card-footer
							router-link.btn.btn-primary.btn-sm(to='/app/invoices') View All
					.card
						.card-header: h5.mb-0 Photo Requests
						.card-body
							table.table.table-sm
								tr
									th #Shipment
									th Item
									th Customer
									th Requested On
								tr(v-for="item in pending.photo_requests")
									td: router-link(:to="'/app/shipments/'+item.shipment_id") {{item.parcel_number}}
									td: router-link(:to="'/app/shipments/'+item.shipment_id+'/items/'+item.id") {{item.name}}
									td: router-link(:to="'/app/customer/'+item.user_id") {{item.user_name}}
									td {{timestamp(item.requested_at)}}
</template>
<script>
import c_page from "Components/Page.vue";
import c_dashcard from "Components/dashboard/Dashcard.vue";
import m_common from "Mixins/common";
import moment from "moment";

export default {
    components: {
        c_page,
        c_dashcard
    },
    props: [],
    mixins: [m_common],
    data() {
        return {
            pending: "",
            dashboardRange: [{}],
            selectedDate: {
                end: moment()
                    .endOf("day")
                    .toDate(),
                start: moment()
                    .startOf("day")
                    .toDate()
            },
            cardData: {
                green: {
                    title: "Shiments",
                    icon: "shopping_cart",
                    value: "*"
                },
                blue: {
                    title: "Quote Requests",
                    icon: "receipt",
                    value: "*"
                },
                red: {
                    title: "Users",
                    icon: "people",
                    value: "*"
                },
                purple: {
                    title: "Revenue",
                    icon: "monetization_on",
                    value: "*"
                }
            }
        };
    },
    created() {
        var _this = this;
        _this.getCounter();
        _this.getPending();
    },
    mounted() {},
    computed: {
        maxDate() {
            return moment()
                .endOf("day")
                .toDate();
        }
    },
    methods: {
        durationChange() {
            // console.log(moment(this.selectedDate.start).format("DD-MM-YYYY hh:mm:ss A"));
            // console.log(moment(this.selectedDate.end).format("DD-MM-YYYY hh:mm:ss A"));
            this.getCounter();
        },
        getCounter() {
            var _this = this;
            var duration = `?start_date=${moment(this.selectedDate.start).format("DD-MM-YYYY")}&end_date=${moment(this.selectedDate.end).format("DD-MM-YYYY")}`;
            var url = _this.apiUrl() + "/get-counter" + duration;
            _this.apiGet({
                url,
                success(data) {
                    _this.cardData.green.value = data.orders;
                    _this.cardData.red.value = data.users;
                    _this.cardData.blue.value = data.request_for_quote;
                    _this.cardData.purple.value = data.revenue;
                }
            });
        },
        getPending() {
            var _this = this;
            _this.apiGet({
                url: _this.apiUrl() + "/pending?page=1&limit=10",
                success(data) {
                    _this.pending = data;
                }
            });
        }
    }
};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";
.pending {
    font-size: 13px;
    table {
        th {
            border-top: 0;
        }
    }
}
.dashboard__duration {
    width: 250px;
    position: relative;
    input {
        width: 100%;
        border: none;
        letter-spacing: 1px;
        outline: 0;
        position: relative;
        z-index: 2;
        background: transparent;
        cursor: pointer;
    }
    &:after {
        content: "date_range";
        font-family: "Material Icons";
        position: absolute;
        right: 10px;
        top: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        font-size: 24px;
    }
}
.dashboard {}
.dashboard__content {
    padding: 30px;
}
.dashcards {
    .row {
        margin: 0 -10px;
        + .row {
            margin-top: 20px;
        }
    }
    .col {
        padding: 0 10px;
    }
    .page__row {
        margin: 0;
    }
}
.dashcard {
    height: 100%;
    border-radius: 10px !important;
    .dashcard__header {
        border-radius: 10px;
    }
}
.chart1 {
    height: 300px;
    position: relative;
}
</style>
