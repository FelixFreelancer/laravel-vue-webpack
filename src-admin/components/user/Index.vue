<template lang="pug">
//📣 : Page Template
c_page.customer.page--grey
	h3(slot="title") Users
	button.btn.btn-sm.btn--icon.btn--round(
		slot="quickAction"
		title="Add New User"
		@click="add()")
		i.material-icons add

	//📣 : Page Content
	.customer__container(slot='content')
		.page__card
			v-server-table(
				:url="`${apiUrl()}/user`"
				:columns="datatable.columns"
				:options="datatable.options"
				ref="userTable"
				class="user-table")

				span(slot="name" slot-scope="props") {{props.row.first_name}} {{props.row.last_name}}

				.table-actions(slot="actions" slot-scope="props")
					button(class="table-btn btn btn-light" title="Edit User" @click="edit(props.row.id)")
						i.material-icons edit
					button(class="table-btn btn btn-light" title="Change Password" @click="edit(props.row.id,true)")
						i.material-icons lock
					button(class="table-btn btn btn-light" title="Delete" @click="deleteUser(props.row.id)" v-if="access('admin','delete')")
						i.material-icons delete
			alert(:title="title" :id="toEdit" :cancel="cancel" :ok="ok")
				p(slot="desc") Are you sure you want to delete this?
		c_upsert(:toEdit="toEdit" :modalClose="modalClose" :changePassword='isChangePassword')
</template>
<script>
import c_page from "Components/Page.vue";
import m_common from "Mixins/common";
import c_upsert from "./Upsert.vue";
import alert from "Components/common/Alert.vue";

export default {
    components: {
        alert,
        c_page,
        c_upsert
    },
    props: [],
    mixins: [m_common],
    data() {
        return {
            title: 'User',
            toEdit: false,
            isChangePassword: false,
            datatable: {
                columns: ["name", "email", "cd_phone", "role", "actions"],
                options: {
                    filterable: ["name", "email", "cd_phone", "role", "plan_type"],
                    perPage: 25,
                    pagination: {
                        dropdown: false
                    },
                    perPageValues: [],
                    sortable: ["id"],
                    orderBy: {
                        ascending: false
                    },
                    filterByColumn: true,
                    skin: "table table-hover",
                    listColumns: {
                        role: []
                    },
                    responseAdapter(resp) {
                        return {
                            data: resp.data.users,
                            count: resp.data.count
                        };
                    },
                    headings: {
                        customer_code: "Code",
                        cd_phone: "Contact No",
                        plan_type: "Plan"
                    }
                }
            }
        };
    },
    created() {
        this.filterRoles();
    },
    mounted() {},
    methods: {
        filterRoles() {
            var _this = this;
            _this.apiGet({
                url: `${_this.apiUrl()}/role`,
                success(data) {
                    var roleItems = [];
                    data.forEach((item, index) => {
                        roleItems.push({
                            text: item.name,
                            id: item.id
                        });
                    });
                    _this.datatable.options.listColumns.role = roleItems;
                }
            });
        },
        add() {
            this.toEdit = false;
            this.$root.$emit("bv::show::modal", "upsertModalUser");
        },
        edit(id, password) {
            this.toEdit = id;
            if (password) {
                this.isChangePassword = true;
            } else {
                this.isChangePassword = false;
            }
            this.$root.$emit("bv::show::modal", "upsertModalUser");
        },
        modalClose() {
            this.isChangePassword = false;
            this.$refs.userTable.refresh();
            this.$root.$emit("bv::hide::modal", "upsertModalUser");
        },
        deleteUser(id) {
            this.toEdit = id;
            this.$root.$emit("bv::show::modal", "alertModal");
        },
        cancel() {
            this.$root.$emit("bv::hide::modal", "alertModal");
        },
        ok(id) {
            var _this = this;
            this.apiPost({
                url: `${this.apiUrl()}/user/${id}`,
                method: 'delete',
                formId: "userTable",
                msg: {
                    success: 'User Deleted Successfully'
                },
                success(data) {
                    _this.$refs.userTable.refresh();
                }
            });
        },
    }
};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";
.customer__container {
    padding: 20px;
}
.user-table {
    font-size: 14px;
}
</style>
