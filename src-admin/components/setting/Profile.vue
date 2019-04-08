<template lang="pug">
	section
		.row
			.col-sm-4.offset-sm-4
				.page__card
					a(href="javascript:;" @click="changePhoto()")
						c_avtar.avtar--center.avtar--round.avtar--edit
							span(slot="photo" v-if="this.$store.getters.user.image_name")
								img(:src="this.$store.getters.user.image_name")
					form.form(id="updateProfile" @submit.prevent="updateProfile($event)")
						.form-group
							label First Name
							input(
								type="text"
								v-validate="'required'"
								class="form-control"
								name="first_name"
								v-model="user.first_name")
							span(v-html="formError(errors,'first_name')")
						.form-group
							label Last Name
							input(
								type="text"
								v-validate="'required'"
								class="form-control"
								v-model="user.last_name"
								name="last_name")
							span(v-html="formError(errors,'last_name')")
						.form-group
							label Email
							input(
								readonly
								type="text"
								v-validate="'required|email'"
								class="form-control"
								v-model="user.email"
								name="email")
							span(v-html="formError(errors,'email')")
						.form-footer
							button.btn.btn-primary(type="submit") Update
		c_avtarModal()
</template>

<script>
import c_page from "Components/Page.vue";
import c_avtarModal from "Components/common/AvtarModal.vue";
import m_common from "Mixins/common";
import c_avtar from "Components/common/Avtar.vue";

export default {
	components: {
		c_page,
		c_avtarModal,
		c_avtar
	},
	props: [],
	mixins: [m_common],
	data() {
		return {
			myCroppa: "",
			user: this.jsonClone(this.$store.getters.user),
		};
	},
	created() {},
	mounted() {},
	methods: {
		changePhoto() {
			this.$root.$emit("bv::show::modal", "avtarModal");
		},
		updateProfile(event) {
	      var _this = this;
		  _this.formSubmit({
			  formId: event.target.id,
			  validator: _this.$validator,
			  url: `${_this.apiUrl()}/user/${this.$store.getters.user.id}`,
			  msg: {
				  success: "Profile Updated Successfully"
			  },
			  success(data) {
				  _this.$store.dispatch("setUser", data).then(() => {});
			  }
		  });
	    }

	}
};
</script>

<style lang="scss">
.avtar--setting {
	position: relative;
}
</style>
