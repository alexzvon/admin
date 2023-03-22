<template>
  <div>
    <div class="block full">
      <div v-if="type === 'create'" class="block-title clearfix">
        <h1>Создание акционного товара</h1>

        <div class="block-title-control">
          <a class="btn btn-sm btn-default btn-alt" @click="redirectToTable">
            <i class="fa fa-arrow-left" />
          </a>

          <span class="btn-separator-xs" />

          <!--<language-picker-->
          <!--:languages="languages"-->
          <!--:activeLanguageCode.sync="activeLanguageCode"-->
          <!--:class="{'has-error': formTranslatesHasError()}" />-->

          <!--<span class="btn-separator-xs"></span>-->

          <a v-if="userCan('sale.create')" class="btn btn-sm btn-success active" @click="save">
            <i class="fa fa-plus-circle" /> Создать
          </a>
        </div>
      </div>

      <div v-if="type === 'edit'" class="block-title">
        <h1>
          <strong>
            Редактирование акционного товара #{{ this.id }}
          </strong>
        </h1>

        <div class="block-title-control">
          <a class="btn btn-sm btn-default btn-alt" @click="redirectToTable">
            <i class="fa fa-arrow-left" />
          </a>

          <a
            v-if="userCan('sale.edit')"
            class="btn btn-sm btn-primary active"
            :disabled="saveButtonDisabled"
            @click="save"
          >
            <i class="fa fa-floppy-o" /> Сохранить
          </a>

          <span class="btn-separator-xs" />

          <language-picker
            :languages="languages"
            :active-language-code.sync="activeLanguageCode"
            :class="{'has-error': formTranslatesHasError()}"
          />

          <span class="btn-separator-xs" />

          <a v-if="userCan('sale.edit')" class="btn btn-sm btn-primary active" @click="save">
            <i class="fa fa-floppy-o" /> Сохранить
          </a>

          <a v-if="userCan('sale.delete')" class="btn btn-sm btn-danger active" @click="remove">
            Удалить
          </a>
        </div>
      </div>

      <div v-if="sale" class="row">

        <div class="col-lg-12">
          <div class="block">
            <div class="block-title">
              <h2>
                <i class="fa fa-pencil" /> <strong>Основная</strong> информация
              </h2>
            </div>

            <div class="form-horizontal form-bordered">

              <div :class="`form-group${formErrors.has('related') ? ' has-error' : ''}`">
                <label class="col-md-3 control-label" for="date_start">
                  Акционный товар
                </label>

                <div class="col-md-9">
                  <div class="input-group">
                    <ajax-multiselect
                      ref="productSearch"
                      :url="productSearchUrl()"
                      :languages="languages"
                      :active-language-code="activeLanguageCode"
                      :selected.sync="sale.product_id"
                      :options="sale.product"
                      :link-url-maker="relatedLinkMaker"
                      :params="{minimumResultsForSearch: -1, allowClear: false, closeOnSelect: false}"
                      @update:selected="setSelected"
                    />
                  </div>

                  <span v-show="formErrors.has('related')" class="help-block">
                    {{ formErrors.first('related') }}
                  </span>
                </div>
              </div>

              <div :class="`form-group${formErrors.has('date_start') ? ' has-error' : ''}`">
                <label class="col-md-3 control-label" for="date_start">
                  Дата начала
                </label>

                <div class="col-md-9">
                  <date-picker
                    id="date_start"
                    ref="dateStart"
                    v-model="sale.date_start"
                    name="date_start"
                    :config="dateStartConfig"
                    class="date-picker"
                    @dp-show="datePickerShow('date_start')"
                    @dp-change="dateChanged('date_start')"
                  />

                  <span v-show="formErrors.has('date_start')" class="help-block">
                    {{ formErrors.first('date_start') }}
                  </span>
                </div>
              </div>

              <div :class="`form-group${formErrors.has('date_finish') ? ' has-error' : ''}`">
                <label class="col-md-3 control-label" for="date_finish">
                  Дата завершения
                </label>

                <div class="col-md-9">
                  <date-picker
                    id="date_finish"
                    ref="dateStart"
                    v-model="sale.date_finish"
                    name="date_finish"
                    :config="dateFinishConfig"
                    class="date-picker"
                    @dp-show="datePickerShow('date_finish')"
                    @dp-change="dateChanged('date_finish')"
                  />

                  <span v-show="formErrors.has('date_finish')" class="help-block">
                    {{ formErrors.first('date_finish') }}
                  </span>
                </div>
              </div>

              <div :class="`form-group${formErrors.has('enabled') ? ' has-error' : ''}`">
                <label class="col-md-3 control-label">
                  Опубликовано
                </label>

                <div class="col-md-9">
                  <label class="switch switch-primary">
                    <input v-model="sale.enabled" type="checkbox">
                    <span />
                  </label>

                  <span v-show="formErrors.has('enabled')" class="help-block">
                    {{ formErrors.first('enabled') }}
                  </span>
                </div>
              </div>

              <div v-if="sale.created_at" class="form-group">
                <label class="col-md-3 control-label">
                  Дата создания
                </label>

                <div class="col-md-9">
                  <p class="form-control-static">
                    {{ sale.created_at }}
                  </p>
                </div>
              </div>

              <div v-if="sale.updated_at" class="form-group">
                <label class="col-md-3 control-label">
                  Последнее изменение
                </label>

                <div class="col-md-9">
                  <p class="form-control-static">
                    {{ sale.updated_at }}
                  </p>
                </div>
              </div>

            </div>
          </div>
        </div>

        <div class="col-lg-12">
          <loading v-if="this.sale.product_id" :loading="loading">
            <prices-table
              v-if="sale.product_id"
              :prices.sync="sale.prices"
              :available-price-types="$root.config('shop.price_types.sale')"
              :active-language-code="activeLanguageCode"
              :errors="formErrors"
            />
          </loading>
        </div>
      </div>
    </div>

    <b-modal
      id="validationModal"
      ref="validationModal"
      title="Ошибка валидации"
      title-tag="h3"
      centered
      ok-title="Ок"
      ok-only
      hide-header-close
    >
      Проверьте правильность заполнения формы!
    </b-modal>

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
      Вы действительно хотите акцию?
    </b-modal>
  </div>
</template>

<script>
//  import bModal from 'bootstrap-vue/es/components/modal/modal'

import EntityPage from '../../../mixins/EntityPage';
import Translatable from '../../../mixins/Translatable';
import DatePickerRangeMixin from '../../../mixins/DatePicker/DatePickerRange';
import LanguagePicker from '../../LanguagePicker';
import TreeSelectTranslatable from '../../TreeSelectTranslatable';
import AjaxMultiselect from '../../AjaxMultiselect';
import PricesTable from '../PricesTable';
import Loading from '../../Loading';
import Core from '../../../core';
import PricesTableModel from '../../../resources/shop/PricesTableModel';
import SaleEditModel from '../../../resources/shop/Sale/SaleEditModel';

export default {
  name: 'SaleEdit',

  components: {
    // bModal,
    LanguagePicker,
    TreeSelectTranslatable,
    AjaxMultiselect,
    PricesTable,
    Loading,
  },

  mixins: [
    EntityPage,
    Translatable,
    DatePickerRangeMixin,
  ],

  props: [
    'id',
  ],

  data() {
    return {
      loading: false,
      entityName: 'sale',
      sale: null,
      saveButtonDisabled: false,

      usedMainData: [
        'languages',
      ],

      reloadDataOnSave: true,

      dateStartConfig: {
        ... this.getBaseDatePickerConfig(),
      },

      dateFinishConfig: {
        ... this.getBaseDatePickerConfig(),
      },
    };
  },

  methods: {
    setSelected(selected) {
      if (selected) {
        this.updatePrice();
      }
    },
    /**
       * Инициализация модели данных.
       */
    initEntity(data) {
      this.setEntityData(new SaleEditModel(data, this.languages));
      this.dateFinishConfig = this.getFinishDatePickerConfig();
    },

    productSearchUrl() {
      return '/api' + this.getPathToTable() + '/search';
    },

    relatedLinkMaker(option) {
      const linkEl = document.createElement('a');

      linkEl.setAttribute('href', '/shop/products/' + option.id);
      linkEl.setAttribute('target', '_blank');

      linkEl.innerHTML = option.text;

      return linkEl;
    },

    updatePrice() {
      this.loading = true;

      return new Core.requestHandler('get', '/api' + this.getPathToTable() + '/price/' + this.sale.product_id)
        .success(response => {
          this.sale.prices = new PricesTableModel(response.data.prices);

          this.loading = false;
        })
        .start();
    },

  },
};
</script>

