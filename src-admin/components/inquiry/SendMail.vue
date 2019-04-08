<template lang="pug">
b-modal(
	id="sendMailModal"
	title="Send an email"
	hide-footer)
	form.form(id="sendMailForm" @submit.prevent="sendMail($event)")
		c_userCard.d-flex.mb-3
			b(slot="name") {{customerDetails.name}}
			span(slot="attr") Sending Email To: {{customerDetails.email}}

		.form-group
			label Subject
			input.form-control(type="text" name="subject" v-model="subject" v-validate="'required'")
			form-error(:err="errors" name="subject")
		.form-group
			label Email Content
			textarea.form-control(rows="10" name="mail" v-model="description" v-validate="'required'")
			form-error(:err="errors" name="mail")
			input(type="hidden" name="name" :value="customerDetails.name")
			input(type="hidden" name="id" :value="customerDetails.id")
			input(type="hidden" name="email" :value="customerDetails.email")
		.form-footer
			button.btn.btn-primary(type="submit") Send Email
</template>

<script>
import m_common from "Mixins/common";
import c_userCard from "Components/common/UserCard.vue";

export default {
    components: {
        c_userCard
    },
    props: ["customerDetails", "modalClose"],
    mixins: [m_common],
    data() {
        return {
            subject: '',
            description: ''
        };
    },
    created() {},
    mounted() {
    },
    methods: {
        sendMail(event) {
            var _this = this;
            _this.formSubmit({
                url: `${_this.apiUrl()}/inquiry-response`,
                formId: 'sendMailForm',
                validator: _this.$validator,
                msg: {
                    success: "Email sent successfully."
                },
                success(data) {
					_this.modalClose();
					_this.subject = '';
					_this.description = '';
                }
            });
        }
    }
};
</script>

<style lang="scss">
@import "~ScssConfig";
</style>
