<template>
  <div>
    <div class="block full">
      <div class="block-title clearfix">
        <h1><strong>Список пользователей партнёров</strong></h1>
        <div class="block-title-control">
          <b-button variant="success" @click="goCreateCompanies">Добавить</b-button>
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
        >

          <template #cell(delete)="companies">
            <span class="table-column-image">
              <div class="product-preview-image">
                <b-button variant="danger" @click.stop="destroyCompaniesTable(companies.item.id)">
                  <i class="el-icon-delete" />
                </b-button>
              </div>
            </span>
          </template>

        </b-table>
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
import Loading from '../../../components/Loading';

import { getListCompanies, destroyCompanies } from '../../../api/companies';

const countRows = 30;

export default {
  name: 'CompaniesTable',
  components: {
    Loading,
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
          key: 'fio',
          sortable: false,
          label: 'Юр. название',
        },
        {
          key: 'name',
          sortable: false,
          label: 'Отображаемое имя',
        },
        {
          key: 'cities',
          sortable: false,
          label: 'Город',
        },
        {
          key: 'delivery_address',
          sortable: false,
          label: 'Адрес доставки',
        },
        {
          key: 'status',
          sortable: false,
          label: 'Статус',
        },
        {
          key: 'delete',
          sortable: false,
          label: 'Удалить',
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
    this.getListCompaniesTable();
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
      this.$router.push('/companies/edit/' + item.id);
    },
    async getListCompaniesTable() {
      try {
        const { data } = await getListCompanies({
          type: this.type,
          sortDesc: this.sortDesc,
          sortBy: this.sortBy,
          perPage: this.perPage,
          page: this.page,
        });
        this.items = data;
      } catch (error) {
        console.log(error);
      }
    },
    async destroyCompaniesTable(id) {
      try {
        await destroyCompanies(id);
        this.$router.go();
      }
      catch (error) {
        console.log(error);
      }
    },
    goCreateCompanies() {
      this.$router.push('/companies/create');
    },
  },
};
</script>

<style scoped>

</style>

