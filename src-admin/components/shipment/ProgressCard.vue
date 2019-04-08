<template lang="pug">
    .ship(:class="extraClass")
        .ship__header
            h3.ship__count {{no}}
            h3.ship__title {{title}}
        .ship__info
            .ship__attrs
                slot(name="attr")
            slot(name="info")
</template>

<script>
export default {
	components: {},
	props: ["extraClass", "no", "title"],
	mixins: [],
	data() {
		return {};
	},
	created() {},
	mounted() {},
	methods: {}
};
</script>

<style lang="css">
</style>
