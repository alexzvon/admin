<template>
  <div>
    <div class="block full">
      <div class="block-title clearfix">
        <h1><strong>Список возможных источников дохода Партнёра</strong></h1>
        <div class="block-title-control">
          <b-button variant="success" @click="goCreateIncome">Добавить</b-button>
        </div>
        <hr/>
      </div>

      <loading :loading="isBusy">
        <b-table
          class="table table-vcenter table-condensed table-hover table-bordered dataTable no-footer"
          striped
          hover
          show-empty
          stacked="md"
          :sort-by.sync="sortBy"
          :sort-desc.sync="sortDesc"
          :busy="isBusy"
          :items="items"
          :fields="fields"
          :current-page="0"
          :per-page="0"
          empty-text="Список товаров пуст."
          empty-filtered-text="Товары с такими параметрами не найдены."
          @sort-changed="sortingChanged"
          @row-clicked="onRowClick"
        >
        </b-table>
        <div class="row">
          <div class="col-sm-12 col-xs-12 clearfix">
            <div class="dataTables_paginate paging_bootstrap" v-if="showPagination">
              <b-pagination
                :total-rows="totalRows"
                :per-page="perPage"
                v-model="page"
                class="my-0"
              />
            </div>
          </div>
        </div>
      </loading>
    </div>
  </div>
</template>

<script>
import Loading from "../Loading";
// import bTable from "bootstrap-vue/es/components/table/table";
// import bPagination from "bootstrap-vue/es/components/pagination/pagination";
// import bButton from "bootstrap-vue/es/components/button/button";

import { getListIncome } from "../../api/income";

const countRows = 30;

export default {
  name: "incomeTable",
  data() {
    return {
      isBusy: false,
      items: [],
      fields: [
        {
          key: "id",
          sortable: true,
          label: "ID",
        },
        {
          key: "name",
          sortable: false,
          label: "Название",
        },
        {
          key: "type",
          sortable: false,
          label: "Тип",
        },
        {
          key: "status",
          sortable: false,
          label: "Статус",
        },
      ],
      totalRows: 0,
      lastPage: 0,
      sortDesc: false,
      sortBy: 'id',
      perPage: 30,
      page: 1,
      type: "0"
    }
  },

  components: {
    Loading,
    // bTable,
    // bPagination,
    // bButton,
  },
  computed: {
    showPagination() {
      return this.totalRows > countRows;
    }
  },
  watch: {
    page: function(newVal, oldVal) {
      if(newVal > 0 && newVal <= this.lastPage) {
        this.getIncomeTable();
      }
      else {
        this.page = oldVal;
      }
    }
  },
  created() {
    this.getIncomeTable();
  },
  methods: {
    sortingChanged(ctx) {
      ctx.page = 1;
      this.sortDesc = ctx.sortDesc,
        this.sortBy = ctx.sortBy,
        this.perPage = ctx.perPage,
        this.page = ctx.page,

        this.getIncomeTable();
    },

    onRowClick(item, index) {
      this.$router.push('/income/edit/' + item.id);
    },

    async getIncomeTable() {
      this.isBusy = true

      try {
        const res = await getListIncome({
          type: this.type,
          sortDesc: this.sortDesc,
          sortBy: this.sortBy,
          perPage: this.perPage,
          page: this.page,
        });

        this.items = res.data;
        this.totalRows = res.meta.total;
        this.page = parseInt(res.meta.current_page);
        this.perPage = parseInt(res.meta.per_page);
        this.lastPage = parseInt(res.meta.last_page);

        this.isBusy = false;
      }
      catch(error) {
        console.log(error);
        this.items = [];
        this.isBusy = false;
      }
    },
    goCreateIncome() {
      this.$router.push('/income/create');
    },
  }
}
</script>

<style scoped>

</style>