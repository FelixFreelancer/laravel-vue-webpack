<template lang="pug">
	//📣 : Page Template
	c_page.country.page--grey
		h3(slot="title") System Settings

		//📣 : Page Content
		.country__container(slot='content')
			.page__card
				v-server-table(
					:url="`${apiUrl()}/settings`"
					:columns="datatable.columns"
					:options="datatable.options"
					id="systemTable"
					ref="systemTable"
					class="system-table")

					.table-actions(slot="actions" slot-scope="props")
						button(class="table-btn btn btn-light" title="Edit" @click="edit(props.row)" v-if="access('system_settings','edit')")
							i.material-icons edit
			c_edit(:toEdit="toEdit" :modalClose="modalClose")
</template>
<script>
import c_page from "Components/Page.vue";
import m_common from "Mixins/common";
import c_edit from "./Edit.vue";

export default {
    components: {
        c_page,
        c_edit
    },
    props: [],
    mixins: [m_common],
    data() {
        return {
            toEdit: false,
            datatable: {
                columns: [
                    "name",
                    "value",
                    "actions"
                ],
                options: {
                    filterable: [
                        "name",
                    ],
                    perPage: 100,
                    pagination: {
                        dropdown: false
                    },
                    perPageValues: [],
                    sortable: ["id"],
                    orderBy: {
                        ascending: true
                    },
                    filterByColumn: true,
                    skin: "table table-hover",
                    responseAdapter(resp) {
                        return {
                            data: resp.data.setting,
                            count: resp.data.count
                        };
                    }
                }
            }
        };
    },
    created() {},
    mounted() {},
    methods: {
        edit(data) {
            this.toEdit = data;
            this.$root.$emit("bv::show::modal", "editSystemSettingModal");
        },
        modalClose() {
            this.$refs.systemTable.refresh();
            this.$root.$emit("bv::hide::modal", "editSystemSettingModal");
        },
    }
};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";
.country__container {
    padding: 20px;
}
.country-table {
    font-size: 14px;
}
</style>
