<template lang="pug">
    .dmenus__link(:id="link" @click="callBackFunc($event)")
        .dmenu__icon
            slot(name="icon")
                img(:src="imagePath")
        span {{title}}
        .dmenu__arrow
</template>

<script>
export default {
	components: {},
	props: ["link", "title", "image", "callBack"],
	mixins: [],
	data() {
		return {};
	},
	created() {},
	mounted() {},
	methods: {
		callBackFunc(event) {
			var active = document.querySelector(".active");
			if (active) {
				active.classList.remove("active");
			}

			document.getElementById(this.link).classList.add("active");
			this.callBack(this.link, this.title, this.image);
		}
	},
	computed: {
		imagePath() {
			return require("Images/" + this.image);
		}
	}
};
</script>

<style lang="css">
</style>
