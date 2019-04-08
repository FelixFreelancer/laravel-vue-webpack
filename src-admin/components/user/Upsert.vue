<template lang="pug">
b-modal(
	id="upsertModalUser"
	:title="toEdit?'Edit User':'Add User'"
	hide-footer
	@shown="shown"
	@hidden="hidden")
	form(id="upsertFormUser" @submit.prevent="upsert")
		.row(v-show="!changePassword")
			.col
				b-form-group(label="First Name")
					b-input(name="first_name" v-validate="'required'" v-model="form.first_name")
					form-error(:err="errors" name="first_name")
			.col
				b-form-group(label="Last Name")
					b-input(name="last_name" v-validate="'required'" v-model="form.last_name")
					form-error(:err="errors" name="last_name")
			.w-100
			.col
				b-form-group(label="Email")
					b-input(name="email" v-validate="'required'" v-model="form.email")
					form-error(:err="errors" name="email")
			.col
				b-form-group(label="Contact Number")
					b-input(name="cd_phone" v-validate="'required'" v-model="form.cd_phone")
					form-error(:err="errors" name="cd_phone")
			.w-100
			.col(v-if="!toEdit")
				b-form-group(label="Password")
					b-input(type="password" name="password" v-validate="'required'" v-model="form.password")
					form-error(:err="errors" name="password")
			.w-100
			.col( v-if="access('twofa','create') && selectedTwofa" )
				b-form-group(label="2FA")
					b-form-radio-group(
						:options="twofa"
						v-model="selectedTwofa"
						v-validate="'required'"
						name="twofa")
					span(v-show="errors.has('twofa')" class="form-error") {{ errors.first('twofa') }}
			.col( v-if="access('deactive','create')" )
				b-form-group(label="User Status")
					b-form-radio-group(
						:options="status"
						v-model="form.is_active"
						v-validate="'required'"
						name="is_active")
					span(v-show="errors.has('is_active')" class="form-error") {{ errors.first('is_active') }}
			.w-100
			.col
				b-form-group(label="Roles")
					b-form-checkbox-group(stacked name="role_id[]" :options="checkboxRoles" v-model="form.role" v-validate="'required'")
					form-error(:err="errors" name="role_id[]")

		.row(v-if="changePassword")
			.col
				b-form-group(label="Enter New Password")
					b-input(type="password" name="password" v-validate="'required'" v-model="form.password")
					form-error(:err="errors" name="password")
		.form-footer
			button.btn.btn-primary(type="submit") {{changePassword?'Update Password':toEdit?'Update User':'Add User'}}
</template>


<script>
import m_common from "Mixins/common";

export default {
    components: {},
    props: ["toEdit", "modalClose", "changePassword"],
    mixins: [m_common],
    data() {
        return {
            twofa: [
                {
                    text: 'Disable',
                    value: '0'
                }
            ],
            status: [{
                    text: 'Active',
                    value: '1'
                },
                {
                    text: 'Deactive',
                    value: '0'
                }
            ],
            checkboxRoles: [],
            formClone: "",
            form: {
                first_name: "",
                last_name: "",
                role: [],
                cd_phone: "",
                email: "",
                password: "",
                is_active: ""
            }
        };
    },
    created() {},
    mounted() {
        this.formClone = this.jsonClone(this.form);
    },
    computed : {
        selectedTwofa:{
			get(){
				return this.form.twofa ? 1 : 0;
			},
			set(){}
		}
    },
    methods: {
        hidden() {
            this.form = this.formClone;
            this.$validator.reset();
            this.modalClose();
        },
        shown() {
            var _this = this;
            //check if edit mode
            if (_this.toEdit) {
                _this.apiGet({
                    url: `${_this.apiUrl()}/user/${_this.toEdit}`,
                    success(data) {
                        _this.form = data;
                    }
                });
            }
            //get roles
            _this.apiGet({
                url: `${_this.apiUrl()}/role`,
                success(data) {
                    var checkboxItems = [];
                    data.forEach((item, index) => {
                        checkboxItems.push({
                            text: item.name,
                            value: item.id
                        });
                    });
                    _this.checkboxRoles = checkboxItems;
                }
            });
        },
        upsert(event) {
            var _this = this;
            if (_this.toEdit) {
                _this.formSubmit({
                    url: `${_this.apiUrl()}/user/${_this.toEdit}`,
                    formId: event.target.id,
                    validator: _this.$validator,
                    msg: {
                        success: "User Updated Successfully"
                    },
                    success(data) {
                        _this.form = _this.formClone;
                        _this.$validator.reset();
                        _this.hidden();
                    }
                });
            } else {
                _this.formSubmit({
                    url: `${_this.apiUrl()}/user`,
                    formId: event.target.id,
                    validator: _this.$validator,
                    msg: {
                        success: "User Added Successfully"
                    },
                    success(data) {
                        _this.form = _this.formClone;
                        _this.$validator.reset();
                        _this.hidden();
                    }
                });
            }
        }
    }
};
</script>

<style lang="scss">
@import "~ScssConfig";
</style>
