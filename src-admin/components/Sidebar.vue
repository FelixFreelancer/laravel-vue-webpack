<template lang="pug">
aside.sidebar
	//- .brand
	//- 	.brand__logo
	//- 		img(src="~Images/logo-white.svg")

	c_userCard.user--sidebar
		span(slot="name") {{this.$store.getters.user.first_name}}
		span(slot="attr") Hello
		img(slot="photo" v-if="this.$store.getters.user.image_name" :src='this.$store.getters.user.image_name'  alt='')

	nav.navigation
		ul.nav.nav--sidebar
			li.nav-item
				router-link.nav-link(to="/app/dashboard")
					i.material-icons {{$t('icon.dashboard')}}
					span Dashboard

			li.nav-item(v-if="access('shipment')")
				router-link.nav-link(to="/app/shipments")
					i.material-icons {{$t('icon.shipment')}}
					span Shipments
			li.nav-item(v-if="access('quotation')")
				router-link.nav-link(to="/app/quotations")
					i.material-icons {{$t('icon.quotation')}}
					span Quotations
			li.nav-item
				router-link.nav-link(to="/app/invoices")
					i.material-icons {{$t('icon.invoice')}}
					span Invoices
			li.nav-item
				router-link.nav-link(to="/app/insurance")
					i.material-icons security
					span Insurance
			li.nav-item(v-if="access('customer')")
				router-link.nav-link(to="/app/customers")
					i.material-icons {{$t('icon.customer')}}
					span Customers
			li.nav-item(v-if="access('transaction')")
				router-link.nav-link(to="/app/transactions")
					i.material-icons {{$t('icon.transaction')}}
					span Transactions
			li.nav-item(v-if="access('inquiry')")
				router-link.nav-link(to="/app/inquiries")
					i.material-icons contact_mail
					span Inquiries
			li.nav-divider
			li.nav-item
				router-link.nav-link(to="/app/users")
					i.material-icons {{$t('icon.user')}}
					span Users
			li.nav-item(v-if="access('log')")
				router-link.nav-link(to="/app/logs")
					i.material-icons {{$t('icon.quotation')}}
					span Logs
			li.nav-item
				router-link.nav-link(to="/app/countries")
					i.material-icons {{$t('icon.country')}}
					span Countries
			li.nav-item
				router-link.nav-link(to="/app/warehouse")
					i.material-icons {{$t('icon.warehouse')}}
					span Warehouses
			li.nav-item(v-if="access('role')")
				router-link.nav-link(to="/app/roles")
					i.material-icons assignment_ind
					span User Roles
			li.nav-item
				a.nav-link(href="javascript:;")
					i.material-icons {{$t('icon.setting')}}
					span Settings
				ul.nav.nav--sidebar(id="submenu")
					li.nav-item(v-if="access('system_settings','index')")
						router-link.nav-link(to="/app/system-settings")
							i.material-icons build
							span System
					li.nav-item
						router-link.nav-link(to="/app/settings")
							i.material-icons {{$t('icon.user')}}
							span User
			li.nav-divider
			li.nav-item
				a.nav-link(href="javascript:;" @click="logout()")
					i.material-icons {{$t('icon.logout')}}
					span Logout
</template>

<script>
import m_common from "Mixins/common";
import c_userCard from "Components/common/UserCard.vue";

import bus from "Bus";
export default {
	components: {
		c_userCard
	},
	props: [],
	mixins: [m_common],
	data() {
		return {};
	},
	computed: {},
	beforeCreate() {},
	created() {},
	mounted() {},
	methods: {
		logout() {
			var _this = this;
			_this.$store.dispatch("reset").then(() => {
				this.$session.destroy();
				this.$router.replace("/");
			});
			// _this.$http.post(`${this.$store.getters.apiEndpoint}/logout/`).then(
			// 	response => {
			// 		var data = _this.apiSuccess(response);
			// 		if (data) {
			// 		}
			// 	},
			// 	response => {
			// 		_this.formError(response);
			// 	}
			// );
		}
	}
};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";

.user-card.user--sidebar {
	border: none;
	.user-card__details {
		display: flex;
		flex-direction: column-reverse;
	}
	.user-card__name {
		color: #fff;
	}
	.user-card__attr {
		margin: 0 0 3px 0;
		color: rgba(255, 255, 255, 0.5);
	}
}
.navigation {
	flex: 1 1 auto;
	overflow: auto;
	padding: 10px 0;
}
.sidebar {
	width: sidebar("width");
	background: linear-gradient(to bottom, color("secondary") 0%, $teal-900 100%);
	position: fixed;
	top: 0;
	left: 0;
	bottom: 0;
	overflow: auto;
	display: flex;
	flex-direction: column;
	padding-top: 20px;
	transition: all 0.3s ease 0s;
}
.user-card--sidebar {
	flex: 0 0 auto;
	color: $grey-300;
	padding: 0 20px;
	margin-bottom: 10px;
	.user-card__photo {
		transition: all 0.3s ease 0s;
	}
	.user-card__name {
		font-size: 20px;
		font-weight: bold;
		small {
			font-size: 14px;
			display: block;
		}
	}
}

.nav-count {
	margin-left: 5px;
	background: color("primary");
	padding: 0px 5px;
	height: 20px;
	line-height: 20px;
	border-radius: 20px;
}
.nav--sidebar {
	flex-direction: column;
	margin: 0 1rem;
	flex-wrap: nowrap;
	.nav-item {
		+ .nav-item {
			margin-top: 5px;
		}
	}
	.nav-divider {
		border-top: 1px solid rgba(255, 255, 255, 0.2);
		margin: 8px 0;
		text-transform: uppercase;
		color: rgba(255, 255, 255, 0.2);
		font-size: 16px;
		letter-spacing: 2px;
		padding-top: 5px;
	}
	.nav-link {
		color: rgba(255, 255, 255, 0.8);
		border-radius: 5px;
		align-items: center;
		display: flex;
		font-size: 14px;
		line-height: normal;
		padding: 7px 10px;
		i {
			margin-right: 5px;
			font-size: 20px;
		}
		&:hover {
			background: rgba(255, 255, 255, 0.1);
			box-shadow: shadow("raised");
		}
		&.router-link-active {
			color: #fff;
			background: color("primary");
			box-shadow: shadow("raised");
		}
		&[data-toggle="collapse"] {
			position: relative;
			&:after {
				content: "keyboard_arrow_down";
				font-family: "Material Icons";
				position: absolute;
				right: 0.5rem;
				top: 0;
				bottom: 0;
				display: flex;
				align-items: center;
				font-size: 1rem;
			}
		}
		&[data-toggle="collapse"]:not(.collapsed) {
			color: #fff;
			&:after {
				content: "keyboard_arrow_up";
			}
		}
	}
	.nav {
		margin-right: 0;
		margin-top: 0.3rem;
		border-left: 1px solid rgba(255, 255, 255, 0.2);
		padding-left: 0.5rem;
		&.collapse {
			display: none;
			&.show {
				display: flex;
			}
		}
	}
}

.brand {
	display: flex;
	align-items: center;
	padding: 20px;
	width: 100%;
	flex: 0 0 auto;
}
.brand__logo {
	text-align: center;
	img {
		width: 100%;
	}
}

//Small sidebar
.app--sidebar.app--sidebar-small {
	.content {
		left: 60px;
	}
	.header {
		left: 60px;
	}
	.nav--sidebar {
		margin: 0px 10px;
	}
	.sidebar {
		width: 60px;
		.user-card {
			padding: 0 10px;
		}
		.user-card__photo {
			flex: 0 0 40px;
			height: 40px;
		}
		.user-card__details {
			display: none;
		}
		.nav-link {
			width: 40px;
			height: 40px;
			border-radius: 3px;
			span {
				display: none;
			}
		}
	}
}
</style>
