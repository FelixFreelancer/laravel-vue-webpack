<template lang="pug">
    .avtar
        slot(name="photo")
            img(src="~Images/avtar.svg")
</template>

<script>
export default {};
</script>

<style lang="scss">
.avtar {
	width: 100px;
	height: 100px;
	position: relative;
	img {
		width: 100%;
	}
}
.avtar--center {
	margin: 0 auto;
}
.avtar--round {
	border-radius: 50%;
	overflow: hidden;
}
.avtar--edit {
	&:hover {
		&:after {
			opacity: 1;
		}
	}
	&:after {
		content: "photo_camera";
		font-family: "Material Icons";
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background: rgba(0, 0, 0, 0.7);
		opacity: 0;
		font-size: 48px;
		color: #fff;
		display: flex;
		align-items: center;
		justify-content: center;
	}
}
</style>
