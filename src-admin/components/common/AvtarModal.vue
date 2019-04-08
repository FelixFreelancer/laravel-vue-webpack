<template lang="pug">
b-modal(id="avtarModal" title="Change Photo" hide-footer)
    .avtar-set
        .avtar-set__croppa
            croppa(
                v-model="avtar"
                @new-image-drawn="onNewImage"
                @zoom="onZoom"
                :width="200"
                :height="200"
                :quality="3"
                :show-remove-button="false"
                :initial-image="initialImage"
                :accept="'image/*'")
        .avtar-set__zoom
            input(
                type="range"
                @input="onSliderChange"
                :min="sliderMin"
                :max="sliderMax"
                step=".001"
                v-model="sliderVal")
    .form-footer.justify-content-center.form-footer--has-margin
        button.btn.btn-primary(type="button" @click="submitPhoto") Update
        button.btn.btn-icon.btn-danger.btn--icon.btn--round(type="button" @click="remove"): i.material-icons delete
</template>

<script>
import m_common from "Mixins/common";

export default {
	components: {},
	props: [],
	mixins: [m_common],
	data() {
		return {
			avtar: {},
			sliderVal: 0,
			sliderMin: 0,
			sliderMax: 0
		};
	},
	created() {},
	mounted() {},
  computed: {
    initialImage: {
			get() {
        return this.$store.getters.user.image_name != '' ? this.$store.getters.user.image_name : 'http://localhost:8080/img/crop.jpg';
			},
			set(value) {}
		},
  },
	methods: {
		onNewImage() {
			this.sliderVal = this.avtar.scaleRatio;
			this.sliderMin = this.avtar.scaleRatio;
			this.sliderMax = this.avtar.scaleRatio * 2;
		},
		onSliderChange(evt) {
			var increment = evt.target.value;
			this.avtar.scaleRatio = +increment;
		},

		onZoom() {
			// To prevent zooming out of range when using scrolling to zoom
			// if (this.sliderMax && this.croppa.scaleRatio >= this.sliderMax) {
			//   this.croppa.scaleRatio = this.sliderMax
			// } else if (this.sliderMin && this.croppa.scaleRatio <= this.sliderMin) {
			//   this.croppa.scaleRatio = this.sliderMin
			// }
			this.sliderVal = this.avtar.scaleRatio;
		},

		remove() {
			this.avtar.remove();
			this.sliderVal = 0;
			this.sliderMin = 0;
			this.sliderMax = 0;
		},
		submitPhoto() {
      var _this = this;
			var image = this.avtar.generateDataUrl();
      _this.apiPost({
				url: `${_this.apiUrl()}/upload-profile-pic`,
				data: {
					image: image
				},
				success(data) {
	      			_this.$store.dispatch("setUser", data).then(() => {
	    				_this.$root.$emit("bv::hide::modal", "avtarModal");
	    			});
        		}
			});
		}
	}
};
</script>

<style lang="scss">
@import "~ScssConfig";
.avtar-set {
	text-align: center;
}
.avtar-set__croppa {
	border: 5px solid $grey-200;
	display: inline-flex;
}
.avtar-set__zoom {
	margin-top: 10px;
}
</style>
