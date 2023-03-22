<template>
  <div>
    <div class="block full">
      <div class="block-title clearfix">
        <h1>Отзывы</h1>
      </div>

      <loading :loading="loading">
        <div class="table-responsive" style="overflow: visible">
          <div class="dataTables_wrapper form-inline no-footer">
            <div class="row">
              <div class="col-xs-12 clearfix">
                <div class="btn-group">

                  <template v-for="(title, typeIdentif) in types">
                    <button
                      :class="{'btn btn-primary': true, 'active': type === typeIdentif}"
                      @click="setType(typeIdentif)"
                    >
                      {{ title }}
                      <template v-if="typeIdentif in countByType && countByType[typeIdentif] > 0">
                        <span class="badge">{{ countByType[typeIdentif] }}</span>
                      </template>
                    </button>
                  </template>

                </div>
              </div>
            </div>

            <b-table
              v-if="activeLanguageCode"
              ref="table"
              show-empty
              stacked="md"
              :items="fetchItems"
              :fields="fields"
              :busy.sync="loading"
              :current-page="page"
              :per-page="perPage"
              empty-text="Список отзывов пуст."
              @refreshed="setHistoryState"
              empty-filtered-text="Отзывы с такими параметрами не найдены."
              style="margin-bottom: 0"
              class="table table-vcenter table-condensed table-hover table-bordered no-footer"
            >

              <!--  ID  -->

              <template slot="HEAD_id" slot-scope="review">
                <span class="table-column-id">ID</span>
              </template>

              <template slot="id" slot-scope="review">
                <router-link :to="review.item.url">
                  <span class="table-column-id">
                    {{ review.item.id }}
                  </span>
                </router-link>
              </template>

              <!--  Оценка  -->

              <template slot="rate" slot-scope="review">
                <span class="table-column-id">
                  {{ review.item.rate }}
                </span>
              </template>

              <!--  Текст  -->

              <template slot="HEAD_text" slot-scope="review">
                Текст
              </template>

              <template slot="text" slot-scope="review">
                <router-link :to="review.item.url">
                  {{ review.item.text }}
                </router-link>
              </template>

              <!--  Название  -->

              <template slot="user_name" slot-scope="review">
                <a :href="review.item.user.url" target="_blank">
                  {{ review.item.user.full_name }}
                </a>
              </template>

              <!--  Отзыв о  -->

              <template slot="entity" slot-scope="review">
                <template v-if="review.item.product">
                  <a :href="'https://candellabra.com/ru/goods/'+review.item.product.id" target="_blank">
                    {{ review.item.product.i18n[activeLanguageCode].title }}
                  </a>
                </template>
              </template>

              <!--  Дата создания  -->

              <template slot="HEAD_created_at" slot-scope="review">
                <span class="table-column-date">Создан</span>
              </template>

              <template slot="created_at" slot-scope="review">
                <span class="table-column-date">
                  {{ review.item.created_at }}
                </span>
              </template>

              <!--  Статус  -->

              <template slot="HEAD_enabled" slot-scope="review">
                <span class="table-column-enabled">Статус</span>
              </template>

              <template slot="enabled" slot-scope="review">
                <span class="table-column-enabled">
                  <toggle :key="review.item.id" :checked="review.item.confirmed" @change="statusChange(review.item.id)" />
                </span>
              </template>

              <!--  Удаление  -->

              <template slot="HEAD_delete" slot-scope="review">
                <span class="table-column-delete" />
              </template>

              <template slot="delete" slot-scope="review">
                <span class="table-column-delete">
                  <a class="btn btn-danger" @click="remove(review.item.id)">
                    <i class="fa fa-times" />
                  </a>
                </span>
              </template>
            </b-table>

            <div class="row">
              <div class="col-sm-6 col-xs-12 clearfix">
                <div v-if="showPagination" class="dataTables_paginate paging_bootstrap">
                  <b-pagination
                    v-model="page"
                    :total-rows="totalRows"
                    :per-page="perPage"
                    class="my-0"
                  />
                </div>
              </div>

              <div class="col-sm-6 col-xs-6">
                <div class="dataTables_length">
                  <label>
                    <b-form-select :options="perPageOptions" :value="perPage" @change="setPerPage" />
                  </label>
                </div>

                <div class="dataTables_info" role="status" aria-live="polite">
                  <strong>{{ showedFrom }}</strong> - <strong>{{ showedTo }}</strong> из <strong>{{ totalRows }}</strong>
                </div>
              </div>
            </div>
          </div>

        </div>
      </loading>
    </div>

    <b-modal
      id="removeModal"
      ref="removeModal"
      title="Удаление отзыва"
      title-tag="h3"
      centered
      ok-title="Удалить"
      cancel-title="Отмена"
      hide-header-close
      @ok="removeConfirm"
    >

      Вы действительно хотите удалить отзыв?
    </b-modal>
  </div>
</template>

<script>
import _ from 'lodash';
// import bTable from "bootstrap-vue/es/components/table/table";
// import bPagination from "bootstrap-vue/es/components/pagination/pagination";
// import bFormSelect from "bootstrap-vue/es/components/form-select/form-select";
// import bModal from "bootstrap-vue/es/components/modal/modal";

import Core from '../../core';

import Toggle from '../Toggle';
import Loading from '../Loading';
import LanguagePicker from '../LanguagePicker';

import TablePage from '../../mixins/TablePage';
import Translatable from '../../mixins/Translatable';
import StatusChangeable from '../../mixins/StatusChangeable';
import ReviewsModel from '../../resources/ReviewsModel';

const defaultTableState = {
  page: 1,
  perPage: 15,
  type: 'all',
};

export default {
  name: 'ReviewsTable',

  components: {
    Toggle,
    Loading,
    // bTable,
    // bPagination,
    // bFormSelect,
    // bModal,
    LanguagePicker,
  },

  mixins: [
    StatusChangeable,
    TablePage,
    Translatable,
  ],

  data() {
    const fields = [
      {
        key: 'id',
        sortable: false,
      },
      {
        key: 'rate',
        sortable: false,
        label: 'Оценка',
      },
      {
        key: 'text',
        sortable: false,
        label: 'Текст',
        thStyle: {
          width: this.userCan('reviews.see', false) ? '40%' : '100%',
        },
      },
    ];

    if (this.userCan('reviews.see', false)) {
      fields.push({
        key: 'user_name',
        sortable: false,
        label: 'Пользователь',
        thStyle: {
          width: '30%',
        },
      });
    }

    if (this.userCan('reviews.see', false)) {
      fields.push({
        key: 'entity',
        label: 'Товар',
        sortable: false,
        thStyle: {
          width: '30%',
        },
      });
    }

    if (this.userCan('reviews.edit', false)) {
      fields.push({
        key: 'enabled',
        sortable: false,
      });
    }

    if (this.userCan('reviews.delete', false)) {
      fields.push({
        key: 'delete',
        sortable: false,
      });
    }

    return {
      loading: false,
      totalRows: 0,
      perPageOptions: [15, 30, 60],
      types: {
        all: 'Все',
        confirmed: 'Подтвержденные',
        unconfirmed: 'Неподтвержденные',
        deleted: 'Удаленные пользователем',
      },
      ...defaultTableState,
      fields,

      usedMainData: [
        'languages',
      ],

      countByType: {
        unconfirmed: 0,
        deleted: 0,
      },
    };
  },

  computed: {
    showedFrom() {
      return this.nanToNum((this.page - 1) * this.perPage + 1);
    },

    showedTo() {
      const to = this.page * this.perPage;
      return this.nanToNum(to > this.totalRows ? this.totalRows : to);
    },

    showPagination() {
      return this.perPage < this.totalRows;
    },
  },

  created() {
    const query = window.location.search.replace('?', '');

    if (_.isEmpty(query)) {
      return;
    }

    query.split('&').forEach(item => {
      item = item.split('=');

      const key = item[0];
      const value = item[1];

      this[key] = this.getValid(key, value);
    });
  },

  methods: {
    loadData() {
      this.fetchMainData().then(data => {
        this.initMainData(data);

        if (typeof this.fetchItemsCb === 'function') {
          this.fetchItemsCb();
          this.fetchItemsCb = undefined;
        }
      });
    },

    getValidPage(value) {
      value = parseInt(value);
      return isNaN(value) ? defaultTableState.page : value;
    },

    getValidType(value) {
      return value in this.types ? value : defaultTableState.type;
    },

    getValidPerPage(value) {
      value = parseInt(value);

      if (this.perPageOptions.indexOf(value) !== -1) {
        return value;
      }

      return this.perPageOptions[0];
    },

    getValid(key, value) {
      const methodName = 'getValid' + Core.camelize(key, true);

      return this[methodName](value);
    },

    setHistoryState() {
      const queryArr = Object.keys(defaultTableState).reduce((acc, key) => {
        const validValue = this.getValid(key, this[key]);

        if (validValue !== defaultTableState[key]) {
          acc.push(encodeURIComponent(key) + '=' + encodeURIComponent(this[key]));
        }

        return acc;
      }, []);

      let pathWithQuery = window.location.pathname;

      if (queryArr.length > 0) {
        pathWithQuery += '?' + queryArr.join('&');
      }

      if (this.$router.path !== pathWithQuery) {
        this.$router.push(pathWithQuery);
      }
    },

    /*
              Загрузка списка.
            */

    fetchItems({ currentPage, perPage }) {
      return new Promise(resolve => {
        new Core.requestHandler('get', this.makePageApiUrl(), {
          page: currentPage,
          perPage,
          type: this.type,
        }).success(response => {
          const data = response.data;

          this.totalRows = this.nanToNum(parseInt(data.totalRows));
          this.page = parseInt(data.page) || 1;
          this.perPage = this.nanToNum(parseInt(data.perPage));

          const byType = {};

          for (const key in this.countByType) {
            byType[key] = key in data ? data[key] : 0;
          }

          this.countByType = byType;

          const items = data.reviews || [];

          if (this.languages) {
            resolve(items.map(item => new ReviewsModel(item, this.languages)));
          } else {
            this.fetchItemsCb = () => {
              resolve(items.map(item => new ReviewsModel(item, this.languages)));
            };
          }
        }).start();
      });
    },

    setType(type) {
      if (this.type === type) {
        return;
      }

      this.type = type;

      this.refreshTable();
    },

    sortingChanged(ctx) {
      ctx.page = 1;
    },

    setPerPage(value) {
      this.perPage = value;
      this.page = 1;
    },

    refreshTable() {
      this.$nextTick(() => {
        this.$refs.table.refresh();
      });
    },

    nanToNum(value, num = 0) {
      return isNaN(value) ? num : value;
    },
  },
};
</script>

