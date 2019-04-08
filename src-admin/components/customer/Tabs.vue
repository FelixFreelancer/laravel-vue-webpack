<template lang="pug">
    //📣 : Page Template
    c_page.page--grey.customer(backLink="/app/customers")
        h3(slot="title") Customer: {{customerDetails.id?customerDetails.first_name+' '+customerDetails.last_name:''}}
        ul.nav.nav-pills.nav--page(slot="action" v-if="customerId")
            li.nav-item
                router-link.nav-link(:to="'/app/customer/'+customerId")
                    i.material-icons info
                    span Customer Info
            li.nav-item
                router-link.nav-link(:to="'/app/customer/'+customerId+'/shipments'")
                    i.material-icons {{$t('icon.shipment')}}
                    span Shipments ({{customerMetadata?customerMetadata.shipment.total:''}})
            li.nav-item
                router-link.nav-link(:to="'/app/customer/'+customerId+'/quotations'")
                    i.material-icons {{$t('icon.quotation')}}
                    span Quotations ({{customerMetadata?customerMetadata.quotation.total:''}})
            li.nav-item
                router-link.nav-link(:to="'/app/customer/'+customerId+'/invoices'")
                    i.material-icons {{$t('icon.invoice')}}
                    span Invoices ({{customerMetadata?customerMetadata.invoice.total:''}})
            li.nav-item
                router-link.nav-link(:to="'/app/customer/'+customerId+'/communication'")
                    i.material-icons email
                    span Communication
        //📣 : Page Content
        .customer__container(slot='content')
            router-view(:customerId="customerId" :customerDetails="customerDetails" :metadata="customerMetadata")
</template>
<script>
import c_page from "Components/Page.vue";
import m_common from "Mixins/common";
export default {
	components: {
		c_page
	},
	props: ["customerId"],
	mixins: [m_common],
	data() {
		return {
			customerDetails: {},
			customerMetadata: false
		};
	},
	created() {},
	mounted() {
		this.getCustomerDetails();
		this.getCustomerMetadata();
	},
	methods: {
		getCustomerDetails() {
			var _this = this;
			_this.apiGet({
				url: `${_this.apiUrl()}/user/${_this.customerId}`,
				success(data) {
					_this.customerDetails = data;
				}
			});
		},
		getCustomerMetadata() {
			var _this = this;
			_this.apiGet({
				url: `${_this.apiUrl()}/user/${_this.customerId}/metadata`,
				success(data) {
					_this.customerMetadata = data;
				}
			});
		}
	}
};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";
.customer__container {
	padding: 20px;
}
</style>
