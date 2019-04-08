<template lang="pug">
    header.header
        .sidebar-toggle
            button.btn.btn--icon(@click="toggleSidebar()")
                i.material-icons menu
        .container-fluid
            .row.header__row
                .col.header__left
                    router-link.header__logo(to="/app/dashboard")
                        img(src="~Images/logo.png" alt="")
                .col.header__search
                    form(@submit.prevent="searchHit")
                        input(type="text" placeholder="Search" v-model="searchQuery")
                        button(type="submit"): i.material-icons search
                .col.header__nav
                    c_nav
</template>

<script>
import c_breadcrumb from "./Breadcrumb.vue";
import c_nav from "./Nav.vue";
import bus from "Bus";

export default {
	components: {
		c_breadcrumb,
		c_nav
	},
	props: [],
	mixins: [],
	data() {
		return {
			searchQuery: ""
		};
	},
	created() {},
	mounted() {},
	methods: {
		toggleSidebar() {
			this.$store.dispatch("toggleSidebar");
		},
		searchHit() {
			this.$router.replace("/app/search/" + encodeURIComponent(this.searchQuery));
		}
	}
};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";

.header__left {
	display: flex;
	align-items: center;
	flex-grow: 0;
}
.header__search {
	form {
		display: flex;
		align-items: center;
	}
	button {
		background: $grey-300;
		border: none;
		padding: 0;
		flex: 0 0 34px;
		height: 34px;
		line-height: 34px;
		display: flex;
		align-items: center;
		border-radius: 0 5px 5px 0;
		justify-content: center;
		outline: 0 !important;
		cursor: pointer;
		i {
			font-size: 20px;
			color: $grey-700;
		}
	}
	input {
		flex: 0 0 300px;
		border: none;
		background: $grey-200;
		height: 34px;
		line-height: 34px;
		padding: 0px 10px;
		font-size: 14px;
		border-radius: 5px 0 0 5px;
		outline: 0 !important;
		&:focus {
			background: $grey-300;
		}
	}
}
.sidebar-toggle {
	flex: 0 0 40px;
	margin-left: 15px;
	.btn {
		box-shadow: none !important;
	}
}
.header {
	position: fixed;
	transition: all 0.3s ease 0s;
	top: 0;
	left: 0;
	right: 0;
	height: header("height");
	//box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
	border-bottom: 1px solid $grey-300;
	background: #fff;
	display: flex;
	align-items: center;
	//z-index: 99;
}
.header__row {
	height: 100%;
	align-items: center;
}
.header__logo {
	img {
		height: 30px;
	}
}
.header__nav {
	text-align: right;
}
</style>
