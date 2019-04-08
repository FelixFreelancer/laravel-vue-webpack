<template lang="pug">
//📣 : Page Template
c_page.warehouse.page--grey
    h3(slot="title") Warehouse Address
    .customer__container(slot='content')
        .page__row
            .container-fluid
                .row
                    .col-sm-4.offset-sm-4
                        .page__card
                            form.form(id="warehouseAddress" @submit.prevent="updateWarehouse($event)")
                                .form-group
                                    label Address Line 1
                                    input(
                                    type="text"
                                    v-validate="'required'"
                                    class="form-control"
                                    name="uk_warehouse_address_line_1"
                                    v-model="uk_warehouse_address_line_1")
                                    span(v-html="formError(errors,'uk_warehouse_address_line_1')")
                                .form-group
                                    label Address Line 2
                                    input(
                                    type="text"
                                    v-validate="'required'"
                                    class="form-control"
                                    v-model="uk_warehouse_address_line_2"
                                    name="uk_warehouse_address_line_2")
                                    span(v-html="formError(errors,'uk_warehouse_address_line_2')")
                                .form-group
                                    label Country
                                    input(
                                    type="text"
                                    v-validate="'required'"
                                    class="form-control"
                                    v-model="uk_warehouse_country"
                                    name="uk_warehouse_country")
                                    span(v-html="formError(errors,'uk_warehouse_country')")
                                .form-footer
                                    button.btn.btn-primary(type="submit") Update
</template>

<script>
import c_page from "Components/Page.vue";
import m_common from "Mixins/common";

export default {
	components: { c_page },
	props: [],
	mixins: [m_common],
	data() {
		return {
			uk_warehouse_address_line_1: "",
			uk_warehouse_address_line_2: "",
			uk_warehouse_country: ""
		};
	},
	created() {},
	mounted() {
        this.getAddress();
    },
	methods: {
        getAddress(){
            var _this = this;
            _this.apiGet({
                url:`${_this.apiUrl()}/uk-warehouse-address`,
                success(data){
                    _this.uk_warehouse_address_line_1 = data.uk_warehouse_address_line_1;
                    _this.uk_warehouse_address_line_2 = data.uk_warehouse_address_line_2;
                    _this.uk_warehouse_country = data.uk_warehouse_country;
                }
            })
        },
		updateWarehouse(event) {
			var _this = this;
			_this.formSubmit({
				formId: event.target.id,
				url: `${_this.apiUrl()}/uk-warehouse-address/`,
				validator: _this.$validator,
				msg: {
					success: "Address Updated Successfully"
				},
				success(data) {}
			});
		}
	},
	computed: {}
};
</script>

<style lang="scss">
.avtar--setting {
	position: relative;
}
</style>
