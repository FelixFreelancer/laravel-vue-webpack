<template lang="pug">
    b-modal(id="addRoleModal" size="lg" :title="toEdit?'Edit Role':'Add New Role'" hide-footer @hidden="close()" @shown="getRole()")
        form(id="addRole" @submit.prevent="upsertRole($event)")
            .form-group
                label Role Name
                input.form-control(
                    type="text"
                    v-validate="'required'"
                    v-model="name"
                    name="name")
                span(v-html="formError(errors,'name')")
            .form-group
                label Permission Settings
                table.table.table-striped.role-table.mt-3
                    tr
                        th
                        th(v-for="action in actions") {{permissionTitles[action]}}
                    tr(v-for="(row,rowKey) in modules")
                        td: b {{permissionTitles[rowKey]}}
                        td(v-for="action in actions")
                            div(v-if="row[action]")
                                input.tgl.tgl-light(
                                    type="checkbox"
                                    :id="'permission'+row[action]"
                                    v-model="permissions"
                                    :checked="permissions"
                                    :value="row[action]"
                                    name="permissions[]")
                                label.tgl-btn(:for="'permission'+row[action]")
                            div(v-if="!row[action]"): small.text-muted N/A
            .form-footer.form-footer--has-margin
                button.btn.btn-primary(type="submit") {{toEdit?'Update Role':'Add Role'}}
</template>


<script>
import m_common from "Mixins/common";

export default {
	components: {},
	props: ["toEdit", "actions", "modules", "modalClose"],
	mixins: [m_common],
	data() {
		return {
			permissions: [],
			name: "",
			permissionTitles: {
				index: "View",
				create: "Add/Create",
				edit: "Edit/Update",
				delete: "Delete",
				customer: "Customers",
				shipment: "Shipments",
				shipment_item: "Shipment Items",
				quotation: "Quotations",
				countries: "Countries",
				warehouses: "Warehouses",
				transaction: "Transactions",
				log: "Logs",
				role: "Role Management",
				invoice: "Invoices",
				twofa: "2FA",
				inquiry: "Inquiry",
				admin: "Admin",
				security_questions: "Security Questions",
				system_settings: "System Settings",
				deactive: "Active / Deactive",
			}
		};
	},
	created() {},
	mounted() {},
	methods: {
		upsertRole(event) {
			var _this = this;
			if (_this.toEdit) {
				_this.formSubmit({
					url: `${_this.apiUrl()}/role/${_this.toEdit}`,
					validator: _this.$validator,
					formId: event.target.id,
					msg: {
						success: "Role Updated Successfully"
					},
					success(data) {
							_this.$root.$emit("bv::hide::modal", "addRoleModal");
						// _this.$store.dispatch("setUser", data).then(() => {
						//
						// });
					}
				});
			} else {
				_this.formSubmit({
					url: `${_this.apiUrl()}/role`,
					validator: _this.$validator,
					formId: event.target.id,
					msg: {
						success: "Role Added Successfully"
					},
					success(data) {
						_this.$root.$emit("bv::hide::modal", "addRoleModal");
					}
				});
			}
		},
		getRole() {
			var _this = this;
			if (_this.toEdit) {
				_this.apiGet({
					url: `${_this.apiUrl()}/role/${_this.toEdit}`,
					success(data) {
						_this.name = data.role.name;
						_this.permissions = [];
						data.permission.forEach((item, index) => {
							_this.permissions.push(item.id);
						});
					}
				});
			}
		},
		close() {
			var _this = this;
			_this.modalClose();
			_this.permissions = [];
			_this.name = "";
			_this.$validator.reset();
		}
	}
};
</script>

<style lang="scss">
@import "~ScssConfig";
.role-table {
	font-size: 14px;
}
</style>
