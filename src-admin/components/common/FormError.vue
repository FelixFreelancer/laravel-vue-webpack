<template lang="pug">
    span.form-error {{showError}}
</template>

<script>
export default {
	components: {},
	props: ["err", "name", "scope"],
	mixins: [],
	data() {
		return {};
	},
	created() {},
	mounted() {},
	methods: {},
	computed: {
		showError: {
			get() {
				if (this.err.has(this.name)) {
					if (this.scope) {
						return this.err.first(this.name, this.scope);
					} else {
						return this.err.first(this.name);
					}
				} else {
					return "";
				}
			}
		}
	}
};
</script>

<style lang="css">
</style>
