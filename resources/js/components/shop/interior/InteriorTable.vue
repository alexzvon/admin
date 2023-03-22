<script>
import Core from '../../../core';
//  import bModal from 'bootstrap-vue/es/components/modal/modal'

import LanguagePicker from '../../LanguagePicker';

import TablePage from '../../../mixins/TablePage';
import Sortable from '../../../mixins/Sortable';
import Translatable from '../../../mixins/Translatable';
import TableWithFilters from '../../../mixins/TableWithFilters';
import Toggle from '../../Toggle';

import InteriorTableModel from '../../../resources/shop/interior/InteriorTableModel';

// import bTable from 'bootstrap-vue/es/components/table/table'
// import bPagination from 'bootstrap-vue/es/components/pagination/pagination'
// import bFormSelect from 'bootstrap-vue/es/components/form-select/form-select'
import Loading from '../../Loading';

const defaultTableState = {
  page: 1,
  perPage: 15,
};

export default {
  name: 'Interiors',

  components: {
    // bModal,
    Toggle,
    LanguagePicker,
    // bTable,
    // bPagination,
    // bFormSelect,
    Loading,
  },

  mixins: [
    Sortable,
    TablePage,
    TableWithFilters,
    Translatable,
  ],

  data() {
    const fields = [
      {
        key: 'id',
        sortable: false,
      },
      {
        key: 'image',
        sortable: false,
      },
      {
        key: 'title',
        sortable: false,
        label: 'Название',
        thStyle: {
          width: '100%',
        },
      },
    ];

    if (this.userCan('interriors.delete', false)) {
      fields.push({
        key: 'delete',
        sortable: false,
      });
    }

    return {
      fields,
      rbacNamespace: 'shop.interiors',
      loading: false,
      totalRows: 0,
      perPageOptions: [15, 30, 60],

      ... defaultTableState,

      rooms: [],
      styles: [],

      usedMainData: [
        'languages',
        'rooms',
        'styles',
      ],
    };
  },

  watch: {
    items: 'refreshSort',
  },

  methods: {
    getDefaultState() {
      return defaultTableState;
    },

    fetchItems({ currentPage, perPage }) {
      return this.onMainDataLoaded()
        .then(() => {
          return new Promise(resolve => {
            new Core.requestHandler('get', this.makePageApiUrl(), {
              page: currentPage,
              perPage,
              type: this.type,
            })
              .success(response => {
                const data = response.data;

                this.totalRows = this.nanToNum(parseInt(data.totalRows));
                this.page = parseInt(data.page) || 1;
                this.perPage = this.nanToNum(parseInt(data.perPage));

                const items = data.interiors || [];

                resolve(items.map(item => new InteriorTableModel(item, this.languages)));
              })
              .start();
          });
        });
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
            Интерьеры
          </strong>
        </h1>

        <div v-if="userCan('interiors.create')" class="block-title-control">
          <language-picker :languages="languages" :active-language-code.sync="activeLanguageCode" />

          <span class="btn-separator-xs" />

          <router-link to="/shop/interiors/create" class="btn btn-sm btn-success active">
            <i class="fa fa-plus-circle" /> Создать
          </router-link>
        </div>
      </div>

      <loading :loading="loading">
        <div class="table-responsive" style="overflow: visible">
          <div class="dataTables_wrapper form-inline no-footer">
            <div class="row">
              <div class="col-sm-4 col-xs-12 clearfix">
                <div v-if="showPagination" class="dataTables_paginate paging_bootstrap">
                  <b-pagination v-model="page" :total-rows="totalRows" :per-page="perPage" class="my-0" />
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
              empty-text="Список интерьеров пуст."
              empty-filtered-text="Интерьеры с такими параметрами не найдены."
              @refreshed="setHistoryState"
              class="table table-vcenter table-condensed table-hover table-bordered no-footer"
            >

              <!--  ID  -->

              <template slot="HEAD_id" slot-scope="item">
                <span class="table-column-id">ID</span>
              </template>

              <template slot="id" slot-scope="item">
                <router-link :to="item.item.url">
                  <span class="table-column-id">
                    {{ item.item.id }}
                  </span>
                </router-link>
              </template>

              <!--  Изображение  -->

              <template slot="HEAD_image" slot-scope="item">
                <span class="table-column-image">Изображение</span>
              </template>

              <template slot="image" slot-scope="item">
                <span class="table-column-image">
                  <router-link :to="item.item.url">
                    <div class="product-preview-image">
                      <img :src="$helpers.getImagePath(item.item.image)">
                    </div>
                  </router-link>
                </span>
              </template>

              <!--  Название  -->

              <template slot="title" slot-scope="item">
                <router-link :to="item.item.url" v-html="item.item.i18n[activeLanguageCode].title" />
              </template>

              <!--  Дата создания  -->

              <template slot="HEAD_created_at" slot-scope="item">
                <span class="table-column-date">Создан</span>
              </template>

              <template slot="created_at" slot-scope="item">
                <span class="table-column-date">
                  {{ item.item.created_at }}
                </span>
              </template>

              <!--  Удаление  -->

              <template slot="HEAD_delete" slot-scope="item">
                <span class="table-column-delete" />
              </template>

              <template slot="delete" slot-scope="item">
                <span class="table-column-delete">
                  <a class="btn btn-danger" @click="remove(item.item.id)">
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
      title="Удаление интерьера"
      title-tag="h3"
      centered
      ok-title="Удалить"
      cancel-title="Отмена"
      hide-header-close
      @ok="removeConfirm"
    >

      Вы действительно хотите удалить интерьер?
    </b-modal>
  </div>
</template>
