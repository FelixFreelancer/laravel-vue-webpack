<template lang="pug">
b-modal(id="editCountryModal"
	:title="toEdit?'Edit Country':'Add Country'"
	@shown="shown"
	@hidden="hidden"
	 hide-footer)
	form.form(id="editCountry" @submit.prevent="upsert($event)")
		.form-group
			label Country Name
			input.form-control(
				type='text'
				name="name"
				v-validate="'required'"
				v-model="formData.name")
			span(v-show="errors.has('name')" class="form-error") {{ errors.first('name') }}
		.form-group
			label Country Nice Name
			input.form-control(
				type='text'
				name="nice_name"
				v-validate="'required'"
				v-model="formData.nice_name")
			span(v-show="errors.has('nice_name')" class="form-error") {{ errors.first('nice_name') }}
		.form-group
			label Short Code
			input.form-control(
				type='text'
				name="short_code"
				v-validate="'required'"
				v-model="formData.short_code")
			span(v-show="errors.has('short_code')" class="form-error") {{ errors.first('short_code') }}
		.form-group
			label Country Code
			input.form-control(
				type='text'
				name="country_code"
				v-validate="'required'"
				v-model="formData.country_code")
			span(v-show="errors.has('country_code')" class="form-error") {{ errors.first('country_code') }}
		.form-group
			label Suite Number
			input.form-control(
				type='text'
				name="suite_number"
				v-validate="'required'"
				v-model="formData.suite_number")
			span(v-show="errors.has('suite_number')" class="form-error") {{ errors.first('suite_number') }}
		.form-group
			label ISO
			input.form-control(
				type='text'
				name="iso"
				v-validate="'required'"
				v-model="formData.iso")
			span(v-show="errors.has('iso')" class="form-error") {{ errors.first('iso') }}
		.form-footer
			button.btn.btn-primary(type="submit") {{ toEdit?'Update Country':'Add Country' }}
</template>


<script>
import m_common from "Mixins/common";

export default {
	components: {},
	props: ["toEdit", "modalClose"],
	mixins: [m_common],
	data() {
		return {
			formData: {
                iso: "",
                short_code: "",
                suite_number: "",
                country_code: "",
                nice_name: "",
                name: ""
            }
		};
	},
	created() {},
	mounted() {},
	methods: {
		hidden() {
            this.formData.iso = '';
            this.formData.short_code = '';
            this.formData.suite_number = '';
            this.formData.country_code = '';
            this.formData.nice_name = '';
            this.formData.name = '';
            this.$validator.reset();
            this.modalClose();
        },
        shown() {
            var _this = this;
            if (_this.toEdit) {
                _this.apiGet({
                    url: `${_this.apiUrl()}/country/${_this.toEdit}`,
                    success(data) {
                        _this.formData = data;
                    }
                });
            }
        },
		upsert(event) {
            var _this = this;
            if (_this.toEdit) {
				_this.formSubmit({
					url: `${_this.apiUrl()}/country/${_this.toEdit}`,
					formId: event.target.id,
					validator: _this.$validator,
					msg: {
						success: "Country Updated Successfully"
					},
					success(data) {
						_this.hidden();
					}
				});
            } else {
                _this.formSubmit({
                    url: `${_this.apiUrl()}/country`,
                    formId: event.target.id,
                    validator: _this.$validator,
                    msg: {
                        success: "Country Added Successfully"
                    },
                    success(data) {
                        _this.hidden();
                    }
                });
            }
		}
	}
};
</script>

<style lang="scss" scoped>
@import "~ScssConfig";
</style>
