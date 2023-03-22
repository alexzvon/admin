<script>
import TablePage from '../../../mixins/TablePage';
import Sortable from '../../../mixins/Sortable';
import StatusChangeable from '../../../mixins/StatusChangeable';
import Translatable from '../../../mixins/Translatable';

import Toggle from '../../Toggle';
import LanguagePicker from '../../LanguagePicker';

//  import bModal from 'bootstrap-vue/es/components/modal/modal'

import AttributesTableModel from '../../../resources/shop/AttributesTableModel';

export default {
  name: 'AttributesTable',

  components: {
    Toggle,
    LanguagePicker,
    //      bModal
  },

  mixins: [
    Sortable,
    StatusChangeable,
    TablePage,
    Translatable,
  ],

  data() {
    return {
      tableItemsDataName: 'attributes',

      usedMainData: [
        'languages',
      ],
    };
  },

  methods: {
    initItems(items = []) {
      this.items = this.getSortedData(items.map(item => new AttributesTableModel(item, this.languages)));
    },
  },
};
</script>

<template>
  <div>
    <div class="block full">
      <div class="block-title clearfix">
        <h1><strong>Аттрибуты</strong></h1>

        <div class="block-title-control">
          <language-picker :languages="languages" :active-language-code.sync="activeLanguageCode" />

          <span v-if="languages.length > 1" class="btn-separator-xs" />

          <router-link v-if="userCan('shop.attributes.create')" to="/shop/attributes/create" class="btn btn-sm btn-success active">
            <i class="fa fa-plus-circle" /> Создать
          </router-link>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-middle table-center table-condensed table-bordered table-hover table-sortable table-attributes">
          <thead>
            <tr>
              <th v-if="userCan('shop.attributes.edit')">
                <span class="table-column-sort" />
              </th>

              <th>
                <span class="table-column-id">ID</span>
              </th>

              <th style="width:100%">Название</th>

              <th v-if="userCan('shop.attributes.edit')">
                <span class="table-column-enabled">
                  Статус
                </span>
              </th>

              <th v-if="userCan('shop.attributes.delete')">
                <span class="table-column-delete" />
              </th>
            </tr>
          </thead>

          <tbody class="ui-sortable">
            <tr v-for="attribute in items" :key="attribute.id" class="js-sort-item">
              <td v-if="userCan('shop.attributes.edit')" class="table-sort-handler js-sort-handler">
                <span>
                  <input type="hidden" name="ids" :value="attribute.id">
                </span>
              </td>

              <td>
                <span class="table-column-id">
                  <router-link :to="attribute.url">
                    {{ attribute.id }}
                  </router-link>
                </span>
              </td>

              <td style="width:100%">
                <router-link :to="attribute.url">
                  {{ attribute.i18n[activeLanguageCode].title }}
                </router-link>
              </td>

              <td v-if="userCan('shop.attributes.edit')">
                <span class="table-column-enabled table-remove-restore__restore">
                  <toggle :checked="attribute.enabled" @change="statusChange(attribute.id)" />
                </span>
              </td>

              <td v-if="userCan('shop.attributes.delete')">
                <span class="table-column-delete">
                  <a class="btn btn-danger" @click="remove(attribute.id)">
                    <i class="fa fa-times" />
                  </a>
                </span>
              </td>
            </tr>

            <tr v-if="! (items && items.length)">
              <td class="text-center" colspan="5">Список аттрибутов пуст</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <b-modal
      id="removeModal"
      ref="removeModal"
      title="Удаление аттрибута"
      title-tag="h3"
      centered
      ok-title="Удалить"
      cancel-title="Отмена"
      hide-header-close
      @ok="removeConfirm"
    >

      Вы действительно хотите удалить аттрибут?
    </b-modal>
  </div>
</template>
