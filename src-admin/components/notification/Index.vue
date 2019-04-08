<template lang="pug">
.npane(:class="visible?'npane--active':''" @click.self="toggleNPane()")
	.npane__wrap
		.npane__header
			h5.mb-0 Notifications ({{count}})
		.npane__container
			.npane__list
				b-alert(:show="notification.length == 0" variant="warning") All Good :) There are no notifications at this moment!
				button.nblock(
					v-for="(item,index) in notification"
					@click="goTo(item.redirect_url,item.id)"
					:class="item.read_at?'':'nblock--unread'"
					:key="index")
					.nblock__photo
						img(src="~Images/avtar.svg")
					.nblock__details
						p.nblock__text {{item.notification}}
						small {{since(item.time_elapsed)}}
		.npane__footer
			button.btn.btn-sm.btn-link(@click="markAsReadAll") Mark All As Read
			//button.btn.btn-sm.btn-link View All Notifications
</template>

<script>
import bus from "Bus";
import m_common from "Mixins/common";
import moment from "moment";

export default {
	components: {},
	props: [],
	mixins: [m_common],
	data() {
		return {
			visible: false,
			notification: [],
			count: 0
		};
	},
	created() {
		var _this = this;
		_this.getNotifications();
		bus.$on("toggleNPane", () => {
			_this.toggleNPane();
		});
	},
	mounted() {},
	computed: {},
	methods: {
		goTo(link, notificationId) {
			var _this = this;
			_this.apiPost({
				url: `${_this.apiUrl()}/read-notifications`,
				data: {
					notification_id: notificationId
				}
			});
			bus.$emit("toggleNPane");
			this.$router.replace(link);
		},
		since(date) {
			return moment.unix(date).fromNow();
		},
		markAsReadAll() {
			var _this = this;
			_this.apiPost({
				url: `${_this.apiUrl()}/read-notifications`,
				success(data) {
					_this.getNotifications();
				}
			});
		},
		getNotifications() {
			var _this = this;
			_this.apiGet({
				url: `${_this.apiUrl()}/notification`,
				success(data) {
					_this.count = data.unread_notification_count;
					if (data.notification_count > 0) {
						_this.notification = data.notifications;
					}
				}
			});
		},
		toggleNPane() {
			var _this = this;
			_this.getNotifications();
			_this.visible = !_this.visible;
		}
	}
};
</script>

<style lang="scss">
@import "~ScssConfig";

.npane__footer {
	flex: 0 0 auto;
	padding: 10px;
	display: flex;
	box-shadow: 0 -10px 20px 0 rgba(0, 0, 0, 0.1);
	position: relative;
	z-index: 2;
	button {
		flex: 1 1 auto;
		+ button {
			margin-left: 10px;
		}
	}
}
.nblock__details {
	display: flex;
	flex-direction: column;
}
.npane__wrap {
	display: flex;
	flex-direction: column;
}
.npane__container {
	overflow: auto;
	background: $grey-300;
	padding: 10px;
	flex: 1 1 auto;
}
.nblock__photo {
	flex: 0 0 auto;
	width: 40px;
	height: 40px;
	margin-right: 10px;
	img {
		width: 100%;
	}
}
.nblock {
	display: flex;
	align-items: flex-start;
	color: #000 !important;
	padding: 15px 20px;
	text-decoration: none;
	background: #fff;
	border-radius: 5px;
	text-decoration: none !important;
	justify-content: flex-start;
	cursor: pointer;
	text-align: left;
	outline: 0 !important;
	opacity: 0.5;
	filter: grayscale(100%);
	+ .nblock {
		margin-top: 5px;
	}
	&:hover {
		background: $green-50;
	}
	small {
		font-size: 11px;
		margin-top: 2px;
		color: $grey-500;
	}
}

.nblock--unread {
	opacity: 1;
	filter: grayscale(0%);
}
.nblock__text {
	margin: 0;
	font-size: 13px;
}
.npane {
	position: fixed;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background: rgba(0, 0, 0, 0.5);
	visibility: hidden;
	opacity: 0;
	transition: all 0.3s ease 0s;
}
.npane--active {
	visibility: visible;
	opacity: 1;
	.npane__wrap {
		transform: translate3d(0%, 0, 0);
	}
}
.npane__wrap {
	width: 450px;
	background: #fff;
	position: absolute;
	top: 0;
	right: 0;
	bottom: 0;
	transition: all 0.3s ease 0s;
	transform: translate3d(100%, 0, 0);
	box-shadow: 0 -10px 100px 0 rgba(0, 0, 0, 0.3);
}
.npane__header {
	padding: 20px 30px;
	flex: 0 0 auto;
	box-shadow: 0 10px 20px 0 rgba(0, 0, 0, 0.1);
	position: relative;
	z-index: 2;
}
</style>
