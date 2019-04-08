<template lang="pug">
	aside.dashboard__menus
		.select-box
			.select-box__value(@click="toggleSelectBox")
				.dmenu__icon
					img(:src="image")
				a(href="javascript:;") {{ title }}
				.dmenu__arrow
			.select-box__list#dd
				ul.ul-reset.dmenus
					li: c_navItem(link="profile-summary" :callBack="callBack" title="Profile Summary" image="profile-summary.png")
					li: c_navItem(link="action-box" title="Action Box"  :callBack="callBack" image="action-box.png")
					li: c_navItem(link="my-warehouse"  :callBack="callBack" title="My Warehouse" image="warehouse.png")
					li: c_navItem(link="ready-for-shipping"  :callBack="callBack" title="Ready For Shipping" image="ready-for-shipping.png")
					li: c_navItem(link="shipments" title="Shipments"  :callBack="callBack" image="shipments.png")
					li: c_navItem(link="shipments-history" :callBack="callBack" title="Shipments History" image="shipments-history.png")
					li: c_navItem(link="personal-shopper" :callBack="callBack" title="Personal Shopper" image="personal-shopper.png")
					li: c_navItem(link="invoices"  :callBack="callBack" title="Invoices" image="invoices.png")
					li: c_navItem(link="subscriptions-plan" :callBack="callBack" title="Subscriptions Plan" image="subscriptions.png")
					li: c_navItem(link="security-setting" :callBack="callBack" title="Security Setting" image="security.png")
</template>

<script>
import m_common from "Mixins/common";
import c_navItem from "./NavItem.vue";

export default {
	components: {
		c_navItem
	},
	props: [],
	mixins: [m_common],
	data() {
		return {
			title: "Please select",
			image: ""
		};
	},
	computed: {},
	beforeCreate() {},
	created() {},
	mounted() {
		console.log("Mounted");
		var element = document.getElementById(this.$route.path.substr(1));
		if(element){
			element.classList.add("active");
		}
	},
	watch: {
		'$route.path': function (newVal) {
			var active = document.querySelector(".active");
			if (active) {
				active.classList.remove("active");
			}
			var element = document.getElementById(newVal.substr(1));
	   		 if(element){
	   			 element.classList.add("active");
	   		 }
       }

   },
	methods: {
		toggleSelectBox() {
			var x = document.getElementById("dd");
			if (x.style.display === "none" || x.style.display == "") {
				x.style.display = "block";
			} else {
				x.style.display = "none";
			}
		},
		callBack(link, text, image) {
			this.title = text;
			this.image = require("Images/" + image);
			this.$router.push(link);
			const mq = window.matchMedia("(max-width: 1199px)");
			if (mq.matches) {
				this.toggleSelectBox();
			} else {
			}
		},
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
.select-box__value {
	display: none;
}
@media only screen and (max-width: 1199px) {
	.select-box {
		position: relative;
	}
	.select-box__value {
		padding: 10px;
		background: #063c5d;
		color: #fff;
		display: flex;
		border-radius: 5px;
	}
	.select-box__value a {
		color: #fff;
		text-decoration: none;
	}
	.select-box__value img {
		filter: saturate(0) invert(1);
	}
	.select-box__list {
		display: none;
	}
	.dmenus > li + li {
		margin-left: 0px !important;
	}
	.dmenus {
		flex-direction: column !important;
	}
	.dmenu__arrow {
		display: block;
	}
}
</style>
