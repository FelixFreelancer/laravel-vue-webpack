<template lang="pug">
section
	h3.dashboard__content__title Shipments History
	b-alert(:show="loaded && shipments.length == 0" variant="warning") You have no items that are ready for shipping at the moment
	.history-wrap(v-if="loaded && shipments.length > 0")
		.history-table.res-table
			table
				tr
					th Shipment #
					th Status
					th Date Shipped
					th Courier
					th Tracking #
				tr(v-for="item in shipments")
					td {{item.parcel_number}}
					td {{item.status}}
					td {{item.delivered_at}}
					td {{item.shipping_out_company}}
					td {{item.shipping_out_tracking}}
	pagination(v-if="shipments && shipments.length > 0" :nextData="getShipments" :pagination="pagination" :lastPage="lastPage" :page="page")
</template>

<script>
import m_common from "Mixins/common";
import pagination from "./Pagination.vue";

export default {
    components: {
        pagination
    },
    props: [],
    mixins: [m_common],
    data() {
        return {
            shipments: [],
            loaded: false,
            page: 1,
            lastPage: 0,
            pagination: {},
        };
    },
    created() {},
    mounted() {
        this.getShipments(this.page);
    },
    methods: {
        getShipments(page) {
            var _this = this;
            _this.apiGet({
                url: `${_this.apiUrl()}/history?page=${page}`,
                success(data) {
                    _this.shipments = data.shipment;
					_this.lastPage = page-1;
					_this.page = data.page;
					_this.pagination = data.pagination;
                    _this.loaded = true;
                }
            });
        }
    }
};
</script>

<style lang="css">
</style>
