<template lang="pug">
    .not-found
        .not-found__block
            img(src="~Images/404.svg")
            h1.not-found__title Oops! Page Not Found.
            router-link(to="/app" class="btn btn-primary") Go To Home Page
</template>

<script>
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";
.not-found {
	display: flex;
	height: 100vh;
	justify-content: center;
	align-items: center;
}
.not-found__block {
	max-width: 400px;
	text-align: center;
	img {
		width: 100px;
		margin-bottom: 10px;
	}
}
</style>
