<template lang="pug">
.page__card
  v-server-table(
    :url="`${apiUrl()}/insurance`"
    :columns="datatable.columns"
    :options="datatable.options"
    class="insurance-table")
      router-link(:to="'/app/customer/'+props.row.user_id" slot="user_name" slot-scope="props") {{props.row.user_name}}

      router-link(:to="'/app/shipments/'+props.row.shipment_id" slot="shipment_name" slot-scope="props") {{props.row.shipment_name}}

      span(slot="created_at" slot-scope="props") {{formatDate(props.row.created_at)}}
      
      span(slot="insurance_charge" slot-scope="props" ) £{{props.row.insurance_charge}}
      
</template>
<script>
import c_page from "Components/Page.vue";
import m_common from "Mixins/common";

export default {
  components: {
    c_page
  },
  props: [],
  mixins: [m_common],
  data() {
    var _this = this;
    return {
      toEdit: false,
      datatable: {
        columns: ["shipment_name","user_name","insurance_charge","created_at"],
        options: {
          // initFilters: {
          //     user_id: _this.customerId ? _this.customerId : ""
          // },
          filterable: ["shipment_name", "user_name"],
          perPage: 25,
          pagination: {
            dropdown: false
          },
          perPageValues: [],
          sortable: ["shipment_id"],
          orderBy: {
            ascending: false
          },
          filterByColumn: true,
          skin: "table table-hover",
          responseAdapter(resp) {
            return {
              data: resp.data.insurance,
              count: resp.data.count
            };
          },
          headings: {
            shipment_name: 'Shipment Name',
            user_name: 'Customer Name',
            insurance_charge: 'Insurance Charge',
            created_at: 'Date'
          }
        }
      }
    };
  },
  created() {},
  mounted() {}
};
</script>

<style lang="scss">
@import "~Styles/vue-imports.scss";
.country__container {
  padding: 20px;
}
.country-table {
  font-size: 14px;
}
</style>
