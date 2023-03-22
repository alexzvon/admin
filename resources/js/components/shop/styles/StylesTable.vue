<template>
    <div>
        <div class="block full">
            <div class="block-title clearfix">
                <h1>
                    <strong>Стили</strong>
                </h1>

                <div class="block-title-control" v-if="isAllowCreate">
                    <!--<language-picker :languages="languages" :activeLanguageCode.sync="activeLanguageCode" />-->
                    <!--<span class="btn-separator-xs"></span>-->

                    <router-link to="/shop/styles/create" class="btn btn-sm btn-success active">
                        <i class="fa fa-plus-circle"></i> Создать
                    </router-link>
                </div>
            </div>

            <div class="table-responsive">
                <div class="table-responsive">
                    <table class="table table-middle table-center table-condensed table-bordered table-hover table-sortable">
                        <thead>
                            <tr>
                                <th v-if="userCan('styles.edit')">
                                    <span class="table-column-sort"></span>
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

                                <th v-if="userCan('styles.edit')">
                                    <span class="table-column-enabled">
                                        Статус
                                    </span>
                                </th>

                                <th v-if="userCan('styles.delete')">
                                    <span class="table-column-delete"></span>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="ui-sortable">
                            <tr v-for="item in items" :key="item.id" class="js-sort-item">
                                <td v-if="userCan('styles.edit')" class="table-sort-handler js-sort-handler">
                                    <span>
                                        <input type="hidden" name="ids" :value="item.id">
                                    </span>
                                </td>

                                <td>
                                    <span class="table-column-id">
                                        <router-link v-bind:to="item.url">
                                            {{ item.id }}
                                        </router-link>
                                    </span>
                                </td>

                                <td>
                                    <span class="table-column-image">
                                        <router-link :to="item.url">
                                            <div class="product-preview-image">
                                                <img
                                                  :src="$helpers.getImagePath(item.image.src)"
                                                  :srcset="`${item.image.srcset} 2x`"
                                                >
                                            </div>
                                        </router-link>
                                    </span>
                                </td>

                                <td style="width:100%">
                                    <router-link v-bind:to="item.url">
                                        {{ item.i18n[activeLanguageCode].title }}
                                    </router-link>
                                </td>

                                <td v-if="userCan('styles.edit')">
                                    <span class="table-column-enabled table-remove-restore__restore">
                                        <toggle @change="statusChange(item.id)" :checked="item.enabled"></toggle>
                                    </span>
                                </td>

                                <td v-if="userCan('styles.delete')">
                                    <span class="table-column-delete">
                                        <a class="btn btn-danger" @click="remove(item.id)">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </span>
                                </td>
                            </tr>

                            <tr v-if="! (items && items.length)">
                                <td class="text-center" colspan="6">Список стилей пуст</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <b-modal
          id="removeModal"
          ref="removeModal"
          title="Удаление стиля"
          title-tag="h3"
          centered
          ok-title="Удалить"
          cancel-title="Отмена"
          hide-header-close
          @ok="removeConfirm">

            Вы действительно хотите удалить стиль?
        </b-modal>
    </div>
</template>


<script>
//  import bModal from 'bootstrap-vue/es/components/modal/modal'

  import LanguagePicker from '../../LanguagePicker'

  import TablePage from '../../../mixins/TablePage'
  import Sortable from '../../../mixins/Sortable'
  import StatusChangeable from '../../../mixins/StatusChangeable'
  import Translatable from '../../../mixins/Translatable'
  import Toggle from '../../Toggle'
  import Core from "../../../core";

  import StylesTableModel from '../../../resources/shop/StylesTableModel'

  export default {
//  module.exports = {
    name: 'styles-table',

    mixins: [
      Sortable,
      StatusChangeable,
      TablePage,
      Translatable
    ],

    components : {
  //    bModal,
      Toggle,
      LanguagePicker
    },

    data() {
      return {
        tableItemsDataName: 'styles',

        usedMainData: [
          'languages',
        ],
      }
    },
      computed: {
          isAllowCreate() {
              return Core.user.can('styles.create')
          },
      },

    methods: {
      initItems (items = []) {
        this.items = this.getSortedData(items.map(item => new StylesTableModel(item, this.languages)))
      },
    },
  }
</script>

