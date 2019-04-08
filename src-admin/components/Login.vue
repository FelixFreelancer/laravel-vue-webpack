<template lang="pug">
    .login
        notifications(group="app")
        .login__box
            .login__logo
                img(src="~Images/logo.png")
            .login__form
                form(id="loginForm" action="#" @submit.prevent="login($event)")
                    h4.login__title Login
                    .form-group
                        label Username/Email
                        input(type="text" v-model="email" v-validate="'required|email'" class="form-control" name="email")
                        span(v-html="formError(errors,'email')")
                    .form-group
                        label Password
                        input(type="password" v-model="password" v-validate="'required'" class="form-control" name="password")
                        span(v-html="formError(errors,'password')")
                    button(type="submit" class="btn btn-primary") Login
        b-modal(id="loginAuthy" title="Enter 2FA Code" hide-footer @shown="shown")
            form.form(id="loginAuthyForm" @submit.prevent="loginAuthySubmit($event)" data-vv-scope="loginAuth")
                input(type="hidden" name="user_id" v-model="user_id")
                .otp.form-group
                    input.form-control(
                        maxlength="1"
                        type="text"
                        v-validate="'required'"
                        v-for="n in 6"
                        :name="'auth_code['+(n-1)+']'"
                        @keyup="otpEnter"
                        :id="'loginAuth'+n"
                        )
                .form-footer
                    button.btn.btn-primary(type="submit") Verify
</template>

<script>
import m_common from "Mixins/common";
import Vue from "vue";
export default {
	components: {},
	props: [],
	mixins: [m_common],
	data() {
		return {
			email: IS_DEV ? "yo@7span.com" : "",
			password: IS_DEV ? "1234567890" : "",
			user_id: ""
		};
	},
	created() {},
	mounted() {},
	beforeCreate() {
		if (this.$session.exists()) {
			this.$router.push("/app");
		}
	},
	methods: {
		shown() {
			document.querySelector("#loginAuth1").focus();
		},
		otpEnter(event) {
			if (["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"].indexOf(event.key) > -1) {
				event.target.value = event.key;
				if (event.target.nextElementSibling) event.target.nextElementSibling.focus();
			} else if (event.keyCode == 8) {
				if (event.target.previousElementSibling) event.target.previousElementSibling.focus();
			}
		},
		loginAuthySubmit(event) {
			var _this = this;
			_this.formSubmit({
				formId: event.target.id,
				validator: _this.$validator,
				validatorScope: "loginAuth",
				url: `${_this.apiUrl()}/google-authentication`,
				success(data) {
					_this.setSession(data);
				}
			});
		},
		login(event) {
			var _this = this;
			_this.formSubmit({
				formId: event.target.id,
				validator: _this.$validator,
				url: `${_this.apiUrl()}/login`,
				success(data) {
					if (data.authy_required) {
						_this.user_id = data.user_id;
						_this.$root.$emit("bv::show::modal", "loginAuthy");
					} else {
						_this.setSession(data);
					}
				}
			});
		},
		setSession(data) {
			var _this = this;
			_this.$session.start();
			//Set JWT Token
			_this.$session.set("jwt", data.token);
			//Set HTTP Token in header
			Vue.http.headers.common["Authorization"] = "bearer " + data.token;
			_this.setupApp(data);
		}
	}
};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";
.otp {
	display: flex;
	input {
		width: 40px;
		margin-right: 5px;
	}
}
</style>
