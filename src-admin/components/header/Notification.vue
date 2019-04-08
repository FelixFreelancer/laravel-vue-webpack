<template lang="pug">
    b-dropdown.user-notifications(right variant="default")
        template.btn--icon(slot="button-content")
            i.material-icons notifications
        //- b-dropdown-item(v-for="n in 10")
        //-     .notify
        //-         .notify__image
        //-             img(src="~Images/avtar.svg")
        //-         .notify__desc
        //-             p.notify__text Lorem ipsum dolor sit amet, consectetur adipisicing elit.
        //-             p.notify__date 12th Jan 2018
</template>

<script>
export default {};
</script>

<style lang="scss">
@import "~ScssConfig";
.user-notifications {
	.dropdown-menu {
		border: none;
		width: 400px;
		max-height: 500px;
		overflow: auto;
		margin-top: 4px;
		padding: 0;
		box-shadow: 0 0px 40px 0 rgba(0, 0, 0, 0.2);
	}
	.dropdown-item {
		outline: 0;
		padding: 0;
		&:hover,
		&:focus {
			background: $grey-100;
		}
	}
	button {
		transition: none;
	}
	&.show {
		button {
			border-radius: 50%;
			background: $grey-200;
			color: #000;
		}
	}
}
.notify {
	display: flex;
	padding: 10px 15px;
	white-space: normal;
}
.notify__image {
	flex: 0 0 50px;
	height: 50px;
	margin-right: 10px;
	img {
		width: 100%;
	}
}
.notify__desc {
	flex: 1 1 auto;
}
.notify__text {
	font-size: 13px;
	margin: 0;
}
.notify__date {
	margin: 3px 0 0 0;
	font-size: 10px;
	color: $grey-500;
}
</style>
