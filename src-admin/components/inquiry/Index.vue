<template lang="pug">
//📣 : Page Template
c_page.customer.page--grey()
	h3(slot="title") Inquiries
	//📣 : Page Content
	.customer__container(slot='content')
		.page__card
			v-server-table(
				:url="`${apiUrl()}/inquiry`"
				:columns="datatable.columns"
				:options="datatable.options"
				ref="inquiryTable"
				id="inquiryTable"
				class="inquiry-table")

				span(slot="date" slot-scope="props") {{ timestamp(props.row.date) }}

				span(slot="status" slot-scope="props")
					h5: .badge(:style="{backgroundColor:statusColor.inquiry[props.row.status]}") {{props.row.status}}

				span(slot="name" slot-scope="props" )
					router-link(:to="'/app/customer/'+props.row.user_id" v-if="props.row.user_id") {{props.row.name}}
					span(v-if="!props.row.user_id") {{props.row.name}}

				.table-actions(slot="actions" slot-scope="props")
					button(class="table-btn btn btn-light" title="Edit" @click="sendMail(props.row)")
						i.material-icons email
					button(class="table-btn btn btn-light" title="Delete" @click="deleteInquiry(props.row.id)"   v-if="access('inquiry','delete')")
						i.material-icons delete
			alert(:title="title" :id="toEdit" :cancel="cancel" :ok="ok")
				p(slot="desc") Are you sure you want to delete this?
			c_sendMail(:customerDetails="customerDetails" :modalClose="sendMailModalClose")
</template>
<script>
import c_page from "Components/Page.vue";
import m_common from "Mixins/common";
import alert from "Components/common/Alert.vue";
import c_sendMail from "./SendMail.vue";

export default {
    components: {
        c_page,
		c_sendMail,
		alert
    },
    props: [],
    mixins: [m_common],
    data() {
        return {
			title: "Inquiry",
            toEdit: false,
			customerDetails:'',
            datatable: {
                columns: ["name", "email", "subject", "message", "date", "status","actions"],
                options: {
                    filterable: ["name", "email", "subject"],
                    perPage: 25,
                    pagination: {
                        dropdown: false
                    },
                    perPageValues: [],
                    sortable: ["id"],
                    orderBy: {
                        ascending: false
                    },
                    filterByColumn: true,
                    skin: "table table-hover",
                    listColumns: {},
                    responseAdapter(resp) {
                        return {
                            data: resp.data.contact,
                            count: resp.data.count
                        };
                    },
                    headings: {},
                }
            }
        };
    },
    created() {},
    mounted() {},
    methods: {
		sendMail(customerDetails) {
			this.customerDetails = customerDetails;
			this.$root.$emit("bv::show::modal", "sendMailModal");
		},
		sendMailModalClose() {
			this.$refs.inquiryTable.refresh();
			this.$root.$emit("bv::hide::modal", "sendMailModal");
		},
		deleteInquiry(id) {
            this.toEdit = id;
            this.$root.$emit("bv::show::modal", "alertModal");
        },
        cancel() {
            this.$root.$emit("bv::hide::modal", "alertModal");
        },
        ok(id) {
            var _this = this;
            this.apiPost({
                url: `${this.apiUrl()}/inquiry/${id}`,
                method: 'delete',
                formId: "inquiryTable",
                msg: {
                    success: 'Inquiry Deleted Successfully'
                },
                success(data) {
                    _this.$refs.inquiryTable.refresh();
                }
            });
        },
	}
};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";
.customer__container {
    padding: 20px;
}
.user-table {
    font-size: 14px;
}
</style>
