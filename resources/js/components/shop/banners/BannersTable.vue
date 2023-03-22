<template>
  <div>
    <div class="block full">
      <div class="block-title clearfix">

        <h1>Баннеры</h1>

        <div class="block-title-control">
          <router-link v-if="userCan('shop.banners.create')" :to="'/shop/banners/' + bannerType$ + '/create'" class="btn btn-sm btn-success active">
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

              <div class="col-sm-4 col-xs-12 clearfix text-center">
                <div class="btn-group">
                  <template v-for="(title, typeIdentif) in bannerTypes">
                    <button :class="{'btn btn-primary': true, 'btn-alt': bannerType$ !== typeIdentif}" @click="setBannerType(typeIdentif)">
                      {{ title }}
                    </button>
                  </template>
                </div>
              </div>

              <div class="col-sm-4 col-xs-12 clearfix">
                <div v-if="bannerPlacesSelect.length" class="dataTables_filter pull-right" style="min-width: 200px">
                  <tree-select
                    :options="bannerPlacesSelect"
                    :selected="place"
                    :params="{minimumResultsForSearch: -1, allowClear: false}"
                    @update:selected="setBannerPlaces"
                  />
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
              @refreshed="setHistoryState"
              empty-text="Список баннеров пуст."
              empty-filtered-text="Баннеров с такими параметрами не найдены."
              class="table table-vcenter table-condensed table-hover table-bordered no-footer"
              style="margin-bottom: 0;"
              @sort-changed="sortingChanged"
            >

              <!--  ID  -->

              <template slot="HEAD_id" slot-scope="banner">
                <span class="table-column-id">ID</span>
              </template>

              <template slot="id" slot-scope="banner">
                <router-link :to="banner.item.url">
                  <span class="table-column-id">
                    {{ banner.item.id }}
                  </span>
                </router-link>
              </template>

              <!--  Название  -->

              <template slot="title" slot-scope="banner">
                <router-link :to="banner.item.url" v-html="bannerTitle(banner.item)" />
              </template>

              <!--  Цена  -->

              <template slot="places" slot-scope="banner">
                {{ bannerPlacesList(banner.item.places) }}
              </template>

              <!--  Статус  -->

              <template slot="HEAD_enabled" slot-scope="banner">
                <span class="table-column-enabled">Статус</span>
              </template>

              <template slot="enabled" slot-scope="banner">
                <span class="table-column-enabled">
                  <toggle :key="banner.item.id" :checked="banner.item.enabled" @change="statusChange(banner.item.id)" />
                </span>
              </template>

              <!--  Удаление  -->

              <template slot="HEAD_delete" slot-scope="banner">
                <span class="table-column-delete" />
              </template>

              <template slot="delete" slot-scope="banner">
                <span class="table-column-delete">
                  <a class="btn btn-danger" @click="remove(banner.item.id)">
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
      title="Удаление баннера"
      title-tag="h3"
      centered
      ok-title="Удалить"
      cancel-title="Отмена"
      hide-header-close
      @ok="removeConfirm"
    >

      Вы действительно хотите удалить баннер?
    </b-modal>
  </div>
</template>

<script>
// import bTable from "bootstrap-vue/es/components/table/table";
// import bPagination from "bootstrap-vue/es/components/pagination/pagination";
// import bFormSelect from "bootstrap-vue/es/components/form-select/form-select";
// import bModal from "bootstrap-vue/es/components/modal/modal";

import Core from '../../../core';

import Toggle from '../../Toggle';
import Loading from '../../Loading';
import LanguagePicker from '../../LanguagePicker';

import TablePage from '../../../mixins/TablePage';
import Translatable from '../../../mixins/Translatable';
import StatusChangeable from '../../../mixins/StatusChangeable';
import BannersMixin from './mixin';
import TreeSelect from '../../TreeSelect';

import BannersTableModel from '../../../resources/shop/banner/BannersTableModel';
import TableWithFilters from '../../../mixins/TableWithFilters';

const defaultTableState = {
  page: 1,
  perPage: 15,
  place: 'all',
  bannerType$: 'default',
};

const bannerTypes = {
  default: 'Стандартные',
  header: 'В шапке',
};

export default {
  name: 'BannersTable',

  components: {
    Toggle,
    Loading,
    // bTable,
    // bPagination,
    // bFormSelect,
    // bModal,
    LanguagePicker,
    TreeSelect,
  },

  mixins: [
    TablePage,
    TableWithFilters,
    StatusChangeable,
    Translatable,
    BannersMixin,
  ],

  data() {
    const fields = [
      {
        key: 'id',
        sortable: false,
        thStyle: {
          width: '40px',
        },
      },
      {
        key: 'title',
        sortable: true,
        label: 'Название',
        thStyle: {
          width: '40%',
        },
      },
      {
        key: 'places',
        sortable: false,
        label: 'Места размещения',
        thStyle: {},
      },
    ];

    if (this.userCan('shop.banners.edit')) {
      fields.push({
        key: 'enabled',
        sortable: false,
      });
    }

    if (this.userCan('shop.banners.delete')) {
      fields.push({
        key: 'delete',
        sortable: false,
      });
    }

    return {
      rbacNamespace: 'shop.banners',
      loading: false,
      totalRows: 0,
      perPageOptions: [15, 30, 60],
      ...defaultTableState,

      fields,

      bannerPlaces: [],
      bannerTypes,

      banners: [],

      usedMainData: [
        'languages',
        'banner-places',
      ],
    };
  },

  computed: {
    bannerPlacesSelect() {
      if (this.bannerPlacesSelectOptions.length < 2) {
        return [];
      }

      return [
        {
          id: 'all',
          title: 'Все',
        },
        ...this.bannerPlacesSelectOptions,
      ];
    },
  },

  methods: {
    /*  Загрузка списка. */
    fetchItems({ currentPage, perPage, sortBy, sortDesc }) {
      return new Promise(resolve => {
        new Core.requestHandler('get', this.makePageApiUrl(), {
          page: currentPage,
          perPage,
          sortBy,
          sortType: sortDesc ? 'desc' : 'asc',
          place: this.place,
          bannerType: this.bannerType$,
        }).success(response => {
          const data = response.data;

          this.totalRows = this.nanToNum(parseInt(data.totalRows));
          this.page = parseInt(data.page) || 1;
          this.perPage = this.nanToNum(parseInt(data.perPage));

          const items = data.banners || [];

          resolve(items.map(item => new BannersTableModel(item, this.languages)));
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

    bannerPlacesList(places = []) {
      return places.reduce((acc, placeId) => {
        const place = this.bannerPlaces.find(place => place.id === placeId);

        if (place) {
          acc.push(place.name);
        }

        return acc;
      }, []).join(', ');
    },

    bannerTitle(banner) {
      let title = ['title', 'caption'].reduce((acc, item) => {
        if (banner.i18n[this.activeLanguageCode][item]) {
          acc.push(banner.i18n[this.activeLanguageCode][item]);
        }

        return acc;
      }, []).join('<br>');

      if (title.length < 5) {
        title = 'Без названия';
      }

      return title;
    },

    getDefaultState() {
      return defaultTableState;
    },

    setBannerPlaces(places) {
      this.place = places;
      this.refreshTable();
    },

    getValidPlace(value) {
      value = parseInt(value);
      const place = this.bannerPlaces.find(item => item.id === value);

      if (place) {
        return place.id;
      }

      return this.getDefaultState().place;
    },

    setBannerType(type) {
      this.place = this.getDefaultState().place;
      this.bannerType$ = type;
      this.refreshTable();
    },

    getValidBannerType$(value) {
      if (value in bannerTypes) {
        return value;
      }

      return Object.keys(bannerTypes)[0];
    },
  },
};
</script>
