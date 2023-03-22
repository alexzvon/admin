<template>
  <div>
    <div class="block full">
      <div class="block-title clearfix">
        <h1><strong>Список Городов присутствия франшизы</strong></h1>
        <div class="block-title-control">
          <b-button variant="success" @click="goCreateCity">Добавить</b-button>
        </div>
        <hr>
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
        />
        <div class="row">
          <div class="col-sm-12 col-xs-12 clearfix">
            <div v-if="showPagination" class="dataTables_paginate paging_bootstrap">
              <b-pagination
                v-model="page"
                :total-rows="totalRows"
                :per-page="perPage"
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

import Loading from '../Loading';
// import bTable from "bootstrap-vue/es/components/table/table";
// import bPagination from "bootstrap-vue/es/components/pagination/pagination"

// import bButton from 'bootstrap-vue/es/components/button/button';

import { getListCity } from '../../api/territory';

const countRows = 30;

export default {
  name: 'QuickFilterTable',

  components: {
    Loading,
    // bTable,
    // bPagination,
    // bButton,
  },
  data() {
    return {
      isBusy: false,
      items: [],
      fields: [
        {
          key: 'id',
          sortable: true,
          label: 'ID',
        },
        {
          key: 'name',
          sortable: false,
          label: 'Город',
        },
        {
          key: 'country',
          sortable: false,
          label: 'Страна',
        },
        {
          key: 'percent',
          sortable: false,
          label: '% надбавки',
        },
        {
          key: 'status',
          sortable: false,
          label: 'Статус',
        },
      ],
      totalRows: 0,
      lastPage: 0,
      sortDesc: false,
      sortBy: 'id',
      perPage: 30,
      page: 1,
      type: '0',
    };
  },
  computed: {
    showPagination() {
      return this.totalRows > countRows;
    },
  },
  watch: {
    page: function(newVal, oldVal) {
      if (newVal > 0 && newVal <= this.lastPage) {
        this.getQuickFilterTable();
      } else {
        this.page = oldVal;
      }
    },
  },
  created() {
    this.getCityTable();
  },
  methods: {
    sortingChanged(ctx) {
      ctx.page = 1;
      this.sortDesc = ctx.sortDesc,
      this.sortBy = ctx.sortBy,
      //      this.perPage = ctx.perPage,
      this.page = ctx.page,

      this.getQuickFilterTable();
    },

    onRowClick(item, index) {
      this.$router.push('/city/edit/' + item.id);
    },

    async getCityTable() {
      this.isBusy = true;

      try {
        const res = await getListCity({
          type: this.type,
          sortDesc: this.sortDesc,
          sortBy: this.sortBy,
          perPage: this.perPage,
          page: this.page,
        });

        //        console.log(res);

        this.items = res.data;
        this.totalRows = res.meta.total;
        this.page = parseInt(res.meta.current_page);
        this.perPage = parseInt(res.meta.per_page);
        this.lastPage = parseInt(res.meta.last_page);

        this.isBusy = false;
      } catch (error) {
        console.log(error);
        this.items = [];
        this.isBusy = false;
      }
    },
    goCreateCity() {
      this.$router.push('/city/create');
    },
  },
};

</script>

<style scoped>

</style>

