<template lang="pug">
.page__card
	form.form.add-shipment(id="addShipment" @submit.prevent="addShipment($event)")
		.row
			.col-7
				h3 Shipment Info
				.row
					.col
						.form-group
							label User
							v-select(
								label="name"
								@search="onSearch"
								:options="users"
								v-model="selectedUser"
								name="user"
								v-validate:selectedUser="'required'")
								template(slot="no-options") Type To Search Users
							input(type="hidden" v-model="selectedUserValue" name="user_id")
							span(v-html="formError(errors,'user')")
					.col
						.form-group
							label Name
							input.form-control(
								type="text"
								v-validate="'required'"
								data-vv-as="Name"
								v-model="name"
								name="name")
							span(v-html="formError(errors,'name')")
				.row
					.col
						.form-group
							label Parcel Number
							input.form-control(
								type="text"
								v-validate="'required'"
								data-vv-as="Parcel Number"
								v-model="parcel_number"
								name="parcel_number")
							span(v-html="formError(errors,'parcel_number')")
					.col
						.form-group
							label Received On
							div
								DatePicker(
									v-validate="'required'"
									type='datetime'
									format="DD-MM-yyyy HH:mm:ss"
									v-model="receivedOn"
									data-vv-as="Received On"
									input-class="form-control"
									name="received_on_default"
									:lang="datepicker.lang"
									:not-after="new Date()"
									:time-picker-options="datepicker.time")
							input(
								type="hidden"
								v-validate="'required'"
								name="received_on"
								v-model="receivedOnServer")
							span(v-html="formError(errors,'received_on_default')")
				.row
					.col
						.form-group
							label Description
							textarea.form-control(
								v-validate="''"
								v-model="parcel_desc"
								name="parcel_desc")
							span(v-html="formError(errors,'parcel_desc')")
				.row
					.col
						.form-group
							label Weight(kg)
							input.form-control(
								type="text"
								v-validate="'required'"
								data-vv-as="Parcel Weight"
								v-model="parcel_weight"
								name="parcel_weight")
							span(v-html="formError(errors,'parcel_weight')")
					.col
						.form-group
							label Length(cm)
							input.form-control(
								type="text"
								v-validate="'required'"
								v-model="dimension_length"
								data-vv-as="Length"
								name="dimension_length")
							span(v-html="formError(errors,'dimension_length')")
					.col
						.form-group
							label Width(cm)
							input.form-control(
								type="text"
								v-validate="'required'"
								v-model="dimension_width"
								data-vv-as="Width"
								name="dimension_width")
							span(v-html="formError(errors,'dimension_width')")
					.col
						.form-group
							label Height(cm)
							input.form-control(
								type="text"
								v-validate="'required'"
								data-vv-as="Height"
								v-model="dimension_height"
								name="dimension_height")
							span(v-html="formError(errors,'dimension_height')")
				.row
					.col
						.form-group
							label Postal Company
							input.form-control(
								type="text"
								v-validate="'required'"
								data-vv-as="Postal Company"
								v-model="postal_company"
								name="postal_company")
							span(v-html="formError(errors,'postal_company')")
							input(type="hidden" v-model="shipping_option_available" name="shipping_option_available")

					.col(v-if="!shipping_option_available || custom_shipping_price !== null")
						.form-group
							label Custom Shipping Price
							input.form-control(
								type="text"
								data-vv-as="Shipping Price"
								v-model="custom_shipping_price"
								name="custom_shipping_price"
								:disabled="status != '' && status !=1 ? true : false")
							span(v-html="formError(errors,'custom_shipping_price')")
			.col-5
				h3 Shipment Images
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
		.form-footer(v-if="!shipmentId")
			button.btn.btn-primary(type="submit") Create Shipment
		.form-footer(v-if="shipmentId")
			button.btn.btn-primary(type="submit") Update Shipment
		alert(:title="title" :id="imageId" :cancel="cancel" :ok="ok")
			p(slot="desc") Are you sure you want to delete this?
</template>
<script>
import c_page from "Components/Page.vue";
import m_common from "Mixins/common";
import DatePicker from "vue2-datepicker";
import moment from "moment";
import alert from "Components/common/Alert.vue";

export default {
    components: {
        c_page,
        alert,
        DatePicker
    },
    props: ["shipmentId"],
    mixins: [m_common],
    data() {
        var _this = this;
        return {
            title: "Image",
            shipping_option_available: true,
            imageId: false,
            selectedUser: null,
            users: [],
            receivedOn: "",
            postal_company: "",
            custom_shipping_price: null,
            dimension_height: "",
            dimension_width: "",
            dimension_length: "",
            parcel_weight: "",
            parcel_desc: "",
            parcel_number: "",
			status : "",
            name: "",
            images: [{
                base64: false
            }],
            viewImages: [],
            datepicker: {
                lang: {
                    days: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                    months: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    pickers: ["next 7 days", "next 30 days", "previous 7 days", "previous 30 days"],
                    placeholder: {
                        date: "Select Date",
                        dateRange: "Select Date Range"
                    }
                },
                time: {
                    start: "00:00",
                    step: "00:15",
                    end: "23:50"
                }
            }
        };
    },
    created() {},
    mounted() {
        if (this.shipmentId) {
            this.getShipment();
        }
    },
    computed: {
        receivedOnServer() {
            return this.receivedOn ? this.formatDateServer(this.receivedOn) : "";
        },
        selectedUserValue() {
            return this.selectedUser ? this.selectedUser.id : "";
        }
    },
    methods: {
        onSearch(search, loading) {
            loading(true);
            this.search(loading, search, this);
        },
        search(loading, search, vm) {
            var _this = this;
            this.apiGet({
                url:`${_this.apiUrl()}/ajax/user?q=${escape(search)}`,
                success(data) {
                    _this.users = data;
                    loading(false);
                }
            });
        },
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
                    success: `Image removed from ${this.name}`
                },
                success(data) {
                    _this.getShipment();
                }
            });
        },
        addShipment(event) {
            var _this = this;
            var formId = event.target.id;
			_this.formSubmit({
				url: `${_this.apiUrl()}/shippingOptions`,
				validator: _this.$validator,
				formId: formId,
				success(data) {
					_this.shipping_option_available = data.is_option_available;
					if(!data.is_option_available && !_this.custom_shipping_price){
						_this.$notify({
							group: "app",
							title: "Currently we dont have any shipping options for this Country. Please add the custom shipping price.",
							type: "error"
						});
					}
					if(data.is_option_available){
						_this.custom_shipping_price = null;
					}
					if(data.is_option_available || _this.custom_shipping_price){
						if (!_this.shipmentId) {
							_this.formSubmit({
								url: `${_this.apiUrl()}/shipment`,
								validator: _this.$validator,
								formId: formId,
								success(data) {
									_this.selectedUser = null;
									_this.receivedOn = "";
									_this.postal_company = "";
									_this.dimension_height = "";
									_this.dimension_width = "";
									_this.dimension_length = "";
									_this.parcel_weight = "";
									_this.parcel_desc = "";
									_this.parcel_number = "";
									_this.name = "";
									_this.custom_shipping_price = "";
									_this.images = [{
										base64: false
									}];
									_this.$nextTick(() => {
										_this.$validator.reset();
									});
								}
							});
						}
						else {
							_this.formSubmit({
								url: `${_this.apiUrl()}/shipment/${_this.shipmentId}`,
								validator: _this.$validator,
								formId: formId,
								msg: {
									success: "Shipment Updated"
								},
								success(data) {
									_this.getShipment();
									_this.images = [{
										base64: false
									}];
								}
							});
						}
					}
				}
			});
        },
        getShipment() {
            var _this = this;
            this.apiGet({
                url: `${this.apiUrl()}/shipment/${this.shipmentId}`,
                success(data) {
                    _this.name = data.name;
                    _this.parcel_number = data.parcel_number;
                    _this.parcel_desc = data.parcel_desc;
                    _this.parcel_weight = data.parcel_weight;
                    _this.dimension_length = data.dimension_length;
                    _this.dimension_width = data.dimension_width;
                    _this.custom_shipping_price = data.custom_shipping_price;
                    _this.dimension_height = data.dimension_height;
                    _this.postal_company = data.postal_company;
					_this.status = data.status;
                    _this.receivedOn = moment(data.received_on, "DD-MM-yyyy HH:mm:ss").toDate();
                    _this.selectedUser = {
                        id: data.user_id,
                        name: data.user_name
                    };
                    _this.viewImages = data.medias;
                }
            });
        }
    }
};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";

.add-shipment {
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
