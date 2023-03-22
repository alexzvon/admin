<template>
  <div>
    <div class="sideNavigation__content">
      <div class="block full">
        <div class="block-title clearfix">
          <h1>Товары</h1>
          <div class="block-title-control">
            <import-btn />
            <a
              v-if="userCan('shop.excel-import-export.products-export')"
              target="_blank"
              href="/exports/products"
              class="btn btn-sm btn-default"
            >
              Выгрузить в Excel
            </a>
            <router-link
              v-if="userCan('shop.products.create')"
              to="/shop/products/create"
              class="btn btn-sm btn-success active"
            >
              <i class="fa fa-plus-circle" /> Создать
            </router-link>
          </div>
        </div>

        <loading :loading="loading">
          <ProductsFilters
            @updated="refreshTable()"
          />
          <div class="table-responsive" style="overflow: visible">
            <div class="dataTables_wrapper form-inline no-footer">
              <div class="row">
                <div class="col-sm-6 col-xs-12">
                  <div class="btn-group">
                    <template v-for="(title, typeIdentif) in types">
                      <button
                        :class="{'btn btn-primary': true, 'btn-alt': type !== typeIdentif}"
                        @click="setType(typeIdentif)"
                      >
                        {{ title }}
                      </button>
                    </template>
                  </div>
                </div>

                <div class="col-sm-6 col-xs-12 clearfix">
                  <div class="col-sm-12 col-xs-12 clearfix">
                    <b-form-group>
                      <b-form-radio-group
                        v-model="selected"
                        :options="options"
                        name="radioInline"
                        buttons
                      />
                    </b-form-group>
                  </div>
                  <div class="col-sm-12 col-xs-12 clearfix">
                    <div class="dataTables_filter">
                      <search-input
                        :value="searchPhrase"
                        placeholder="Поиск"
                        class="form-control"
                        type="search"
                        aria-controls="example-datatable"
                        @change="search"
                      />
                    </div>
                  </div>
                </div>
              </div>

              <b-table
                v-if="priceTypes.length"
                ref="table"
                show-empty
                stacked="md"
                :sort-by.sync="sortBy"
                :sort-desc.sync="sortDesc"
                :items="fetchItems"
                :fields="fields"
                :busy.sync="loading"
                @refreshed="setHistoryState"
                :current-page="page"
                :per-page="perPage"
                empty-text="Список товаров пуст."
                empty-filtered-text="Товары с такими параметрами не найдены."
                class="table table-vcenter table-condensed table-hover table-bordered dataTable no-footer"
                @sort-changed="sortingChanged"
              >

                <!--  ID  -->

                <template slot="HEAD_id" slot-scope="product">
                  <span class="table-column-id">ID</span>
                </template>

                <template slot="id" slot-scope="product">
                  <router-link :to="product.item.url">
                    <span class="table-column-id">
                      {{ product.item.id }}
                    </span>
                  </router-link>
                </template>

                <!--  Изображение  -->
                <template #cell(image)="product">
                  <span class="table-column-image">
                    <router-link :to="product.item.url">
                      <div class="product-preview-image">
                        <img
                          :src="$helpers.getImagePath(product.item.image.src)"
                          :srcset="`${$helpers.getImagePath(product.item.image.srcset)} 2x`"
                        >
                      </div>
                    </router-link>
                  </span>
                </template>

                <!--  Название  -->
                <template #cell(title)="product">
                  <router-link
                    :to="product.item.url"
                    v-html="product.item.i18n[activeLanguageCode].title"
                  />
                </template>

                <!--  Цена  -->
                <template #head(price)="product">
                  <dropdown
                    class-name="btn-group btn-group-xs"
                    style="margin: -4px 2px -2px;"
                    position="right"
                    :options="priceTypes"
                    @click.native.stop
                  >

                    <template slot="button">
                      <a class="btn btn-default dropdown-toggle">
                        <i class="fa fa-chevron-down fa-fw" />
                      </a>
                    </template>

                    <template slot="option" slot-scope="{ id, i18n }">
                      <li :class="{active: activePriceType === id}">
                        <a @click="setActivePriceType(id)">
                          {{ i18n[activeLanguageCode].title }}
                        </a>
                      </li>
                    </template>
                  </dropdown>

                  {{ activePriceTypeTitle }} <span style="color:red;">{{ salePriceTypeTitle }}</span>
                </template>

                <template #cell(price)="product">
                  {{ getItemPrice(product.item) }}

                  <span v-if="getSalePrice(product.item)" style="color:red;">{{ getSalePrice(product.item) }}</span>
                </template>

                <!--  Дата создания  -->
                <template #cell(created_at)="product">
                  <span class="table-column-date">
                    {{ product.item.created_at }}
                  </span>
                </template>

                <!--  Статус  -->
                <template #cell(enabled)="product">
                  <span class="table-column-enabled">
                    <toggle
                      :key="product.item.id"
                      :checked="product.item.enabled"
                      @change="statusChange(product.item.id)"
                    />
                  </span>
                </template>

                <!--  Удаление  -->
                <template #cell(delete)="product">
                  <span class="table-column-delete">
                    <a class="btn btn-danger" @click="remove(product.item.id)">
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
        title="Удаление товара"
        title-tag="h3"
        centered
        ok-title="Удалить"
        cancel-title="Отмена"
        hide-header-close
        @ok="removeConfirm"
      >
        Вы действительно хотите удалить товар?
      </b-modal>
    </div>

  </div>
</template>

<script>

import Core from '../../../core';

import Toggle from '../../Toggle';
import Loading from '../../Loading';
import SearchInput from '../../SearchInput';
import Dropdown from '../../Dropdown';
import LanguagePicker from '../../LanguagePicker';

import TablePage from '../../../mixins/TablePage';
import Translatable from '../../../mixins/Translatable';
import StatusChangeable from '../../../mixins/StatusChangeable';

import ProductsTableModel from '../../../resources/shop/ProductsTableModel';
import TableWithFilters from '../../../mixins/TableWithFilters';
import ImportBtn from '../products/import/ImportBtn';

const defaultTableState = {
  sortBy: 'id',
  sortDesc: false,
  page: 1,
  perPage: 15,
  searchPhrase: '',
  type: 'all',
};

export default {
  name: 'ProductsTable',

  mixins: [
    TablePage,
    TableWithFilters,
    StatusChangeable,
    Translatable,
  ],

  components: {
    Toggle,
    Loading,
    SearchInput,
    Dropdown,
    LanguagePicker,
    ProductsFilters: require('./ProductsFilters').default,
    ImportBtn,
  },

  data() {
    const fields = [
      {
        key: 'id',
        sortable: true,
      },
      {
        key: 'image',
        sortable: false,
        label: 'Изображение',
      },
      {
        key: 'title',
        sortable: true,
        label: 'Название',
        thStyle: {
          width: this.userCan('shop.prices.see', false) ? '70%' : '100%',
        },
      },
    ];

    if (this.userCan('shop.prices.see', false)) {
      fields.push({
        key: 'price',
        sortable: true,
        thStyle: {
          width: '30%',
        },
      });
    }

    if (this.userCan('shop.products.edit', false)) {
      fields.push({
        key: 'enabled',
        sortable: true,
        label: 'Статус',
      });
    }

    if (this.userCan('shop.products.delete', false)) {
      fields.push({
        key: 'delete',
        sortable: false,
        label: 'Удалить',
      });
    }

    return {
      selected: 'standart',
      options: [
        { text: 'Стандартный', value: 'standart' },
        { text: 'Id товара', value: 'product' },
        { text: 'Id поставщика', value: 'supplier' },
      ],
      loading: false,
      totalRows: 0,
      perPageOptions: [30, 60],
      types: {
        all: 'Все',
        popular: 'Популярные',
        new: 'Новинки',
        'no-image': 'Без изображений',
        sale: 'Со скидкой',
      },
      ...defaultTableState,
      fields,

      priceTypes: [],
      activePriceType: null,

      usedMainData: [
        'languages',
        'price-types',
      ],
    };
  },

  computed: {
    activePriceTypeTitle() {
      const priceType = this.priceTypes.find(item => item.id == this.activePriceType);
      return priceType.i18n[this.activeLanguageCode].title;
    },

    salePriceTypeTitle() {
      const priceType = this.priceTypes.find(item => item.id == 6);
      return priceType.i18n[this.activeLanguageCode].title;
    },
    productsFilteredBy() {
      return this.$store.state.products.filteredBy;
    },
  },

  methods: {
    getDefaultState() {
      return defaultTableState;
    },

    loadData() {
      this.fetchMainData().then(data => {
        this.initMainData(data);
        this.activePriceType = this.priceTypes[0].id;
      });
    },

    // Загрузка списка
    fetchItems({ currentPage, perPage, sortBy, sortDesc }) {
      return new Promise(resolve => {
        new Core.requestHandler('get', this.makePageApiUrl(), {
          page: currentPage,
          perPage,
          sortBy,
          sortType: sortDesc ? 'desc' : 'asc',
          selected: this.selected,
          search: this.searchPhrase,
          priceType: this.activePriceType,
          type: this.type,
          filteredByCategories: this.productsFilteredBy.categories,
          filteredBySuppliers: this.productsFilteredBy.suppliers,
        }).success(response => {
          const data = response.data;
          this.totalRows = this.nanToNum(parseInt(data.totalRows));
          this.page = parseInt(data.page) || 1;
          this.perPage = this.nanToNum(parseInt(data.perPage));

          const items = data.products || [];

          resolve(items.map(item => new ProductsTableModel(item, this.languages)));
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

    setActivePriceType(id) {
      if (this.activePriceType === id) {
        return;
      }
      this.activePriceType = id;
      if (this.sortBy === 'price') {
        this.refreshTable();
      }
    },

    getItemPrice(item) {
      const price = item.prices.find(item => {
        return item.price_type_id == this.activePriceType && item.currency_code == 'RUB';
      }) || { formatted: '' };

      return price.formatted;
    },

    getSalePrice(item) {
      const price = item.prices.find(item => {
        return item.price_type_id == 6 && item.currency_code == 'RUB';
      }) || { formatted: '' };

      return price.formatted;
    },
  },
};
</script>
