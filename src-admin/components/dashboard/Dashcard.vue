<template lang="pug">
    //📣 : Dash Card
    .dashcard(:class='extraClass' v-if="cardData")
        .dashcard__header
            .dashcard__data
                p.dashcard__title {{cardData.title}}
                p.dashcard__value {{cardData.value}}
            .dashcard__icon
                i.material-icons {{cardData.icon}}
        //- .dashcard__overview(v-if="cardData.overview")
        //-     table
        //-         tr(v-for="item in cardData.overview")
        //-             td
        //-                 span {{item.title}}
        //-                 span.dashcard__per(v-if="item.per") {{item.per}}
        //-             td.text-right
        //-                 span.dashcard__val {{item.value}}
</template>

<script>
export default {
	components: {},
	props: ["extraClass", "cardData"],
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
.dashcard {
	border-radius: 5px;
	overflow: hidden;
	box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
	display: flex;
	flex-direction: column;
	background: #fff;
}
.dashcard__header {
	position: relative;
	display: flex;
	overflow: hidden;
}
.dashcard__icon {
	flex: 0 0 auto;
	position: absolute;
	right: 0;
	top: -20px;
	i {
		color: rgba(0, 0, 0, 0.1);
		font-size: 100px;
	}
}
.dashcard__data {
	flex: 1 1 auto;
	width: 100%;
	padding: 15px 20px;
	position: relative;
	z-index: 2;
}
.dashcard__value {
	margin: 0;
	color: #fff;
	font-size: 32px;
	font-weight: 700;
	line-height: 32px;
}
.dashcard__title {
	color: #fff;
	font-size: 14px;
	margin: 0;
	text-transform: uppercase;
	letter-spacing: 2px;
}
.dashcard__per {
	color: $grey-500;
	margin-left: 5px;
	font-size: 12px;
	background: #eaeaea;
	padding: 2px 5px;
	border-radius: 10px;
}
.dashcard__val {
	font-weight: bold;
}
.dashcard__overview {
	background: #fff;
	padding: 20px;
	table {
		width: 100%;
		td {
			padding: 2px 0;
		}
	}
}
.dashcard--red {
	box-shadow: 0 20px 30px 0 transparentize(#eb3b5a, 0.8);
	.dashcard__header {
		background: linear-gradient(to right, #eb3b5a 0%, #fa8231 100%);
	}
}
.dashcard--blue {
	box-shadow: 0 20px 30px 0 transparentize(#3867d6, 0.8);
	.dashcard__header {
		background: linear-gradient(to right, #3867d6 0%, #2d98da 100%);
	}
}
.dashcard--green {
	box-shadow: 0 20px 30px 0 transparentize(#20bf6b, 0.8);
	.dashcard__header {
		background: linear-gradient(to right, #20bf6b 0%, #0fb9b1 100%);
	}
}
.dashcard--purple {
	box-shadow: 0 20px 30px 0 transparentize(#8854d0, 0.8);
	.dashcard__header {
		background: linear-gradient(to right, #8854d0 0%, #a55eea 100%);
	}
}
</style>
