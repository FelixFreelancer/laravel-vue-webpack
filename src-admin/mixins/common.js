import bus from "Bus";
import moment from "moment";

var mixin = {
	data() {
		return {
			IS_DEV: IS_DEV,
			statusColor: {
				quotation: {
					1: "#FFA000",
					2: "#1976D2",
					3: "#689F38"
				},
				inquiry: {
					"Open": "#FFA000",
					"Closed": "#1976D2"
				},
				shipment: {
					"1": "#FFA000",
					"2": "#E64A19",
					"3": "#689F38",
					"4": "#00796B",
					"5": "#1976D2",
					"6": "#303F9F"
				},
				payment_status : {
					"Paid": "#32CD32",
					"Outstanding":"#E64A19"
				}

			}
		};
	},
	created() {},
	mounted() {},
	computed: {},
	methods: {
		access(moduleName, action) {
			var _this = this;
			if (action) {
				if (_this.$store.getters.permission[moduleName] && _this.$store.getters.permission[moduleName].includes(action)) {
					return true;
				} else {
					return false;
				}
			} else {
				if (_this.$store.getters.permission[moduleName]) {
					return true;
				} else {
					return false;
				}
			}
		},
		setupApp(data) {
			var _this = this;
			_this.$store.dispatch("setUser", data).then(() => {
				this.$router.replace("/app/dashboard");
			});
		},

		apiUrl() {
			return this.$store.getters.api.url;
		},

		btnLoader(loaderTarget, action) {
			var tag = document.querySelector(`#${loaderTarget}`).tagName;
			if (tag.toLowerCase() == "form") {
				var btn = document.querySelector(`#${loaderTarget} button[type="submit"]`);
			} else {
				var btn = document.querySelector(`#${loaderTarget}`);
			}
			if (action == "start") {
				btn.classList.add("btn--loading");
				btn.disabled = true;
			} else {
				btn.classList.remove("btn--loading");
				btn.disabled = false;
			}
		},

		formError(errors, name, scope) {
			if (scope) {
				if (errors.has(name, scope)) {
					return `<span class="form-error">${errors.first(name, scope)}</span>`;
				}
			} else {
				if (errors.has(name)) {
					return `<span class="form-error">${errors.first(name)}</span>`;
				}
			}
		},

		formSubmit(data) {
			var _this = this;
			var validator = data.validator;
			var validatorScope = data.validatorScope ? data.validatorScope : null;
			var url = data.url;
			var formId = data.formId;
			var method = data.method ? data.method : "post";
			console.log(data);
			validator.validateAll(validatorScope).then(result => {
				console.log(result);
				console.log(document.getElementById(formId));
				console.log(new FormData(document.getElementById(formId)));
				if (result) {
					_this.btnLoader(formId, "start");
					console.log(method);
					console.log(_this.$http[method]);
					_this.$http[method](url, new FormData(document.getElementById(formId))).then(
						response => {
							_this.btnLoader(formId, "stop");
							if (_this.apiSuccess(response)) {
								if (data.msg && data.msg.success) {
									_this.$notify({
										group: "app",
										title: data.msg.success,
										type: "success"
									});
								}
								if (data.success) {
									data.success(response.body.data);
								}
							}
						},
						response => {
							_this.btnLoader(formId, "stop");
							if (_this.apiError(response)) {
								if (data.error) {
									data.error(response.data);
								}
							}
						}
					);
				}
			});
		},

		apiPost(data) {
			var _this = this;
			var url = data.url;
			var payload = data.data;
			var method = data.method ? data.method : "post";
			if (data.buttonId) {
				_this.btnLoader(data.buttonId, "start");
			}
			_this.$http[method](url, payload).then(
				response => {
					if (_this.apiSuccess(response)) {
						if (data.msg && data.msg.success) {
							_this.$notify({
								group: "app",
								title: data.msg.success,
								type: "success"
							});
						}
						data.success ? data.success(response.body.data) : "";
					}
					if (data.buttonId) {
						_this.btnLoader(data.buttonId, "stop");
					}
				},
				response => {
					if (_this.apiError(response)) {
						data.error ? data.error(response.data) : "";
					}
					if (data.buttonId) {
						_this.btnLoader(data.buttonId, "stop");
					}
				}
			);
		},

		apiGet(data) {
			var _this = this;
			var url = data.url;
			_this.$http.get(url).then(
				response => {
					if (_this.apiSuccess(response)) {
						data.success(response.body.data);
					}
				},
				response => {
					if (_this.apiError(response)) {
						data.error(response.data);
					}
				}
			);
		},

		apiSuccess(response) {
			var _this = this;
			if (response.body.msg && response.body.msg == "success") {
				if (response.body.info) {
					_this.$notify({
						group: "app",
						title: response.body.info,
						type: "success"
					});
				}
				return response.body.data ? response.body.data : "";
			} else {
				_this.apiError(response);
			}
		},

		apiError(response) {
			var _this = this;
			if (response.body.data && response.body.data.error) {
				var error = response.body.data.error;
				var err = "";
				if (typeof error === "string") {
					err = error;
				}
				if (_this.isArray(error)) {
					err = error[0];
				}
				if (_this.isObject(error)) {
					for (var key in error) {
						err += error[key][0];
					}
				}
				_this.$notify({
					group: "app",
					title: err,
					type: "error"
				});
				return response.body.data ? response.body.data : "";
			} else {
				_this.$notify({
					group: "app",
					title: "Something Went Wrong! Please Try Again.",
					type: "error"
				});
			}
		},

		jsonEmpty(obj) {
			var cloned = this.jsonClone(obj);
			for (key in cloned) {
				if (cloned.hasOwnProperty(key)) {
					cloned[key] = null;
				}
			}
			return cloned;
		},

		jsonClone(obj) {
			return JSON.parse(JSON.stringify(obj));
		},

		isArray(a) {
			return !!a && a.constructor === Array;
		},

		isObject(a) {
			return !!a && a.constructor === Object;
		},

		timestamp(timestamp) {
			return moment.unix(timestamp).format(this.$store.getters.config.dateFormatUi);
		},

		formatDate(date, format) {
			if (!format) format = "YYYY-MM-DD HH:mm:ss";
			return moment(date, format).format(this.$store.getters.config.dateFormatUi);
		},

		formatDateServer(date, format) {
			if (format) {
				return moment(date, format).format(this.$store.getters.config.dateFormat);
			} else {
				return moment(date).format(this.$store.getters.config.dateFormat);
			}
		}
	}
};

export default mixin;
