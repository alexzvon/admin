<template>
  <div>
    <div class="block full">
      <div class="block-title clearfix">

        <h1>Категории</h1>

        <div class="block-title-control">

          <a class="btn btn-default btn-sm btn-alt" @click="expandAll">
            <i class="fa fa-expand" /> Раскрыть все
          </a>

          <a class="btn btn-default btn-sm btn-alt" @click="compressAll">
            <i class="fa fa-compress" /> Свернуть все
          </a>

          <span class="btn-separator-xs" />

          <router-link v-if="userCan('shop.categories.create')" to="/shop/categories/create" class="btn btn-sm btn-success active">
            <i class="fa fa-plus-circle" /> Создать
          </router-link>
        </div>
      </div>

      <div class="table-responsive">

        <div class="table table-middle table-center table-condensed table-bordered table-hover table-sortable">
          <div class="table-head">
            <div class="table-row">
              <div v-if="userCan('shop.categories.edit')" class="table-cell table-cell-column-sort" />

              <div class="table-cell table-cell-column-id">
                ID
              </div>

              <div class="table-cell table-cell-column-image">
                Изображение
              </div>

              <div class="table-cell">
                Название
              </div>

              <div class="table-cell table-cell-column-slug">
                Slug
              </div>

              <div class="table-cell table-cell-column-num">
                Товаров
              </div>

              <div v-if="userCan('shop.categories.edit')" class="table-cell table-cell-column-enabled">
                Статус
              </div>

              <div v-if="userCan('shop.categories.delete')" class="table-cell table-cell-column-delete" />
            </div>
          </div>

          <categories-table-tree
            v-if="items && items.length"
            :tree="items"
            :level="0"
            :status-change="statusChange"
            :remove="remove"
            :active-language-code="activeLanguageCode"
          />

          <div v-if="!items.length" class="table-group">
            <div class="table-row">
              <div class="table-cell table-cell-empty text-center">
                Список категорий пуст.
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <b-modal
      id="removeModal"
      ref="removeModal"
      title="Удаление категории"
      title-tag="h3"
      centered
      ok-title="Удалить"
      cancel-title="Отмена"
      hide-header-close
      @ok="removeConfirm"
    >

      Вы действительно хотите удалить категорию?
    </b-modal>
  </div>
</template>

<script>
import Core from '../../../core';

import CategoriesTableTree from './CategoriesTableTree';
import LanguagePicker from '../../LanguagePicker';

import TablePage from '../../../mixins/TablePage';
import Sortable from '../../../mixins/Sortable';
import StatusChangeable from '../../../mixins/StatusChangeable';
import Translatable from '../../../mixins/Translatable';

import CategoriesTableModel from '../../../resources/shop/CategoriesTableModel';

/*
    todo: Разобраться с сортировкой. Пересмотреть формат таблицы.
   */
export default {
  name: 'CategoriesTable',

  components: {
    CategoriesTableTree,
    LanguagePicker,
  },

  mixins: [
    StatusChangeable,
    Sortable,
    TablePage,
    Translatable,
  ],

  data() {
    return {
      tableItemsDataName: 'categories',

      usedMainData: [
        'languages',
      ],
    };
  },

  watch: {
    items: 'refreshSort',
  },

  methods: {
    getSortParams() {
      return {
        ... this.getDefaultSortParams(),
        items: '> .js-sort-item',
      };
    },

    initItems(items = []) {
      this.items = this.getSortedData(items.map(item => new CategoriesTableModel(item, this.languages)));
    },

    expandAll() {
      Core.events.trigger('categories-expand-all');
    },

    compressAll() {
      Core.events.trigger('categories-compress-all');
    },
  },
};
</script>

