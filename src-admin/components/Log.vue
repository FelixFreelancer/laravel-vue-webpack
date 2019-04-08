<template lang="pug">
	//📣 : Page Template
	c_page.logs.page--grey
		h3(slot="title") Logs
		span(class="btn btn-sm btn--icon btn--round"
				@click="deleteLog(id=0)"  v-if="access('log','delete')"
				slot="quickAction"
				title="Delete Logs"): i.material-icons delete

		//📣 : Page Content
		.log__container(slot='content')
			.page__card
				v-server-table(
					:url="`${apiUrl()}/logs`"
					:columns="datatable.columns"
					:options="datatable.options"
					ref="logTable"
					class="log-table")

					span(slot="notes" slot-scope="props" v-html="props.row.notes")

					span(slot="date" slot-scope="props") {{ timestamp(props.row.date) }}

					.table-actions(slot="actions" slot-scope="props")
						button(class="table-btn btn btn-light" title="Delete" @click="deleteLog(props.row.id)"  v-if="access('log','delete')")
							i.material-icons delete
				alert(:title="title" :id="toEdit" :cancel="cancel" :ok="ok")
					p(v-if="deleteAll" slot="desc") Are you sure you want to delete all logs?
					p(v-if="!deleteAll" slot="desc") Are you sure you want to delete this?
</template>
<script>
import c_page from "Components/Page.vue";
import m_common from "Mixins/common";
import alert from "Components/common/Alert.vue";

export default {
    components: {
        c_page,
        alert,
    },
    props: [],
    mixins: [m_common],
    data() {
        return {
            title: "Log",
            toEdit: false,
						deleteAll: false,
            datatable: {
                columns: ["id", "name", "action", "notes", "date", "actions"],
                options: {
                    filterable: ["id", "name", "notes", "role"],
                    perPage: 100,
                    pagination: {
                        dropdown: false
                    },
                    perPageValues: [],
                    sortable: [],
                    orderBy: {
                        ascending: false
                    },
                    filterByColumn: true,
                    skin: "table table-hover",
                    responseAdapter(resp) {
                        return {
                            data: resp.data.logs,
                            count: resp.data.count
                        };
                    },
                    headings: {
                        id: "ID"
                    }
                }
            }
        };
    },
    created() {},
    mounted() {},
    methods: {
        deleteLog(id) {
					this.deleteAll = false;
					  if(id != 0){
							this.toEdit = id;
						}
						else{
							this.deleteAll = true;
						}
            this.$root.$emit("bv::show::modal", "alertModal");
        },
        cancel() {
            this.$root.$emit("bv::hide::modal", "alertModal");
        },
        ok(id) {
            var _this = this;
						var url = '';
						var method = '';
						if(_this.deleteAll == true)
						{
							url = `${this.apiUrl()}/logs`;
							method='delete';
						}
						else {
							url = `${this.apiUrl()}/logs/${id}`;
							method='delete';
						}
						this.apiPost({
							url: url,
							method: method,
							formId: "logTable",
							msg: {
								success: 'Log Deleted Successfully'
							},
							success(data) {
								_this.$refs.logTable.refresh();
							}
						});
        },
    }
};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";
.log__container {
    padding: 20px;
}
.log-table {
    font-size: 14px;
}
</style>
