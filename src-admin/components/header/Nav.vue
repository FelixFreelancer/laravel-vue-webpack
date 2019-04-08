<template lang="pug">
.nav.nav--header
    //c_notification
    //- router-link.btn(to="#" type='button')
    //-     i.material-icons timeline
    button.btn(type='button' @click="showNPane")
        i.material-icons notifications
</template>

<script>
import c_notification from "./Notification.vue";
import bus from "Bus";
export default {
	components: {
		c_notification
	},
	props: [],
	mixins: [],
	data() {
		return {};
	},
	created() {},
	mounted() {},
	methods: {
		logout() {
			this.$session.destroy();
			this.$router.push("/login");
		},
		showNPane() {
			bus.$emit("toggleNPane");
		}
	}
};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";
.nav--header {
	justify-content: flex-end;
	.btn {
		background: transparent;
		border: none;
		color: $grey-500;
		height: 40px;
		width: 40px;
		padding: 0;
		text-align: center;
		box-shadow: none !important;
		-webkit-appearance: none;
		&:after {
			display: none;
		}
		&:hover,
		&:active {
			color: $grey-900;
		}
	}
}
</style>
