<template lang="pug">
.page__card
	form.form.add-item(id="addItem" @submit.prevent="addItem($event)")
		.row
			.col-7
				h3 Item Info
				.row
					.col
						input(type="hidden" name="shipment_id" :value="shipmentId")
						.form-group
							label Item Name
							input.form-control(
								type="text"
								v-validate="'required'"
								v-model="item_name"
								name="item_name")
							span(v-html="formError(errors,'item_name')")
					.col
						.row
							.col
								.form-group
									label Quantity
									input.form-control(
										type="text"
										v-validate="'required'"
										v-model="qty"
										name="qty")
									span(v-html="formError(errors,'qty')")
							.col
								.form-group
									label Amount
									input.form-control(
										type="text"
										v-validate="'required'"
										v-model="amount"
										name="amount")
									span(v-html="formError(errors,'amount')")
				.row
					.col
						.form-group
							label Description
							textarea.form-control(
								v-validate="''"
								v-model="desc"
								name="desc")
							span(v-html="formError(errors,'desc')")
				.row
					.col
						.form-group
							label Weight(kg)
							input.form-control(
								type="text"
								v-validate="'required'"
								v-model="weight"
								name="weight")
							span(v-html="formError(errors,'weight')")
					.col
						.form-group
							label Length(cm)
							input.form-control(
								type="text"
								v-validate="'required'"
								v-model="dimension_length"
								name="dimension_length")
							span(v-html="formError(errors,'dimension_length')")
					.col
						.form-group
							label Width(cm)
							input.form-control(
								type="text"
								v-validate="'required'"
								v-model="dimension_width"
								name="dimension_width")
							span(v-html="formError(errors,'dimension_width')")
					.col
						.form-group
							label Height(cm)
							input.form-control(
								type="text"
								v-validate="'required'"
								v-model="dimension_height"
								name="dimension_height")
							span(v-html="formError(errors,'dimension_height')")
				.row
					.col-4
						.form-group
							label Tracking Number
							input.form-control(
								type="text"
								v-validate="'required'"
								v-model="tracking_number"
								name="tracking_number")
							span(v-html="formError(errors,'tracking_number')")
			.col-5
				h3 Items Photos
				.image-upload.image-upload--gallery
					.image-upload__preview(
						v-for="(item,index) in this.viewImages"
						:style="`background-image:url(${item.image})`")
						.image-upload__close
							i.material-icons(title="Delete Image" @click="removeImage(item.id)") close
				.image-upload
					.image-upload__preview(
						v-for="(item,index) in this.images"
						:style="setPreview(index)")
						input(type="file" name="image[]" @change="previewImage" accept="image/*" :data-no="index")
				.form-footer.form-footer--has-margin
					button.btn.btn-primary(type="button" @click="addImage()") Add Image

		.form-footer(v-if="!itemId")
			button.btn.btn-primary(type="submit") Add Item
		.form-footer(v-if="itemId")
			button.btn.btn-primary(type="submit") Update Item
		alert(:title="title" :id="imageId" :cancel="cancel" :ok="ok")
			p(slot="desc") Are you sure you want to delete this?
</template>
<script>
import c_page from "Components/Page.vue";
import m_common from "Mixins/common";
import DatePicker from "vue2-datepicker";
import alert from "Components/common/Alert.vue";

export default {
    components: {
        c_page,
        alert,
        DatePicker
    },
    props: ["shipmentId", "itemId"],
    mixins: [m_common],
    data() {
        var _this = this;
        return {
            title: "Image",
			imageId: false,
            dimension_height: "",
            dimension_width: "",
            dimension_length: "",
            tracking_number: "",
            desc: "",
            amount: "",
            qty: "",
            item_name: "",
            weight: "",
            images: [{
                base64: false
            }],
            viewImages: []
        };
    },
    created() {},
    mounted() {
        if (this.itemId) {
            this.getItem();
        }
    },
    computed: {},
    methods: {
        previewImage(event) {
            var input = event.target;
            var _this = this;
            var imageNo = input.getAttribute("data-no");
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = e => {
                    this.imageData = e.target.result;
                    _this.images[imageNo].base64 = this.imageData;
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                _this.images[imageNo].base64 = false;
            }
        },
        setPreview(index) {
            return this.images[index].base64 ? `background-image:url(${this.images[index].base64})` : "";
        },
        addImage() {
            this.images.push({
                base64: false
            });
        },
        removeImage(id) {
            this.imageId = id;
            this.$root.$emit("bv::show::modal", "alertModal");
        },
        cancel() {},
        ok(id) {
            var _this = this;
            this.apiPost({
                url: `${this.apiUrl()}/medias/${id}`,
                validator: _this.$validator,
                method: 'delete',
                msg: {
                    success: `Image removed from ${this.item_name}`
                },
                success(data) {
                    _this.getItem();
                }
            });
        },
        getItem() {
            var _this = this;
            this.apiGet({
                url: `${_this.apiUrl()}/shipment/${this.shipmentId}/shipment-items/${_this.itemId}`,
                success(data) {
                    _this.dimension_height = data.dimension_height;
                    _this.dimension_width = data.dimension_width;
                    _this.dimension_length = data.dimension_length;
                    _this.tracking_number = data.tracking_number;
                    _this.weight = data.weight;
                    _this.desc = data.desc;
                    _this.amount = data.amount;
                    _this.qty = data.qty;
                    _this.item_name = data.item_name;
                    _this.viewImages = data.medias;
                }
            });
        },
        addItem(event) {
						//console.log(event);

            var _this = this;
							//console.log(_this.itemId);
            var formId = event.target.id;
            if (!_this.itemId) {

                _this.formSubmit({
                    url: `${_this.apiUrl()}/shipment/${_this.shipmentId}/shipment-items`,
                    validator: _this.$validator,
                    formId: formId,
										method:"post",
                    msg: {
                        success: "Item Added Successfully"
                    },
                    success(data) {
											//console.log(data);
                        _this.dimension_height = "";
                        _this.dimension_width = "";
                        _this.dimension_length = "";
                        _this.tracking_number = "";
                        _this.desc = "";
                        _this.amount = "";
                        _this.qty = "";
                        _this.item_name = "";
                        _this.weight = "";
                        _this.images = [{
                            base64: false
                        }];
                        _this.$nextTick(() => {
                            _this.$validator.reset();
                        });
                    }
                });
            } else {
                _this.formSubmit({
                    url: `${_this.apiUrl()}/shipment/${_this.shipmentId}/shipment-items/${_this.itemId}`,
                    validator: _this.$validator,
                    formId: formId,
                    msg: {
                        success: "Item Updated"
                    },
                    success(data) {
                        _this.images = [{
                            base64: false
                        }];
                        _this.getItem();
                    }
                });
            }
        }
    }
};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";
.add-item {
    .v-select .dropdown-toggle {
        white-space: nowrap;
    }
    .mx-datepicker {
        width: 100% !important;
    }
}
.image-upload {
    display: flex;
    flex-wrap: wrap;
}
.image-upload--gallery {
    padding-bottom: 20px;
    margin-bottom: 20px;
    .image-upload__preview {
        border-style: solid;
        &:before {
            display: none;
        }
    }
}
.image-upload__preview {
    width: 120px;
    height: 120px;
    position: relative;
    background-color: $grey-200;
    margin: 2px;
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    border: 2px dashed $grey-300;
    &:before {
        content: "add_a_photo";
        font-family: "Material Icons";
        display: flex;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        justify-content: center;
        align-items: center;
        font-size: 48px;
        color: rgba(0, 0, 0, 0.2);
    }
    input {
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        opacity: 0;
        z-index: 2;
    }
}
.image-upload__close {
    width: 13px;
    height: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    background: red;
    border-radius: 50%;
    position: absolute;
    bottom: 2px;
    right: 2px;
    cursor: pointer;
}
.image-upload__close i {
    font-size: 10px;
    font-weight: 800;
}
</style>
