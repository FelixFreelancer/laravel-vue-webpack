<template lang="pug">
    c_page.quote.page--grey
        h3(slot="title") Quotations
        .quote__container(slot='content')
            c_dataTable
</template>
<script>
import c_page from "Components/Page.vue";
import m_common from "Mixins/common";
import c_dataTable from "./DataTable.vue";

export default {
	components: {
		c_page,
		c_dataTable
	},
	props: [],
	mixins: [m_common],
	data() {
		var _this = this;
		return {};
	},
	created() {},
	mounted() {},
	methods: {}
};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";
.quote__container {
	padding: 20px;
}
</style>
