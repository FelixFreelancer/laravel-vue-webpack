<template lang="pug">
    b-modal(id="viewShipmentModal" size="lg" :title="'Shipment Details'" hide-footer @hidden="hidden()")
        section(v-if="toView")
            //Shipping In
            c_progressCard(no="1" title="Shipping In" :extraClass="toView.shipping_in?'ship--done':''")
                section(slot="attr")
                    c_shipmentAttr(label="Name" :value="toView.shipping_in.name")
                    c_shipmentAttr(label="Parcel Number" :value="toView.shipping_in.parcel_number")
                    c_shipmentAttr(label="Description" :value="toView.shipping_in.parcel_desc")
                section(slot="info")
                    a.btn.btn-light.ship__author(href="javascript:;" title="Added By")
                        i.material-icons account_circle
                        span {{toView.shipping_in.status_by_name}}

            //Shipping Selected
            c_progressCard(no="2" title="Shipping Provider Selected" :extraClass="toView.shipping_select?'ship--done':''")
                section(slot="attr" v-if="toView.shipping_select")
                    c_shipmentAttr(label="Amount" :value="toView.shipping_select.shipping_out_amount")
                    c_shipmentAttr(label="Service" :value="toView.shipping_select.shipping_out_service")
                    c_shipmentAttr(label="Company" :value="toView.shipping_select.shipping_out_company")

            //Payment
            c_progressCard(no="3" title="Payment" :extraClass="toView.payment?'ship--done':''")
                section(slot="attr" v-if="toView.payment")
                    c_shipmentAttr(label="Transation ID" :value="toView.payment.transaction_id")
                    c_shipmentAttr(label="Merchant ID" :value="toView.payment.merchant_account_id")
                    c_shipmentAttr(label="Seller ID" :value="toView.payment.seller_account_id")
                    c_shipmentAttr(label="Payment Gateway Status" :value="toView.payment.payment_gateway_status")
                    c_shipmentAttr(label="Payment Gateway Type" :value="toView.payment.payment_gateway_type")
                section(slot="info" v-if="toView.payment && !toView.payment_verified")
                    button.btn.btn-info.payment-verify(@click="paymentVerify($event)") Marke As Verified

            //Payment Verify
            c_progressCard(no="4" title="Payment Verified" :extraClass="toView.payment_verified?'ship--done':''")
                section(slot="info" v-if="toView.payment_verified")
                    a.btn.btn-light.ship__author(href="javascript:;" title="Verified By")
                        i.material-icons account_circle
                        span {{toView.shipping_in.status_by_name}}

            //Shipped
            c_progressCard(no="5" title="Shipped" :extraClass="toView.shipped?'ship--done':''")
                section(slot="attr" v-if="toView.shipped")
                    c_shipmentAttr(label="Tracking Number" :value="toView.shipped.shipping_out_tracking")
                    c_shipmentAttr(label="Tracking Link" :value="toView.shipped.shipping_tracking_link")
                    c_shipmentAttr(label="Shipped At" :value="toView.shipped.shipping_out_at")
                section(slot="info" v-if="toView.shipped")
                    a.btn.btn-light.ship__author(href="javascript:;" title="Marked By")
                        i.material-icons account_circle
                        span {{toView.shipping_in.status_by_name}}
                section(slot="info" v-if="!toView.shipped && toView.payment_verified")
                    form(id="markAsShipped" @submit.prevent="markAsShipped($event)")
                        input.form-control(
                            type="text"
                            v-validate="'required'"
                            placeholder="Tracking Number"
                            name="tracking_no")
                        span(v-html="formError(errors,'tracking_no')")
                        input.form-control(
                            type="text"
                            v-validate="'required'"
                            placeholder="Tracking Link"
                            name="tracking_link")
                        span(v-html="formError(errors,'tracking_link')")
                        button.btn.btn-info(
                            type="submit"
                            ) Mark As Shipped
                        //class="btn-shipped"

            //Delivered
            c_progressCard(no="6" title="Delivered" :extraClass="toView.delivered?'ship--done':''")
                section(slot="attr" v-if="!toView.delivered && toView.shipped")
                    button.btn.btn-info(
                        id="markAsDelivered"
                        @click="markAsDelivered($event)"
                        type="submit") Mark As Delivered
                section(slot="attr" v-if="toView.delivered")
                    c_shipmentAttr(label="Amount" :value="toView.delivered.shipping_out_amount")
                    c_shipmentAttr(label="Service" :value="toView.delivered.shipping_out_service")
                    c_shipmentAttr(label="Company" :value="toView.delivered.shipping_out_company")
</template>


<script>
import m_common from "Mixins/common";
import c_shipmentAttr from "./ShipmentAttr.vue";
import c_progressCard from "./ProgressCard.vue";
export default {
	components: {
		c_shipmentAttr,
		c_progressCard
	},
	props: ["toView", "table"],
	mixins: [m_common],
	data() {
		return {};
	},
	created() {},
	mounted() {},
	methods: {
		paymentVerify(event) {
			var _this = this;
			_this.apiPost({
				buttonId: event.target.id,
				url: `${_this.apiUrl()}/shipment/${_this.toView.shipping_in.id}/update-status?status=4`,
				msg: {
					success: "Payment Marked As Verified"
				},
				success(data) {
					_this.$root.$emit("bv::hide::modal", "viewShipmentModal");
				}
			});
		},
		markAsDelivered(event) {
			var _this = this;
			_this.apiPost({
				url: `${_this.apiUrl()}/shipment/${_this.toView.shipping_in.id}/update-status?status=6`,
				buttonId: event.target.id,
				msg: {
					success: "Shipment is marked as Delivered"
				},
				success(data) {
					_this.$root.$emit("bv::hide::modal", "viewShipmentModal");
				}
			});
		},
		markAsShipped(event) {
			var _this = this;
			_this.formSubmit({
				url: `${_this.apiUrl()}/shipment/${_this.toView.shipping_in.id}/update-status?status=5`,
				validator: _this.$validator,
				formId: event.target.id,
				msg: {
					success: "Shipment is marked as Shipped"
				},
				success(data) {
					_this.$root.$emit("bv::hide::modal", "viewShipmentModal");
				}
			});
		},
		hidden() {
			this.table.refresh();
		}
	}
};
</script>

<style lang="scss">
@import "~ScssConfig";
.ship__author {
	font-size: 14px;
	padding: 3px 10px;
	color: $grey-700;
	i {
		font-size: 18px;
	}
}
.ship {
	padding: 10px 0;
	position: relative;
	&.ship--done {
		.ship__count {
			position: relative;
			overflow: hidden;
			&:after {
				content: "";
				position: absolute;
				left: 0;
				top: 0;
				right: 0;
				bottom: 0;
				background: $green-500;
				content: "check";
				font-family: "Material Icons";
				color: #fff;
				text-align: center;
			}
		}
	}
}
.ship__info {
	margin-left: 60px;
}
.ship__attrs section {
	display: flex;
	flex-wrap: wrap;
}
.ship__attr {
	flex: 0 1 auto;
	margin-right: 20px;
	margin-bottom: 10px;
	label {
		color: $grey-500;
		margin: 0;
		line-height: 14px;
		font-size: 14px;
	}
	p {
		margin: 0;
		line-height: 16px;
		font-size: 14px;
	}
}
.ship__header {
	display: flex;
	align-items: center;
	&:after {
		content: "";
		position: absolute;
		width: 2px;
		left: 20px;
		background: $grey-200;
		bottom: 0;
		top: 0;
	}
}
.ship__count {
	flex: 0 0 40px;
	height: 40px;
	text-align: center;
	line-height: 40px;
	background: $grey-300;
	border-radius: 50%;
	color: $grey-500;
	z-index: 2;
	margin: 0;
	font-size: 18px;
}
.ship__title {
	font-size: 18px;
	margin: 0;
	margin-left: 20px;
}
#markAsShipped input{
  width:50%;
}
.btn-shipped{
  display:block;
}

</style>
