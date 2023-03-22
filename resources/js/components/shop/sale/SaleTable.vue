<template>
  <div>
    <div class="block full">
      <div class="block-title clearfix">
        <h1>
          <strong>
            Акционные товары
          </strong>
        </h1>

        <div v-if="userCan('sale.create')" class="block-title-control">
          <!--<language-picker :languages="languages" :activeLanguageCode.sync="activeLanguageCode"/>-->
          <!--<span class="btn-separator-xs"></span>-->
          <router-link to="/shop/sale/create" class="btn btn-sm btn-success active">
            <i class="fa fa-plus-circle" /> Создать
          </router-link>
        </div>
      </div>

      <div class="table-responsive">
        <div class="table-responsive">
          <table class="table table-middle table-center table-condensed table-bordered table-hover table-sortable table-attributes">
            <thead>
              <tr>
                <th v-if="userCan('sale.edit')">
                  <span class="table-column-sort" />
                </th>

                <th>
                  <span class="table-column-id">ID</span>
                </th>

                <th style="width:100%">Название</th>

                <th>
                  <span class="table-column-id">Цена</span>
                </th>

                <th v-if="userCan('sale.edit')">
                  <span class="table-column-enabled">
                    Статус
                  </span>
                </th>

                <th v-if="userCan('sale.delete')">
                  <span class="table-column-delete" />
                </th>
              </tr>
            </thead>

            <tbody class="ui-sortable">
              <tr v-for="item in items" :key="item.id" class="js-sort-item">
                <td v-if="userCan('sale.edit')" class="table-sort-handler js-sort-handler">
                  <span>
                    <input type="hidden" name="ids" :value="item.id">
                  </span>
                </td>

                <td>
                  <span class="table-column-id">
                    <router-link :to="item.url">
                      {{ item.id }}
                    </router-link>
                  </span>
                </td>

                <td style="width:100%">
                  <router-link :to="item.url">
                    {{ item.product.i18n[activeLanguageCode].title }}
                  </router-link>
                </td>

                <td>
                  {{ getItemPrice(item) }}
                </td>

                <td v-if="userCan('sale.edit')">
                  <span class="table-column-enabled table-remove-restore__restore">
                    <toggle :checked="item.enabled" @change="statusChange(item.id)" />
                  </span>
                </td>

                <td v-if="userCan('sale.delete')">
                  <span class="table-column-delete">
                    <a class="btn btn-danger" @click="remove(item.id)">
                      <i class="fa fa-times" />
                    </a>
                  </span>
                </td>
              </tr>

              <tr v-if="! (items && items.length)">
                <td class="text-center" colspan="6">Список акционных товаров пуст</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>

    <b-modal
      id="removeModal"
      ref="removeModal"
      title="Удаление акции"
      title-tag="h3"
      centered
      ok-title="Удалить"
      cancel-title="Отмена"
      hide-header-close
      @ok="removeConfirm"
    >
      Вы действительно хотите удалить акцию?
    </b-modal>

  </div>
</template>

<script>
//    import bModal from "bootstrap-vue/es/components/modal/modal";

import LanguagePicker from '../../LanguagePicker';

import TablePage from '../../../mixins/TablePage';
import Sortable from '../../../mixins/Sortable';
import StatusChangeable from '../../../mixins/StatusChangeable';
import Translatable from '../../../mixins/Translatable';
import Toggle from '../../Toggle';

import SaleTableModel from '../../../resources/shop/Sale/SaleTableModel';

export default {
  name: 'SaleTable',

  components: {
    // bModal,
    Toggle,
    LanguagePicker,
  },

  mixins: [
    Sortable,
    StatusChangeable,
    TablePage,
    Translatable,
  ],

  data() {
    return {
      tableItemsDataName: 'sales',

      usedMainData: [
        'languages',
      ],
    };
  },

  watch: {
    items: 'refreshSort',
  },

  methods: {
    initItems(items = []) {
      this.items = this.getSortedData(items.map(item => new SaleTableModel(item, this.languages)));
    },

    getItemPrice(item) {
      const price = item.prices.find(item => {
        return item.currency_code === 'RUB';
      }) || { formatted: '' };

      return price.formatted;
    },
  },
};
</script>
