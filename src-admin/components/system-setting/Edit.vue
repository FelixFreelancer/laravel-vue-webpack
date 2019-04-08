<template lang="pug">
b-modal(id="editSystemSettingModal"
	title="Edit System Setting"
	 hide-footer)
	form.form(id="editSystemSetting" @submit.prevent="upsert($event)")
		.form-group
			label {{ toEdit.name }}
			input.form-control(
				type='text'
				name="value"
				v-validate="'required'"
				v-model="toEdit.value")
			span(v-show="errors.has('value')" class="form-error") {{ errors.first('value') }}
		.form-footer
			button.btn.btn-primary(type="submit") Update System Setting
</template>


<script>
import m_common from "Mixins/common";

export default {
	components: {},
	props: ["toEdit", "modalClose"],
	mixins: [m_common],
	data() {
		return {

		};
	},
	created() {},
	mounted() {},
	methods: {
		hidden() {
            this.$validator.reset();
            this.modalClose();
        },
		upsert(event) {
            var _this = this;
			_this.formSubmit({
				url: `${_this.apiUrl()}/settings/${_this.toEdit.id}`,
				formId: event.target.id,
				validator: _this.$validator,
				msg: {
					success: "Value Of "+_this.toEdit.name+" Updated Successfully"
				},
				success(data) {
					_this.hidden();
				}
			});
		}
	}
};
</script>

<style lang="scss" scoped>
@import "~ScssConfig";
</style>
