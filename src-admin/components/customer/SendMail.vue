<template lang="pug">
b-modal(
	id="sendMailModal"
	title="Send an email"
	hide-footer)
	form.form(id="sendMailForm" @submit.prevent="sendMail")
		input(type="hidden" name="user_id" :value="customerDetails.id")
		c_userCard.d-flex.mb-3
			b(slot="name") {{customerDetails.first_name+' '+customerDetails.last_name}}
			span(slot="attr") Sending Email To: {{customerDetails.email}}
		.form-group
			label Type
			v-select(
				label="text"
				:options="types"
				v-model="selectedType"
				name="order")
		.form-group
			label Subject
			input.form-control(type="text" name="subject" v-model="subject" v-validate="'required'")
			form-error(:err="errors" name="subject")
		.form-group
			label Email Content
			textarea.form-control(rows="10" name="mail" v-model="description" v-validate="'required'")
			form-error(:err="errors" name="mail")
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
            types: [],
            selectedType: ''
        };
    },
    created() {},
    mounted() {
        this.getTypes();
    },
	computed:{
		description:{
			get(){
				return this.selectedType ? this.selectedType.description : '';
			},
			set(){}
		},
		subject: {
			get(){
				return this.selectedType ? this.selectedType.subject : '';
			},
			set(){}
		}
	},
    methods: {
        getTypes() {
            var _this = this;
            _this.apiGet({
                url: `${_this.apiUrl()}/mail-contents/`,
                success(data) {
                    _this.types = data;
                }
            });
        },
        sendMail(event) {
            var _this = this;
            _this.formSubmit({
                url: `${_this.apiUrl()}/mail`,
                formId: event.target.id,
                validator: _this.$validator,
                msg: {
                    success: "Email sent successfully."
                },
                success(data) {
					_this.modalClose();
					_this.subject = '';
					_this.selectedType = '';
                }
            });
        }
    }
};
</script>

<style lang="scss">
@import "~ScssConfig";
</style>
