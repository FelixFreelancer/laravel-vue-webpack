<template lang="pug">
    .login
        notifications(group="app")
        .login__intro
            h1 👋 Hello.
            p You have found the pseudo TEST deployment of Team Central. (It is NOT done; it works, we are using it internally, but we're not actively promoting this quite yet) We got tired of struggling with other systems only to get frustrated with lack of needed features, really strange operational characteristics, overly complex processes, and compounded with an unwillingness to make changes.
            p Don't get me wrong, it's their code and they can do whatever they desire - BUT, we don't have to be a client.
            p.dots
        .login__box
            .login__logo
                img(src="~Images/logo.png")
            .login__form
                form(id="loginForm" action="#" @submit.prevent="")
                    h4.login__title Sign Up
                    .form-group
                        label Domain
                        input(type="text" v-validate="'required'" class="form-control" name="domain")
                        span(v-html="formError(errors,'domain')")
                    .form-group
                        label Username/Email
                        input(type="text" v-validate="'required|email'" class="form-control" name="email")
                        span(v-html="formError(errors,'email')")
                    .form-group
                        label Password
                        input(type="password" v-validate="'required'" class="form-control" name="password")
                        span(v-html="formError(errors,'password')")
                    .form-message(v-show="formMessage") {{formMessage}}
                    button(type="submit" class="btn btn-secondary" id="loginFormSubmit") Signup
            .login__extra
                p Already have an account?
                    router-link(to="/login") Login Here
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
			formMessage: ""
		};
	},
	created() {},
	mounted() {},
	beforeCreate() {
		if (this.$session.exists()) {
			this.$router.push("/app");
		}
	},
	methods: {}
};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";
</style>
