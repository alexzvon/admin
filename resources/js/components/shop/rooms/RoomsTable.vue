<script>
//  import bModal from 'bootstrap-vue/es/components/modal/modal'

import LanguagePicker from '../../LanguagePicker';

import TablePage from '../../../mixins/TablePage';
import Sortable from '../../../mixins/Sortable';
import StatusChangeable from '../../../mixins/StatusChangeable';
import Translatable from '../../../mixins/Translatable';
import Toggle from '../../Toggle';

import RoomsTableModel from '../../../resources/shop/RoomsTableModel';

export default {
  name: 'RoomsTable',

  components: {
    //      bModal,
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
      tableItemsDataName: 'rooms',

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
      this.items = this.getSortedData(items.map(item => new RoomsTableModel(item, this.languages)));
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
            Комнаты
          </strong>
        </h1>

        <div v-if="userCan('rooms.create')" class="block-title-control">
          <language-picker :languages="languages" :active-language-code.sync="activeLanguageCode" />

          <span class="btn-separator-xs" />

          <router-link to="/shop/rooms/create" class="btn btn-sm btn-success active">
            <i class="fa fa-plus-circle" /> Создать
          </router-link>
        </div>
      </div>

      <div class="table-responsive">
        <div class="table-responsive">
          <table class="table table-middle table-center table-condensed table-bordered table-hover table-sortable table-attributes">
            <thead>
              <tr>
                <th v-if="userCan('rooms.edit')">
                  <span class="table-column-sort" />
                </th>

                <th>
                  <span class="table-column-id">ID</span>
                </th>

                <th>
                  <span class="table-column-image">
                    Изображение
                  </span>
                </th>

                <th style="width:100%">Название</th>

                <th v-if="userCan('rooms.edit')">
                  <span class="table-column-enabled">
                    Статус
                  </span>
                </th>

                <th v-if="userCan('rooms.delete')">
                  <span class="table-column-delete" />
                </th>
              </tr>
            </thead>

            <tbody class="ui-sortable">
              <tr v-for="item in items" :key="item.id" class="js-sort-item">
                <td v-if="userCan('rooms.edit')" class="table-sort-handler js-sort-handler">
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

                <td>
                  <span class="table-column-image">
                    <router-link :to="item.url">
                      <div class="product-preview-image">
                        <img :src="$helpers.getImagePath(item.image.src)" :srcset="`${item.image.srcset} 2x`">
                      </div>
                    </router-link>
                  </span>
                </td>

                <td style="width:100%">
                  <router-link :to="item.url">
                    {{ item.i18n[activeLanguageCode].title }}
                  </router-link>
                </td>

                <td v-if="userCan('rooms.edit')">
                  <span class="table-column-enabled table-remove-restore__restore">
                    <toggle :checked="item.enabled" @change="statusChange(item.id)" />
                  </span>
                </td>

                <td v-if="userCan('rooms.delete')">
                  <span class="table-column-delete">
                    <a class="btn btn-danger" @click="remove(item.id)">
                      <i class="fa fa-times" />
                    </a>
                  </span>
                </td>
              </tr>

              <tr v-if="! (items && items.length)">
                <td class="text-center" colspan="6">Список комнат пуст</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>

    <b-modal
      id="removeModal"
      ref="removeModal"
      title="Удаление комнаты"
      title-tag="h3"
      centered
      ok-title="Удалить"
      cancel-title="Отмена"
      hide-header-close
      @ok="removeConfirm"
    >

      Вы действительно хотите удалить комнату?
    </b-modal>
  </div>
</template>
