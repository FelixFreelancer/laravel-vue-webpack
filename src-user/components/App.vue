<template lang="pug">
.app
	.container
		//Notifications
		notifications(group="app")
		.d-content
			c_sidebar
			.dashboard__content.card-shadow
				.dashboard__content-wrap
					router-view
</template>

<script>
import Vue from "vue";
import m_common from "Mixins/common";
import c_sidebar from "Components/sidebar/Index.vue";
import bus from "Bus";

export default {
    components: {
        c_sidebar,
        m_common
    },
    props: [],
    mixins: [],
    data() {
        return {
            sidebarOpen: true
        };
    },
    created() {},
    mounted() {},
    methods: {}
};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";

.d-content {
    display: flex;
    margin: 30px 0 70px;
}
</style>
