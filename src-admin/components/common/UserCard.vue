<template lang="pug">
    .user-card
        .user-card__photo
            slot(name="photo")
                img(src="~Images/avtar.svg")
        .user-card__details
            p.user-card__name
                slot(name="name") John Junior Doe
            p.user-card__attr
                slot(name="attr") UI Designer
</template>

<script>
export default {};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";

.user-card-link {
	text-decoration: none !important;
	color: #000 !important;
}
.user-card {
	display: inline-flex;
	align-items: center;
	border: 1px solid #dadada;
	padding: 15px;
	border-radius: 5px;
}
.user-card__photo {
	flex: 0 0 50px;
	height: 50px;
	width: 50px;
	border-radius: 50%;
	background: rgba(0, 0, 0, 0.5);
	overflow: hidden;
	margin-right: 10px;
	img {
		width: 100%;
	}
}
.user-card__details {
	flex: 1 1 auto;
}
.user-card__name {
	margin: 0;
	line-height: 1;
	font-size: 16px;
}
.user-card__attr {
	margin: 3px 0 0 0;
	line-height: 1;
	font-size: 12px;
	color: rgba(0, 0, 0, 0.5);
}

.user-card--large {
	border: none;
	border-radius: 0;
}
</style>
