<template lang="pug">
    .app(:class="appClass()")
        //Notifications
        notifications(group="app")
        //Header
        c_header
        //Sidebar
        c_sidebar(v-if="$route.meta.sidebar!=false")
        //Content
        section.content
            router-view
        c_notificationPane
</template>

<script>
import Vue from "vue";
import m_common from "Mixins/common";
import c_header from "Components/header/Header.vue";
import c_sidebar from "Components/Sidebar.vue";
import c_notificationPane from "Components/notification/Index.vue";
import bus from "Bus";

export default {
	components: {
		c_notificationPane,
		c_header,
		c_sidebar
	},
	props: [],
	mixins: [m_common],
	data() {
		return {
			sidebarOpen: true
		};
	},
	beforeCreate: function() {
		if (this.$session.exists()) {
			Vue.http.headers.common["Authorization"] = "Bearer " + this.$session.get("jwt");
		} else {
			this.$router.push("/login");
		}
	},
	created() {
		bus.$on("toggleSidebar", () => {
			this.sidebarOpen = !this.sidebarOpen;
		});
		bus.$on("closeSidebar", () => {
			this.sidebarOpen = false;
		});
		bus.$on("openSidebar", () => {
			this.sidebarOpen = true;
		});
	},
	mounted() {},
	methods: {
		appClass() {
			var _class = "";
			if (this.$route.meta.sidebar != false) {
				_class += "app--sidebar ";
			}
			if (this.$store.getters.ui.sidebar == "small") {
				_class += "app--sidebar-small ";
			}
			return _class;
		}
	}
};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";
.app {
	height: 100vh;
}
.content {
	overflow: auto;
	position: fixed;
	top: header("height");
	bottom: 0;
	left: 0;
	right: 0;
	transition: all 0.3s ease 0s;
}

.app--sidebar {
	.header {
		left: sidebar("width");
	}
	.content {
		left: sidebar("width");
	}
}
</style>
