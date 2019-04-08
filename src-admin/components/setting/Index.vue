<template lang="pug">
    //📣 : Page Template
    c_page.page--grey.settings
        h3(slot="title") User Settings
        ul.nav.nav-pills.nav--page(slot="action")
            li.nav-item
                router-link.nav-link(to="/app/settings/profile" activeClass="active")
                    i.material-icons info
                    span Profile
            li.nav-item
                router-link.nav-link(to="/app/settings/password" activeClass="active")
                    i.material-icons lock
                    span Password
        //📣 : Page Content
        .settings__container(slot='content')
            router-view
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
		return {};
	},
	created() {},
	mounted() {},
	methods: {}
};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";
.settings__container {
	padding: 20px;
}
</style>
