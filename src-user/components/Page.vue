<template lang="pug">
.page

    //Header
    .page__header.container-fluid

        //Back Page
        router-link.page__back(v-if="backLink" :to="backLink")
            i.material-icons keyboard_backspace

        //Page Details
        .page__details

            //Title
            .page__title
                slot(name="title")
                slot(name="quickAction")

            //Desc
            p.page__desc
                slot(name="desc")

        //Actions
        .page__actions
            slot(name="action")

    //Content
    .page__content
        .page__data
            slot(name="content")
</template>

<script>
export default {
	components: {},
	props: ["header", "backLink"],
	mixins: [],
	data() {
		return {};
	},
	created() {},
	mounted() {},
	methods: {}
};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";

$header_height: 45px;
$spacer: 20px;
.page {
	display: flex;
	min-height: 100%;
	flex-direction: column;
}
.page--grey {
	background: $grey-200;
	.page__header {
		border-bottom: none;
		box-shadow: 0 5px 10px rgba(0, 0, 0, 0.05);
	}
}
.page__header {
	background: #fff;
	height: $header_height;
	position: relative;
	z-index: 9;
	display: flex;
	align-items: center;
	border-bottom: 1px solid $grey-300;
}
.page__back {
	flex: 0 0 $header_height;
	height: $header_height;
	display: flex;
	justify-content: center;
	align-items: center;
	color: $grey-500;
	text-decoration: none !important;
	&:hover {
		color: $grey-700;
	}
}
.page__title {
	display: flex;
	align-items: center;
	h1,
	h2,
	h3,
	h4,
	h5,
	h6 {
		text-transform: uppercase;
		letter-spacing: 1px;
		font-size: 16px;
		margin: 0 0 0 10px;
		color: $grey-500;
	}
	.btn.btn-sm {
		background: $grey-300;
		margin-left: 10px;
		color: $grey-500;
		height: 26px;
		width: 26px;
		i {
			font-size: 18px;
		}
		&:hover {
			background: color("primary");
			color: #fff;
		}
	}
}
.page__details {
	flex: 1 1 auto;
}
.page__desc {
	margin: 0;
	font-size: 0.8rem;
	color: $grey-500;
	flex: 1 1 auto;
}
.page__content {
	display: flex;
	flex: 1 1 auto;
	overflow: auto;
}

.page__data {
	flex: 1 1 auto;
}

.page__row {
	margin-top: 15px;
	margin-bottom: 15px;
}
.page__card {
	background: #fff;
	border-radius: 7px;
	padding: 25px;
	box-shadow: 0 10px 20px 0 rgba(0, 0, 0, 0.1);
}

.page__actions {
	text-align: right;
	flex: 0 0 auto;
}

.nav--page {
	.nav-item {
		.nav-link {
			display: flex;
			align-items: center;
			color: $grey-500;
			height: $header_height;
			i {
				margin-right: 5px;
				font-size: 18px;
			}
			&:hover {
				color: $grey-800;
			}
			&.router-link-exact-active {
				background: transparent;
				position: relative;
				color: color("primary");
				&:after {
					content: "";
					position: absolute;
					left: 0;
					width: 100%;
					bottom: 0px;
					border-bottom: 3px solid color("primary");
				}
			}
		}
	}
}
</style>
