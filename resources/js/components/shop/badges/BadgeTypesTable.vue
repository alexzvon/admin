<script>
//  import bModal from 'bootstrap-vue/es/components/modal/modal'

import LanguagePicker from '../../LanguagePicker';

import TablePage from '../../../mixins/TablePage';
import Translatable from '../../../mixins/Translatable';
import Sortable from '../../../mixins/Sortable';
import StatusChangeable from '../../../mixins/StatusChangeable';
import Toggle from '../../Toggle';
import BadgePreview from './BadgePreview';

import BadgeTypesTableModel from '../../../resources/shop/badge/BadgeTypesTableModel';

export default {
  name: 'BadgeTypesTable',

  components: {
    //      bModal,
    Toggle,
    LanguagePicker,
    BadgePreview,
  },

  mixins: [
    Sortable,
    StatusChangeable,
    TablePage,
    Translatable,
  ],

  data() {
    return {
      tableItemsDataName: 'badge-types',

      usedMainData: [
        'languages',
      ],
    };
  },

  methods: {
    initItems(items = []) {
      this.items = this.getSortedData(items.map(item => new BadgeTypesTableModel(item, this.languages)));
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
            Бейджи
          </strong>
        </h1>

        <div v-if="userCan('badge-types.create')" class="block-title-control">
          <language-picker :languages="languages" :active-language-code.sync="activeLanguageCode" />

          <span class="btn-separator-xs" />

          <router-link to="/shop/badge-types/create" class="btn btn-sm btn-success active">
            <i class="fa fa-plus-circle" /> Создать
          </router-link>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-middle table-center table-condensed table-bordered table-hover table-sortable">
          <thead>
            <tr>
              <th v-if="userCan('badge-types.edit')">
                <span class="table-column-sort" />
              </th>

              <th>
                <span class="table-column-id">
                  Превью
                </span>
              </th>

              <th style="width: 100%">
                Название
              </th>

              <th v-if="userCan('badge-types.delete')">
                <span class="table-column-delete" />
              </th>
            </tr>
          </thead>

          <tbody class="ui-sortable">
            <template v-if="items && items.length">
              <tr v-for="badgeType in items" :key="badgeType.id" class="js-sort-item">
                <td v-if="userCan('badge-types.edit')" class="table-sort-handler js-sort-handler">
                  <span>
                    <input type="hidden" name="ids" :value="badgeType.id">
                  </span>
                </td>

                <td class="text-center">
                  <span class="table-column-id">
                    <badge-preview
                      :color="badgeType.color"
                      :icon="badgeType.icon"
                      :title="badgeType.i18n[activeLanguageCode].title"
                      :text="badgeType.has_value ? '-20%' : undefined"
                    />
                  </span>
                </td>

                <td style="width: 100%">
                  <router-link :to="badgeType.url">{{ badgeType.i18n[activeLanguageCode].title }}</router-link>
                </td>

                <td v-if="userCan('badges.delete')">
                  <span class="table-column-delete">
                    <a class="btn btn-danger" @click="remove(badgeType.id)">
                      <i class="fa fa-times" />
                    </a>
                  </span>
                </td>
              </tr>
            </template>

            <tr v-if="! (items && items.length)">
              <td class="text-center" colspan="4">
                Список бейджев пуст.
              </td>
            </tr>
          </tbody>
        </table>

      </div>
    </div>

    <b-modal
      id="removeModal"
      ref="removeModal"
      title="Удаление бейджа"
      title-tag="h3"
      centered
      ok-title="Удалить"
      cancel-title="Отмена"
      hide-header-close
      @ok="removeConfirm"
    >

      Вы действительно хотите удалить бейдж?
    </b-modal>
  </div>
</template>
