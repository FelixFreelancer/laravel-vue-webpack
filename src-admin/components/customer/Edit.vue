<template lang="pug">
b-modal(
	id="editCustomerModal"
	title="Edit Customer"
	hide-footer
	@shown="shown"
	@hidden="hidden")
	form(id="upsertFormUser" @submit.prevent="upsert")
		input(type="hidden" name="email" v-model="form.email")
		.row
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
				b-form-group(label="Gender")
					b-form-select(
						:options="form.genders"
						v-model="form.gender_val"
						v-validate="'required'"
						name="gender")
					span(v-show="errors.has('gender')" class="form-error") {{ errors.first('gender') }}
			.col
				b-form-group(label="Company Name")
					b-input(name="company_name" v-validate="'required'" v-model="form.company_name")
					form-error(:err="errors" name="company_name")
			.w-100
			.col
				b-form-group(label="Contact State")
					b-input(name="cd_state" v-validate="'required'" v-model="form.cd_state")
					form-error(:err="errors" name="cd_state")
			.col
				b-form-group(label="Contact City")
					b-input(name="cd_city" v-validate="'required'" v-model="form.cd_city")
					form-error(:err="errors" name="cd_city")
			.w-100
			.col
				b-form-group(label="Contact Address")
					b-input(name="cd_address" v-validate="'required'" v-model="form.cd_address")
					form-error(:err="errors" name="cd_address")
			.col
				b-form-group(label="Contact Postalcode")
					b-input(name="cd_postalcode" v-validate="'required'" v-model="form.cd_postalcode")
					form-error(:err="errors" name="cd_postalcode")
			.w-100
			.col
				b-form-group(label="Billing State")
					b-input(name="ba_state" v-validate="'required'" v-model="form.ba_state")
					form-error(:err="errors" name="ba_state")
			.col
				b-form-group(label="Billing City")
					b-input(name="ba_city" v-validate="'required'" v-model="form.ba_city")
					form-error(:err="errors" name="ba_city")
			.w-100
			.col
				b-form-group(label="Billing Address")
					b-input(name="ba_address" v-validate="'required'" v-model="form.ba_address")
					form-error(:err="errors" name="ba_address")
			.col
				b-form-group(label="Billing Postalcode")
					b-input(name="ba_postalcode" v-validate="'required'" v-model="form.ba_postalcode")
					form-error(:err="errors" name="ba_postalcode")
			.w-100
		.form-footer
			button.btn.btn-primary(type="submit") Update Customer
</template>


<script>
import m_common from "Mixins/common";

export default {
    components: {},
    props: ["toEdit", "modalClose"],
    mixins: [m_common],
    data() {
        return {

            form: {
                email: "",
                first_name: "",
                last_name: "",
                genders:{},
                gender_val:"",
                company_name: "",
                cd_state: "",
                cd_city: "",
                cd_address: "",
                cd_postalcode: "",
				ba_state: "",
                ba_city: "",
                ba_address: "",
                ba_postalcode: ""
            }
        };
    },
    created() {},
    mounted() {
        this.formClone = this.jsonClone(this.form);
    },
    computed : {
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
                    url: `${_this.apiUrl()}/user/${_this.toEdit}?edit=true`,
                    success(data) {
                        _this.form = data;
                    }
                });
            }

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
            }
        }
    }
};
</script>

<style lang="scss">
@import "~ScssConfig";
</style>
