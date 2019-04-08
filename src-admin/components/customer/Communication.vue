<template lang="pug">
.page__card
    button.btn.btn-primary.mb-2(@click="sendMail") Send New Email
    v-server-table(
        v-if="customerDetails.id"
        :url="`${apiUrl()}/mail/`"
        :columns="datatable.columns"
        :options="datatable.options"
        ref="mailTable"
        class="user-table")
        span(slot="name" slot-scope="props") {{props.row.first_name}} {{props.row.last_name}}
        span(slot="created_at" slot-scope="props") {{formatDate(props.row.created_at)}}
    c_sendMail(:customerDetails="customerDetails" :modalClose="modalClose")
</template>

<script>
import c_page from "Components/Page.vue";
import m_common from "Mixins/common";
import c_sendMail from "Components/customer/SendMail.vue";

export default {
	components: {
		c_page,
		c_sendMail
	},
	props: ["customerId", "customerDetails"],
	mixins: [m_common],
	data() {
		var _this = this;
		return {
			datatable: {
				columns: ["subject", "mail", "to", "from", "created_at"],
				options: {
					initFilters: {
						user_id: _this.customerId ? _this.customerId : ""
					},
					filterable: ["mail", "subject", "user_id", "created_at"],
					perPage: 10,
					pagination: { dropdown: false },
					perPageValues: [],
					sortable: ["id"],
					orderBy: { ascending: false },
					filterByColumn: true,
					skin: "table table-hover",
					columnsClasses: {
						from: "user-table__from",
						to: "user-table__to",
						subject: "user-table__subject",
						mail: "user-table__mail",
						created_at: "user-table__created_at"
					},
					responseAdapter(resp) {
						return {
							data: resp.data.mails,
							count: resp.data.count
						};
					},
					headings: {
						mail: "Mail Content"
					}
				}
			}
		};
	},
	created() {},
	mounted() {},
	methods: {
		sendMail() {
			this.$root.$emit("bv::show::modal", "sendMailModal");
		},
		modalClose() {
			this.$refs.mailTable.refresh();
			this.$root.$emit("bv::hide::modal", "sendMailModal");
		}
	}
};
</script>

<style lang="scss">
.user-table__from,
.user-table__to {
	white-space: nowrap;
}
.user-table__subject {
	font-weight: bold;
}
.user-table__mail {
	font-size: 13px;
}
.user-table__created_at {
	font-size: 13px;
}
</style>
