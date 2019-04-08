<template lang="pug">
    //📣 : Page Template
    c_page.page--grey.settings
        h3(slot="title") User Roles
        //📣 : Page Content
        .settings__container(slot='content')
          .row
              .col-sm-4.offset-sm-4
                  .page__card
                      h4.form-title  Available Roles
                      .role-list
                          .role-list__item(v-for="item in rolesList")
                              .role-list__name {{item.name}}
                              .role-list__action
                                  button.btn.btn-sm.btn--icon.btn--round(@click="edit(item.id)")
                                      i.material-icons edit
                      button.btn.btn-primary(@click="add()") Add New Role
          c_addRole(:actions="actions" :modules="modules" :modalClose="modalClose" :toEdit="toEdit")
</template>
<script>
import c_page from "Components/Page.vue";
import m_common from "Mixins/common";
import c_addRole from "./Create.vue";

export default {
	components: {
		c_addRole,
    c_page
	},
	props: [],
	mixins: [m_common],
	data() {
		return {
			rolesList: [],
			permissionsList: [],
			actions: [],
			modules: {},
			toEdit: false
		};
	},
	created() {},
	mounted() {
		this.getRoles();
		this.getPermissions();
	},
	methods: {
		add() {
			this.$root.$emit("bv::show::modal", "addRoleModal");
		},
		edit(id) {
			this.toEdit = id;
			this.$root.$emit("bv::show::modal", "addRoleModal");
		},
		modalClose() {
			this.toEdit = false;
			this.getRoles();
		},
		getRoles() {
			var _this = this;
			_this.apiGet({
				url: `${_this.apiUrl()}/role`,
				success(data) {
					_this.rolesList = data;
				}
			});
		},
		getPermissions() {
			var _this = this;
			_this.apiGet({
				url: `${_this.apiUrl()}/permission`,
				success(data) {
					_this.permissionsList = data;
					_this.filterModules();
				}
			});
		},

		filterModules() {
			var tempModule = {};
			var tempActions = [];
			this.permissionsList.forEach((item, index) => {
				var action = item.name.split(".")[1];
				var moduleName = item.name.split(".")[0];
				if (!tempModule[moduleName]) {
					tempModule[moduleName] = {};
				}
				tempModule[moduleName][action] = item.id;
				if (tempActions.indexOf(action) < 0) {
					tempActions.push(action);
				}
			});
			this.modules = tempModule;
			this.actions = tempActions;
		}
	}
};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";
.settings__container {
	padding: 20px;
}
.role-list {
	margin-bottom: 30px;
}
.role-list__item {
	display: flex;
	height: 40px;
	align-items: center;
	background: $grey-200;
	border-radius: 5px;
	padding: 0px 20px;
	margin-bottom: 5px;
	&:hover {
		background: $grey-300;
	}
}
.role-list__name {
	flex: 1 1 auto;
	font-weight: bold;
}
</style>
