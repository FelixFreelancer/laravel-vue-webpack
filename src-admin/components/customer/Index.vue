<template lang="pug">
//📣 : Page Template
c_page.customer.page--grey()
	h3(slot="title") Customers
	//router-link.btn.btn-sm.btn--icon.btn--round(
		slot="quickAction"
		to="/app/projects/add"
		title="Add New Project"): i.material-icons add
	download_excel.btn.btn-sm.btn--icon.btn--round(:data="json_data" type="csv" :fields="json_fields" name="Customer.xls" slot="quickAction")
		i.material-icons(title="Download") cloud_download

	//📣 : Page Content
	.customer__container(slot='content')
		.page__card
			v-server-table(
				:url="`${apiUrl()}/user`"
				:columns="datatable.columns"
				:options="datatable.options"
				id="userTable"
				ref="userTable"
				class="user-table")

				router-link(:to="'/app/customer/'+props.row.id" slot="name" slot-scope="props") {{props.row.name}}

				a(:href="'tel:'+props.row.contact_number" slot="contact_number" slot-scope="props") +{{props.row.contact_number}}


				.table-actions(slot="actions" slot-scope="props")
					button(class="table-btn btn btn-light" title="Edit" @click="edit(props.row.id)")
						i.material-icons edit
					router-link(:to="'/app/customer/'+props.row.id" class="table-btn btn btn-light" title="View")
						i.material-icons remove_red_eye
					button(class="table-btn btn btn-light" title="Delete" @click="deleteUser(props.row.id)" v-if="access('customer','delete')")
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
			title:"Customer",
			json_data: [],
            json_fields: {
                'Customer Code': {
			        field: 'customer_code',
			        callback: (value) => {
			            return  (value) ? value : '';
			        }
			    },
                'Name': {
			        field: 'name',
			        callback: (value) => {
			            return  (value) ? value : '';
			        }
			    },
				'Email':  {
			        field: 'email',
			        callback: (value) => {
			            return  (value) ? value : '';
			        }
			    },
                'Contact Number': {
			        field: 'contact_number',
			        callback: (value) => {
			            return  (value) ? value : '';
			        }
			    },
                'Country': {
			        field: 'country',
			        callback: (value) => {
			            return  (value) ? value : '';
			        }
			    },
                'Plan': {
                    field: 'plan_type',
                    callback: (value) => {
                        return (value) ? value : '';
                    }
                },
                'Customer Status': {
                    field: 'user_type',
                    callback: (value) => {
                        return (value) ? value : '';
                    }
                }
            },
            toEdit: false,
            datatable: {
                columns: ["customer_code", "name", "email", "contact_number", "country", "plan_type", "user_type", "actions"],
                options: {
                    initFilters: {
                        type: "customer"
                    },
                    filterable: ["customer_code", "role_id", "contact_number", "type", "name", "email", "cd_phone", "plan_type", "user_type"],
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
                        plan_type: [{
                                id: "free",
                                text: "Free"
                            },
                            {
                                id: "paid",
                                text: "Paid"
                            }
                        ],
                        user_type: [{
                                id: "1",
                                text: "Non-Activated"
                            },
                            {
                                id: "2",
                                text: "Pre-Activated"
                            },
                            {
                                id: "3",
                                text: "Activated"
                            }
                        ]
                    },
                    responseAdapter(resp) {
                        return {
                            data: resp.data.users,
                            count: resp.data.count
                        };
                    },
                    headings: {
                        customer_code: "Customer Code",
                        contact_number: "Contact Number",
                        user_type: "Customer Status",
                        plan_type: "Plan"
                    }
                }
            }
        };
    },
    created() {},
    mounted() {
		this.getCustomer();
	},
    methods: {
		getCustomer() {
            var _this = this;
            this.apiGet({
                url: `${this.apiUrl()}/user?query[type]=customer`,
                success(data) {
                    _this.json_data = data.users;
                }
            });
        },
        edit(data) {
            this.toEdit = data;
            this.$root.$emit("bv::show::modal", "editCustomerModal");
        },
        deleteUser(id) {
            this.toEdit = id;
            this.$root.$emit("bv::show::modal", "alertModal");
        },
		modalClose() {
			this.$refs.userTable.refresh();
			this.$root.$emit("bv::hide::modal", "editCustomerModal");
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
