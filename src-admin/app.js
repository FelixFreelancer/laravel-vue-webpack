/*
* 📣 : Config
*/
import Vue from "vue";

/*
* 📣 : VueX Setup
*/
import store from "./store.js";

/*
* 📣 : JsonExcel Setup
*/
import JsonExcel from 'vue-json-excel'
Vue.component('download_excel', JsonExcel)

/*
* 📣 : Vue Router
*/
import router from "./router.js";

/*
* 📣 : Sync Router With Store
*/
import { sync } from "vuex-router-sync";
sync(store, router);

/*
* 📣 : Bootstrap Vue
*/
import BootstrapVue from "bootstrap-vue";
Vue.use(BootstrapVue);

/*
* 📣 : Datatable
*/
import { ServerTable, ClientTable, Event } from "vue-tables-2";

Vue.use(
	ServerTable,
	{
		sortIcon: {
			base: "sort-icon",
			up: "sort-icon__up",
			down: "sort-icon__down",
			is: "sort-icon__sortable"
		}
	},
	false,
	"bootstrap4",
	"default"
);
Vue.use(
	ClientTable,
	{
		sortIcon: {
			base: "sort-icon",
			up: "sort-icon__up",
			down: "sort-icon__down",
			is: "sort-icon__sortable"
		}
	},
	false,
	"bootstrap4",
	"default"
);

/*
* 📣 : Vee Validate
*/
import VeeValidate from "vee-validate";
Vue.use(VeeValidate, {
	classes: true
});

/*
* 📣 : Vue Resource
*/
import VueResource from "vue-resource";
Vue.use(VueResource);

/*
* 📣 : Vue Session
*/
import VueSession from "vue-session";
Vue.use(VueSession, {
	persist: true
});

/*
* 📣 : Vue Calendar
*/
import VCalendar from "v-calendar";
import "v-calendar/lib/v-calendar.min.css";
Vue.use(VCalendar);

/*
* 📣 : Vue Notification
*/
import Notifications from "vue-notification";
Vue.use(Notifications);

/*
* 📣 : Vue Select
*/
import vSelect from "vue-select";
Vue.component("v-select", vSelect);

/*
* 📣 : Multilanguage
*/
import VueI18n from "vue-i18n";
Vue.use(VueI18n);
import messages from "./lang/lang.js";
var currentLocale;
if (store.getters.getLang) {
	currentLocale = store.getters.getLang;
} else {
	currentLocale = "en";
	store.dispatch("setLang", { lang: "en" });
}
const i18n = new VueI18n({
	locale: currentLocale,
	messages
});

/*
* 📣 : Vue Croppa
*/
import Croppa from "vue-croppa";
import "vue-croppa/dist/vue-croppa.css";
Vue.use(Croppa);

import formError from "Components/common/FormError.vue";
Vue.component("form-error", formError);

/*
* 📣 : Initialize Vue
*/
import style from "Styles/style.scss";
import index from "Components/Index.vue";
window.app = new Vue({
	el: "#app",
	store,
	components: { index },
	template: "<index/>",
	router: router,
	i18n,
	http: {
		options: {
			emulateJSON: true
		}
	}
});
