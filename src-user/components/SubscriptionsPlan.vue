<template lang="pug">
section
	h3.dashboard__content__title Subscription plan
	b-alert(:show="isValid" variant="warning") Your plan is downgrade to Free Plan, But you can still enjoy premium feature till {{validity}}
	.sub-wrap
		.sub-plan(v-if="userData.plan_type == 'paid'")
			span.sub-wrap__title Your subscribed plan
			.auto-renew
				.sc
					img(src="~Images/premium.png")
					.sc__type
						span.type Premium Member
						span.started(v-if="paymentData.first_payment")  (started: {{paymentData.first_payment.started_at}})
				//- form(ref="autorenew" :action="siteUrl()+'/ajax/user/autorenew'" method="post")
				//- 	.ar
				//- 		input(type="hidden" name="payment_id" v-if="paymentData.last_payment" v-model="paymentData.last_payment.id")
				//- 		input(type="hidden" name="autorenew"  v-model="autorenew")
				//- 		input(type="checkbox" id="ar" v-model="userData.auto_renew" @click="toggleAutoRenew($event)")
				//- 		label(for="ar")
				//- 			span Auto renew every month
				//- 			span.ar__next (Next bill {{userData.membership_validity}})
			a.button.button--accent.button--small(:href="paymentObject.paypal.unsubscribe_url" v-if="paymentData.last_payment && paymentData.last_payment.payment_gateway_type == 1") Downgrade plan
			//- a.button.button--accent.button--small(:href="siteUrl()+'/downgrade-plan?downgrade=true'" v-if="paymentData.last_payment && paymentData.last_payment.payment_gateway_type == 2") Downgrade plan
		.sub-plan(v-if="userData.plan_type == 'free'")
			.auto-renew
				.sc
					img(src="~Images/free.png")
					.sc__type
						span.type Free Member
			a.button.button--accent.button--small(:href="siteUrl()+'/users/'+userData.id+'/payment?upgrade=true'") Upgrade plan
		//- .paym-wrap(v-if="userData.plan_type == 'paid' && paymentData.last_payment")
		//- 	span.sub-wrap__title Your Payment Method
		//- 	.paym
		//- 		.paym__item
		//- 			span.paym__title {{ paymentData.last_payment.payment_gateway_type_text }}
		//- 		.paym__item
		//- 			a.button.button--accent.button--small(:href="siteUrl()+'/users/'+userData.id+'/payment?method=1'")  Change payment method
</template>


<script>
import m_common from "Mixins/common";
import moment from "moment";

export default {
	components: {},
	props: [],
	mixins: [m_common],
	data() {
		return {
			currentDate : moment(new Date()).format("YYYY-MM-DD HH:mm:ss"),
			userData: {},
			autorenew:0,
			paymentObject: {},
			paymentData: {},
		};
	},
	created() {
		this.getUser();
	},
	mounted() {},
	computed: {
		isValid(){
			return this.userData.plan_type == 'free' && moment(this.userData.membership_validity_time).isAfter(this.currentDate);
		},
		validity(){
			return this.userData.membership_validity_time != ''  ? moment(this.userData.membership_validity_time).format("DD MMMM YYYY hh:mm a") : '';
		}
	},
	methods: {
		getUser() {
			var _this = this;
			_this.apiGet({
				url: `${_this.apiUrl()}/profile`,
				success(data) {
					_this.userData = data.user;
					_this.paymentObject = data.payment;
					if(_this.userData.plan_type == "paid"){
						_this.getPaymentDetail();
					}
				}
			});
		},
		getPaymentDetail() {
			var _this = this;
			_this.apiPost({
				url: `${_this.apiUrl()}/get-payment-detail`,
				success(data) {
					_this.paymentData = data;
				}
			});
		},
		toggleAutoRenew(event) {
			this.autorenew = event.target.checked ? 1 : 0;
			setTimeout(()=>{
				this.$refs.autorenew.submit();
			},500);
		}
	}
};
</script>

<style lang="css">
</style>
