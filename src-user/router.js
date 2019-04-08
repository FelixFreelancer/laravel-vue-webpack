import Vue from "vue";
import VueRouter from "vue-router";
import store from "./store";
Vue.use(VueRouter);

/*
* 📣 : Routes
*/
const routes = [
	{
		path: "*",
		component: require("Components/404.vue").default
	},
	{
		path: "/",
		redirect: "/app"
	},
	{
		path: "/app",
		component: require("Components/App.vue").default,
		children: [
			{
				path: "/",
				redirect: "/profile-summary",
				meta: {
					title: "Profile Summary"
				}
			},
			{
				path: "/profile-summary",
				component: require("Components/ProfileSummary.vue").default,
				meta: {
					title: "Profile Summary"
				}
			},
			{
				path: "/action-box",
				component: require("Components/ActionBox.vue").default,
				meta: {
					title: "Action Box"
				}
			},
			{
				path: "/my-warehouse",
				component: require("Components/MyWarehouse.vue").default,
				meta: {
					title: "My Warehouse"
				}
			},
			{
				path: "/ready-for-shipping",
				component: require("Components/ReadyForShipping.vue").default,
				meta: {
					title: "Ready For Shipping"
				}
			},
			{
				path: "/shipments",
				component: require("Components/Shipments.vue").default,
				meta: {
					title: "Shipments"
				}
			},
			{
				path: "/shipments-history",
				component: require("Components/ShipmentsHistory.vue").default,
				meta: {
					title: "Shipment History"
				}
			},
			{
				path: "/personal-shopper",
				component: require("Components/PersonalShopper.vue").default,
				meta: {
					title: "Personal Shopper"
				}
			},
			{
				path: "/invoices",
				component: require("Components/Invoices.vue").default,
				meta: {
					title: "Invoices"
				}
			},
			{
				path: "/subscriptions-plan",
				component: require("Components/SubscriptionsPlan.vue").default,
				meta: {
					title: "Subscriptions Plan"
				}
			},
			{
				path: "/security-setting",
				component: require("Components/SecuritySetting.vue").default,
				meta: {
					title: "Security Setting"
				}
			}
		]
	}
];

const router = new VueRouter({
	routes
});


router.beforeEach((to, from, next) => {
	document.title = to.meta.title + " | GlobalParcelForward";
	store
		.dispatch("authenticate", {
			to
		})
		.then(
			res => {
				next();
			},
			error => {
				router.push("/404");
			}
		);
});

export default router;
