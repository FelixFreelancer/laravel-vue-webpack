<template lang="pug">
	.row
		.col-sm-4.offset-sm-2
			.page__card
				form.form(id="changePassword" @submit.prevent="changePassword($event)")
					h4.form-title Change Password
					.form-group
						label Current Password
						input(type="password" v-validate="'required'" class="form-control" v-model="current_password" name="current_password")
						span(v-html="formError(errors,'current_password')")
					.form-group
						label New Password
						input(type="password" v-validate="'required'" class="form-control" v-model="new_password" name="new_password")
						span(v-html="formError(errors,'new_password')")
					.form-footer
						button.btn.btn-primary(type="submit") Update
		.col-sm-4
			.page__card
				h4.form-title 2 Factor Authentication
				.form
					.form-group.d-flex.align-items-center
						input.tgl.tgl-light(
							type="checkbox"
							id="2fa"
							v-model="authy_required"
							@change="toggle2FA"
							name="2fa")
						label.tgl-btn(for="2fa")
						small.ml-3.text-muted {{authy_required?'Enabled':'Disabled' }} | 2FA is powered by Gogole Authentication.
					.row.mt-3(v-if="!authy && qr")
						.col
							h3.dashboard__content__title  Set Up Two-Factor with Google Auth
							ul.ul-list
								li After installing the Google Authenticator app scan the QR code below and enter the verification code you see in the Google Authenticator app
								li.qr-code: img(:src="qr")
								li After scanning the QR Code, enter the verification code.
								li: input.code_input(name="code" v-model="code")
								li
									|Your second-factor backup code is
									b  {{ key }}.
									|This can be used for manual setup, and is necessary to recover your account in case your mobile phone is lost or stolen.
								li
									button.btn.btn-primary#verification_button(@click="verifyCode") Verify Code
</template>

<script>
import c_page from "Components/Page.vue";
import m_common from "Mixins/common";

export default {
	components: {
		c_page
	},
	props: [],
	mixins: [m_common],
	data() {
		return {
			qr:'',
			authy:false,
			current_password: "",
			new_password: "",
			key: "",
			code: "",
			authy_required: false
		};
	},
	created() {
		this.get2FA();
	},
	mounted() {},
	methods: {
		get2FA() {
			var _this = this;
			_this.apiGet({
				url: `${_this.apiUrl()}/twofactor/status`,
				data: {
					user_id: this.$store.getters.user.id
				},
				success(data) {
					_this.authy_required = data.authy_required;
					_this.authy = data.authy_required;
				}
			});
		},
		toggle2FA() {
			var _this = this;
			var status = this.authy_required ? "enable" : "disable";
			_this.apiPost({
				url: `${_this.apiUrl()}/twofactor/${status}`,
				data: {
					user_id: this.$store.getters.user.id
				},
				success(data) {
					if(status == "enable"){
						_this.qr=data['qr'];
						_this.key = data['secret'];
					}else{
						_this.qr="";
						_this.key="";
					}
				}
			});
		},
		verifyCode() {
			var _this = this;
			_this.apiPost({
				url: `${_this.apiUrl()}/twofactor/verify`,
				data:{
					secret: _this.key,
					code: _this.code
				},
				success(data) {
					_this.code="";
					_this.get2FA();
				}
			});
		},
		changePassword(event) {
			var _this = this;
			_this.formSubmit({
				formId: event.target.id,
				url: `${_this.apiUrl()}/change-password`,
				validator: _this.$validator,
				msg: {
					success: "Password Changed Successfully."
				},
				success(data) {
					_this.new_password = "";
					_this.current_password = "";
					_this.$nextTick(() => {
						_this.$validator.reset();
					});
				},
				error(data) {
					_this.new_password = "";
					_this.current_password = "";
					_this.$nextTick(() => {
						_this.$validator.reset();
					});
				}
			});
		}
	}
};
</script>

<style lang="scss">
.avtar--setting {
	position: relative;
}
.ul-list{
	list-style-type: none;
	padding: 1px;
}
.ul-list li{
	margin: 1em 0;
}
.qr-code{
	height:50%;
}
.code_input{
	height: 40px;
	width: 100%;
	padding: 1px;
}
</style>
