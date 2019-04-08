import Vue from "vue";
import VueRouter from "vue-router";
import store from "./store";
Vue.use(VueRouter);

/*
* 📣 : Components
*/
import app from "Components/App.vue";
import login from "Components/Login.vue";
import signup from "Components/Signup.vue";
import notFound from "Components/404.vue";
import dashboard from "Components/dashboard/Index.vue";

import setting from "Components/setting/Index.vue";
import settingProfile from "Components/setting/Profile.vue";
import settingPassword from "Components/setting/Password.vue";

import role from "Components/role/Index.vue";

import customer from "Components/customer/Index.vue";
import user from "Components/user/Index.vue";

import country from "Components/country/Index.vue";
import transaction from "Components/Transaction.vue";
import log from "Components/Log.vue";
import warehouse from "Components/Warehouse.vue";

import quotation from "Components/quotation/Index.vue";
import quotationView from "Components/quotation/View.vue";

import shipment from "Components/shipment/Index.vue";
import shipmentAdd from "Components/shipment/Add.vue";
import shipmentTab from "Components/shipment/Tabs.vue";
import shipmentItem from "Components/shipment/Item.vue";

import itemAdd from "Components/shipment/AddItem.vue";

import invoice from "Components/invoice/Index.vue";

import inquiry from "Components/inquiry/Index.vue";

import insurance from "Components/insurance/Index.vue";
/*
* 📣 : Routes
*/
const routes = [
	{
		path: "*",
		component: notFound
	},
	{
		path: "/",
		component: login,
		meta: {
			title: "Login"
		}
	},
	{
		path: "/login",
		component: login,
		name: "login",
		meta: {
			title: "Login"
		}
	},
	{
		path: "/signup",
		component: signup,
		name: "signup",
		meta: {
			title: "SignUp"
		}
	},
	{
		path: "/404",
		component: notFound,
		name: "404",
		meta: {
			title: "Page Not Found"
		}
	},
	{
		path: "/app",
		component: app,
		children: [
			{
				path: "/",
				redirect: "dashboard"
			},
			{
				path: "search/",
				component: require("Components/Search.vue").default,
				meta: {
					title: "Search"
				}
			},
			{
				path: "search/:searchQuery",
				component: require("Components/Search.vue").default,
				props: true,
				meta: {
					title: "Search Results"
				}
			},
			{
				path: "dashboard",
				component: dashboard,
				name: "dashboard",
				meta: {
					title: "Dashboard"
				}
			},
			{
				path: "customers",
				component: customer,
				name: "customer",
				meta: {
					title: "Customer",
					module: "customer"
				}
			},
			{
				path: "customer/:customerId",
				component: require("Components/customer/Tabs.vue").default,
				props: true,
				meta: {
					title: "Customers",
					module: "customer"
				},
				children: [
					{
						path: "",
						component: require("Components/customer/Profile.vue").default,
						meta: {
							title: "Customer Profile"
						}
					},
					{
						path: "communication",
						component: require("Components/customer/Communication.vue").default,
						meta: {
							title: "Customer Communications"
						}
					},
					{
						path: "shipments",
						component: require("Components/shipment/DataTable.vue").default,
						meta: {
							title: "Customer Shipments"
						}
					},
					{
						path: "quotations",
						component: require("Components/quotation/DataTable.vue").default,
						meta: {
							title: "Customer Quotations"
						}
					},
					{
						path: "invoices",
						component: require("Components/invoice/DataTable.vue").default,
						meta: {
							title: "Customer Invoices"
						}
					}
				]
			},
			{
				path: "users",
				component: user,
				name: "user",
				meta: {
					title: "Users"
				}
			},
			{
				path: "countries",
				component: country,
				name: "country",
				meta: {
					title: "Countries",
					module: "countries"
				}
			},
			{
				path: "quotations",
				component: quotation,
				name: "quotations",
				meta: {
					title: "Quotations",
					module: "quotation"
				}
			},
			{
				path: "insurance",
				component: insurance,
				name: "insurance",
				meta: {
					title: "Insurance",
					module: "insurance"
				}
			},
			{
				path: "transactions",
				component: transaction,
				name: "transaction",
				meta: {
					title: "Transactions",
					module: "transaction"
				}
			},
			{
				path: "inquiries",
				component: inquiry,
				name: "inquiry",
				meta: {
					title: "Inquiries",
					module: "inquiry"
				}
			},
			{
				path: "warehouse",
				component: warehouse,
				name: "warehouse",
				meta: {
					title: "Warehouses",
					module: "warehouses"
				}
			},
			{
				path: "invoices",
				component: invoice,
				name: "invoice",
				meta: {
					title: "Invoices",
					module: "invoice"
				}
			},
			{
				path: "logs",
				component: log,
				name: "log",
				meta: {
					title: "Logs",
					module: "log"
				}
			},
			{
				path: "quotations/:toView",
				component: quotationView,
				name: "quotationView",
				props: true,
				meta: {
					title: "Quotations",
					module: "quotation"
				}
			},
			{
				path: "shipments",
				component: shipment,
				name: "shipment",
				meta: {
					title: "Shipments",
					module: "shipment"
				}
			},
			{
				path: "shipments",
				component: shipmentTab,
				name: "shipmentTab",
				props: true,
				children: [
					{
						path: "add",
						component: shipmentAdd,
						name: "shipmentView",
						meta: {
							title: "Add Shipment",
							module: "shipment",
							action: ["store"]
						}
					},
					{
						path: ":shipmentId",
						component: shipmentAdd,
						name: "shipmentAdd",
						props: true,
						meta: {
							title: "View Shipment",
							module: "shipment",
							action: ["view"]
						}
					},
					{
						path: ":shipmentId/items",
						component: shipmentItem,
						name: "shipmentItem",
						props: true,
						meta: {
							title: "View Items in Shipment",
							module: "shipment_item"
						}
					},
					{
						path: ":shipmentId/items/add",
						component: itemAdd,
						name: "itemAdd",
						props: true,
						meta: {
							title: "Add Items in Shipment",
							module: "shipment_item",
							action: ["store"]
						}
					},
					{
						path: ":shipmentId/items/:itemId",
						component: itemAdd,
						name: "itemView",
						props: true,
						meta: {
							title: "View Items in Shipment",
							module: "shipment_item"
						}
					}
				]
			},
			{
				path: "roles",
				component: role,
				name: "role",
				meta: {
					title: "Role Settings"
				}
			},
			{
				path: "settings",
				component: setting,
				children: [
					{
						path: "/",
						redirect: "profile"
					},
					{
						path: "profile",
						component: settingProfile,
						name: "settingProfile",
						meta: {
							title: "Profile Settings"
						}
					},
					{
						path: "password",
						component: settingPassword,
						name: "settingPassword",
						meta: {
							title: "Change Password"
						}
					}
				]
			},
			{
				path: "system-settings",
				component: require("Components/system-setting/Index.vue").default,
				meta: {
					title: "System Settings"
				}
			}
		]
	}
];

const router = new VueRouter({
	routes
});

router.beforeEach((to, from, next) => {
	document.title = to.meta.title + " | Global Parcel Forward";
	next();
	// store
	// 	.dispatch("authenticate", {
	// 		to
	// 	})
	// 	.then(
	// 		res => {
	// 			next();
	// 		},
	// 		error => {
	// 			router.push("/404");
	// 		}
	// 	);
});

export default router;
