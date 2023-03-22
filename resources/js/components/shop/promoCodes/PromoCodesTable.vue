<script>
import _ from 'lodash';
// import bTable from "bootstrap-vue/es/components/table/table";
// import bPagination from "bootstrap-vue/es/components/pagination/pagination";
// import bFormSelect from "bootstrap-vue/es/components/form-select/form-select";
// import bModal from "bootstrap-vue/es/components/modal/modal";

import Core from '../../../core';

import Toggle from '../../Toggle';
import Loading from '../../Loading';

import TablePage from '../../../mixins/TablePage';
import Translatable from '../../../mixins/Translatable';
import TableWithFilters from '../../../mixins/TableWithFilters';
import StatusChangeable from '../../../mixins/StatusChangeable';
import PromoCodesTableModel from '../../../resources/shop/promo/PromoCodesTableModel';

const defaultTableState = {
  page: 1,
  perPage: 30,
  type: 'all',
};

export default {
  name: 'PromoCodesTable',

  components: {
    Toggle,
    Loading,
    // bTable,
    // bPagination,
    // bFormSelect,
    // bModal,
  },

  mixins: [
    StatusChangeable,
    TablePage,
    Translatable,
    TableWithFilters,
  ],

  data() {
    const fields = [
      {
        key: 'id',
        sortable: false,
      },
      {
        key: 'name',
        sortable: false,
        label: 'Код',
      },
      {
        key: 'discount',
        sortable: false,
        label: 'Скидка',
        thStyle: {
          width: '40%',
        },
      },
    ];

    if (this.userCan('promo-codes.see')) {
      fields.push({
        key: 'quantity',
        sortable: false,
        label: 'Использований',
        thStyle: {
          width: '40%',
        },
      });
    }

    if (this.userCan('promo-codes.see')) {
      fields.push({
        key: 'conditions',
        label: 'Условия',
        sortable: false,
        thStyle: {
          width: '30%',
        },
      });
    }

    if (this.userCan('edit')) {
      fields.push({
        key: 'enabled',
        sortable: false,
      });
    }

    if (this.userCan('promo-codes.delete')) {
      fields.push({
        key: 'delete',
        sortable: false,
      });
    }

    return {
      rbacNamespace: 'promo-codes',
      loading: false,
      totalRows: 0,
      perPageOptions: [15, 30, 60],
      types: {
        all: 'Все',
        enabled: 'Активные',
        old: 'Завершенные',
        disabled: 'Отмененные',
      },
      ...defaultTableState,
      fields,

      currencies: null,

      usedMainData: [
        'currencies',
      ],

      countByType: {
        unconfirmed: 0,
        deleted: 0,
      },
    };
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

          const items = data['promo-codes'] || [];

          if (this.currencies) {
            resolve(items.map(item => new PromoCodesTableModel(item, this.currencies)));
          } else {
            this.fetchItemsCb = () => {
              resolve(items.map(item => new PromoCodesTableModel(item, this.currencies)));
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
  },
};
</script>

<template>
  <div>
    <div class="block full">

      <div class="block-title clearfix">
        <h1>
          <strong>
            Промокоды
          </strong>
        </h1>

        <div class="block-title-control">
          <router-link v-if="userCan('create')" to="/shop/promo-codes/create" class="btn btn-sm btn-success active">
            <i class="fa fa-plus-circle" /> Создать
          </router-link>
        </div>
      </div>

      <loading :loading="loading">
        <div class="table-responsive" style="overflow: visible">
          <div class="dataTables_wrapper form-inline no-footer">
            <div class="row">
              <div class="col-sm-6 col-xs-12 clearfix">
                <div v-if="showPagination" class="dataTables_paginate paging_bootstrap">
                  <b-pagination v-model="page" :total-rows="totalRows" :per-page="perPage" class="my-0" />
                </div>
              </div>

              <div class="col-sm-6 col-xs-12 text-center clearfix">
                <div class="pull-right">
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
            </div>

            <b-table
              ref="table"
              show-empty
              stacked="md"
              :items="fetchItems"
              :fields="fields"
              :busy.sync="loading"
              :current-page="page"
              :per-page="perPage"
              empty-text="Список промокодов пуст."
              @refreshed="setHistoryState"
              empty-filtered-text="Промокоды с такими параметрами не найдены."
              style="margin-bottom: 0"
              class="table table-vcenter table-condensed table-hover table-bordered no-footer"
            >

              <!--  ID  -->

              <template slot="HEAD_id" slot-scope="promoCode">
                <span class="table-column-id">ID</span>
              </template>

              <template slot="id" slot-scope="promoCode">
                <router-link :to="promoCode.item.url">
                  <span class="table-column-id">
                    {{ promoCode.item.id }}
                  </span>
                </router-link>
              </template>

              <!--  Код  -->

              <template slot="name" slot-scope="promoCode">
                <router-link :to="promoCode.item.url">
                  <span class="table-column-promocode">
                    {{ promoCode.item.name }}
                  </span>
                </router-link>
              </template>

              <!--  Количество  -->

              <template slot="quantity" slot-scope="promoCode">
                <template v-for="quantityString in promoCode.item.quantityStrings">
                  <div v-html="quantityString" />
                </template>

                <template v-if="promoCode.item.uses_percent">
                  <div class="promo-progress">
                    <div class="promo-progress__num">
                      {{ promoCode.item.uses_count }}/{{ promoCode.item.quantity }}
                    </div>

                    <div class="promo-progress__progress">
                      <div class="progress">
                        <div
                          class="progress-bar progress-bar-info"
                          role="progressbar"
                          :style="{width: promoCode.item.uses_percent + '%'}"
                        >
                          {{ promoCode.item.uses_percent }}%
                        </div>
                      </div>
                    </div>
                  </div>

                </template>
              </template>

              <!--  Условия  -->

              <template slot="conditions" slot-scope="promoCode">
                <span class="table-column-conditions">
                  <template v-for="condition in promoCode.item.conditions">
                    <div v-html="condition.toString()" />
                  </template>
                </span>
              </template>

              <!--  Статус  -->

              <template slot="HEAD_enabled" slot-scope="promoCode">
                <span class="table-column-enabled">Статус</span>
              </template>

              <template slot="enabled" slot-scope="promoCode">
                <span class="table-column-enabled">
                  <toggle :key="promoCode.item.id" :checked="promoCode.item.enabled" @change="statusChange(promoCode.item.id)" />
                </span>
              </template>

              <!--  Удаление  -->

              <template slot="HEAD_delete" slot-scope="promoCode">
                <span class="table-column-delete" />
              </template>

              <template slot="delete" slot-scope="promoCode">
                <span class="table-column-delete">
                  <a class="btn btn-danger" @click="remove(promoCode.item.id)">
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
      title="Удаление промокода"
      title-tag="h3"
      centered
      ok-title="Удалить"
      cancel-title="Отмена"
      hide-header-close
      @ok="removeConfirm"
    >

      Вы действительно хотите удалить промокод?
    </b-modal>
  </div>
</template>

<style>
    .promo-progress {
        display: flex;
        align-items: center;
        margin-top: 8px;
    }

    .promo-progress__progress {
        width: 100%;
        margin-left: 15px;
    }

    .promo-progress .progress {
        margin-bottom: 0;
    }
</style>
