<template>
  <div>
    <div class="block full">
      <div class="block-title clearfix">
        <h1>
          <strong>
            Заявки на возврат
          </strong>
        </h1>
        <hr>
      </div>

      <loading :loading="isBusy">
        <div class="row">
          <div class="col-sm-12 col-xs-12">
            <div class="btn-group">
              <template v-for="(title, typeIdentif) in types">
                <button
                  :key="title"
                  :class="{'btn btn-primary': true, 'btn-alt': type !== typeIdentif}"
                  @click="setType(typeIdentif)"
                >
                  {{ title }}
                </button>
              </template>
            </div>
          </div>
        </div>

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

import Loading from '../../Loading';
// import bTable from "bootstrap-vue/es/components/table/table";
// import bPagination from "bootstrap-vue/es/components/pagination/pagination"

import { getList } from '../../../api/reply';

const countRows = 30;

export default {
  name: 'ReplyTable',

  components: {
    Loading,
    // bTable,
    // bPagination,
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
          label: 'Наименование товара',
        },
        {
          key: 'status',
          sortable: false,
          label: 'Статус',
        },
        {
          key: 'created_at',
          sortable: true,
          label: 'Дата создания',
        },
      ],
      types: {
        '0': 'Все',
        '2': 'Возвращенные',
        '1': 'На рассмотрении',
      },
      totalRows: 0,
      lastPage: 0,
      sortDesc: false,
      sortBy: 'id',
      perPage: 15,
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
        this.refreshReplyTable();
      } else {
        this.page = oldVal;
      }
    },
  },
  created() {
    this.refreshReplyTable();
  },
  methods: {
    sortingChanged(ctx) {
      ctx.page = 1;
      this.sortDesc = ctx.sortDesc,
      this.sortBy = ctx.sortBy,
      this.perPage = ctx.perPage,
      this.page = ctx.page,

      this.refreshReplyTable();
    },

    onRowClick(item, index) {
      this.$router.push('/shop/reply/edit/' + item.id + '/' + item.product_id);
    },

    async refreshReplyTable() {
      this.isBusy = true;

      try {
        const res = await getList({
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
      } catch (error) {
        console.log(error);
        this.items = [];
        this.isBusy = false;
      }
    },

    setType(type) {
      if (this.type === type) {
        return;
      }
      this.type = type;
      this.page = 1;

      this.refreshReplyTable();
    },
  },
};

</script>

<style scoped>

</style>

