<template lang="pug">
ol.breadcrumb
    li.breadcrumb-item
        a(href='#') Home
        li.breadcrumb-item.active(aria-current='page') Maske
</template>

<script>
export default {};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";
.breadcrumb {
	background: transparent;
	padding: 0;
	margin: 0 0 0 1rem;
}
.breadcrumb-item {
	font-size: 0.8rem;
	a {
		color: $grey-500;
	}
}
</style>
