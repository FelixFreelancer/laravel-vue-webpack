import Vue from "vue";
import VueX from "vuex";
Vue.use(VueX);

//To Persist The Data When Page Reloaded
import createPersistedState from "vuex-persistedstate";

var store = new VueX.Store({
	state: {
		api: {
			url: "https://www.globalparcelforward.com/ajax"
			//url: "https://gpf.preview.im/ajax"
		},
		site: {
			url: "https://www.globalparcelforward.com/"
			//url: "https://gpf.preview.im/"

		},
		config: {
			dateFormat: "DD-MM-YYYY HH:mm:ss",
			dateFormatUi: "DD.MM.YYYY HH:mm"
		},
		lang: "",
		user: {
			email: "",
			role_id: "",
			first_name: "",
			last_name: "",
			id: "",
			gender: ""
		},
		permission: {},
		ui: {
			sidebar: "small"
		},
		const: {}
	},
	getters: {
		api(state) {
			return state.api;
		},
		site(state) {
			return state.site;
		},
		user(state) {
			return state.user;
		},
		permission(state) {
			return state.permission;
		},
		config(state) {
			return state.config;
		},
		getLang(state) {
			return state.lang;
		},
		ui(state) {
			return state.ui;
		}
	},
	mutations: {
		setUser(state, data) {
			state.user = data.user;
			var _permissions = {};
			data.permissions.forEach((item, index) => {
				var action = item.split(".")[1];
				var moduleName = item.split(".")[0];
				if (!_permissions[moduleName]) {
					_permissions[moduleName] = [];
				}
				_permissions[moduleName].push(action);
			});
			state.permission = _permissions;
		},
		setLang(state, data) {
			state.lang = data;
		},
		sidebar(state, data) {
			state.ui.sidebar = data;
		},
		reset(state, data) {
			// TODO: Write better reset method
			state.user = {
				email: "",
				role_id: "",
				first_name: "",
				last_name: "",
				id: "",
				gender: ""
			};
			state.permission = [];
		}
	},
	actions: {
		setLang(context, data) {
			return new Promise((resolve, reject) => {
				if (data.lang) {
					context.commit("setLang", data.lang);
					resolve(true);
				} else {
					resolve(false);
				}
			});
		},
		setUser(context, data) {
			context.commit("setUser", data);
		},
		toggleSidebar(context, data) {
			context.commit("sidebar", context.state.ui.sidebar == "small" ? "full" : "small");
		},
		authenticate(context, data) {
			return new Promise((resolve, reject) => {
				if (data.to.meta && data.to.meta.module) {
					var moduleToView = data.to.meta.module;
					var permission = context.state.permission;
					if (context.state.permission[moduleToView]) {
						resolve(true);
					} else {
						reject(false);
					}
				} else {
					resolve(true);
				}
			});
		},
		reset(context, data) {
			context.commit("reset", data);
		}
	},
	plugins: [
		createPersistedState({
			key: "vue-gpf-user"
		})
	]
});

export default store;
