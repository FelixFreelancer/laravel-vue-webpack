<template lang="pug">
	//📣 : Page Template
	c_page.country.page--grey
		h3(slot="title") Countries
		button.btn.btn-sm.btn--icon.btn--round(
			slot="quickAction"
			title="Add New Country"
			@click="add()")
			i.material-icons add

		//📣 : Page Content
		.country__container(slot='content')
			.page__card
				v-server-table(
					:url="`${apiUrl()}/country`"
					:columns="datatable.columns"
					:options="datatable.options"
					id="countryTable"
					ref="countryTable"
					class="country-table")

					.table-actions(slot="actions" slot-scope="props")
						button(class="table-btn btn btn-light" title="Edit" @click="edit(props.row.id)")
							i.material-icons edit
						button(class="table-btn btn btn-light" title="Delete" @click="deleteCountry(props.row.id)"  v-if="access('countries','delete')")
							i.material-icons delete
				alert(:title="title" :id="toEdit" :cancel="cancel" :ok="ok")
					p(slot="desc") Are you sure you want to delete this?
			c_edit(:toEdit="toEdit" :modalClose="modalClose")
</template>
<script>
import c_page from "Components/Page.vue";
import m_common from "Mixins/common";
import c_edit from "./Edit.vue";
import alert from "Components/common/Alert.vue";

export default {
    components: {
        c_page,
        alert,
        c_edit
    },
    props: [],
    mixins: [m_common],
    data() {
        return {
            title: "Country",
            toEdit: false,
            datatable: {
                columns: [
                    "nice_name",
                    "short_code",
                    "country_code",
                    "suite_number",
                    "iso",
                    "actions"
                ],
                options: {
                    filterable: [
                        "nice_name",
                        "short_code",
                        "country_code",
                        "suite_number",
                        "iso"
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
                    listColumns: {
                        plan_type: [{
                                id: "free",
                                text: "Free"
                            },
                            {
                                id: "paid",
                                text: "Paid"
                            }
                        ]
                    },
                    responseAdapter(resp) {
                        return {
                            data: resp.data.country,
                            count: resp.data.count
                        };
                    },
                    headings: {
                        nice_name: "Country",
                        country_code: "Country Code",
                        suite_number: "Suite Number",
                        iso: "ISO"
                    }
                }
            }
        };
    },
    created() {},
    mounted() {},
    methods: {
		add() {
            this.toEdit = false;
            this.$root.$emit("bv::show::modal", "editCountryModal");
        },
        edit(id) {
            this.toEdit = id;
            this.$root.$emit("bv::show::modal", "editCountryModal");
        },
        modalClose() {
            this.$refs.countryTable.refresh();
            this.$root.$emit("bv::hide::modal", "editCountryModal");
        },
        deleteCountry(id) {
            this.toEdit = id;
            this.$root.$emit("bv::show::modal", "alertModal");
        },
        cancel() {
            this.$root.$emit("bv::hide::modal", "alertModal");
        },
        ok(id) {
            var _this = this;
            this.apiPost({
                url: `${this.apiUrl()}/country/${id}`,
                method: 'delete',
                formId: "countryTable",
                msg: {
                    success: 'Country Deleted Successfully'
                },
                success(data) {
                    _this.$refs.countryTable.refresh();
                }
            });
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
