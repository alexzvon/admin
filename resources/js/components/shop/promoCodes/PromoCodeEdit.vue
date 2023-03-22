<script>
//  import bModal from 'bootstrap-vue/es/components/modal/modal'

import CKEditor from '../../CKEditor';
import TreeSelect from '../../TreeSelect';

import EntityPage from '../../../mixins/EntityPage';
import Translatable from '../../../mixins/Translatable';
import DatePickerRangeMixin from '../../../mixins/DatePicker/DatePickerRange';
import LanguagePicker from '../../LanguagePicker';

import PromoCodeModel from '../../../resources/shop/promo/PromoCodeModel';
import moment from 'moment';
import number from '../../../directives/number';
import Core from '../../../core';

function makeCondition(key) {
  const conditionFields = {};

  switch (key) {
    case 'min_summ':
      conditionFields.value = 0;
      conditionFields.currency_code = 'RUB';
      break;

    case 'product_expensive':
      conditionFields.value = 0;
      conditionFields.currency_code = 'RUB';
      break;

    case 'products_quantity':
      conditionFields.value = 0;
      break;

    case 'first_order':
      conditionFields.value = false;
      break;
  }

  return conditionFields;
}

export default {
  name: 'SupplierEdit',

  mixins: [
    EntityPage,
    Translatable,
    DatePickerRangeMixin,
  ],

  directives: {
    ... number,
  },

  components: {
    'ckeditor': CKEditor,
    //      bModal,
    TreeSelect,
    LanguagePicker,
  },

  props: [
    'id',
  ],

  data() {
    return {
      entityName: 'promo-code',

      promoCode: null,

      promoType: null,

      conditions: {
        'min_summ': Core.translate('shop.promo.conditions.types.min_summ'),
        'product_expensive': Core.translate('shop.promo.conditions.types.product_expensive'),
        'products_quantity': Core.translate('shop.promo.conditions.types.products_quantity'),
        'first_order': Core.translate('shop.promo.conditions.types.first_order'),
      },

      savedPromoTypeValues: {
        percent: {
          percent: this.$root.config('shop.promo.discount.percent.max_percent'),
          amount: 0,
          currency_code: null,
        },

        amount: {
          percent: this.$root.config('shop.promo.discount.amount.max_percent'),
          amount: 0,
          currency_code: 'RUB',
        },
      },

      selectedCondition: null,

      savedQuantities: {
        quantity: 1,
        quantity_per_user: 1,
      },

      dateStartConfig: {
        ... this.getBaseDatePickerConfig(),
      },

      dateFinishConfig: {
        ... this.getBaseDatePickerConfig(),
      },

      usedMainData: [
        'currencies',
        'languages',
      ],
    };
  },

  computed: {
    currenciesToSelect() {
      return this.currencies.map(currency => ({
        id: currency.code,
        title: currency.code,
      }));
    },

    conditionsToSelect() {
      return Object.keys(this.conditions).reduce((acc, key) => {
        if (!(key in this.promoCode.conditions)) {
          acc.push({
            id: key,
            title: this.conditions[key],
          });
        }

        return acc;
      }, []);
    },

    hasNotSelectedConditions() {
      return this.conditionsToSelect.length !== 0;
    },

    promoTypeSelectOptions() {
      return [
        {
          id: 'percent',
          title: 'процентах',
        },

        {
          id: 'amount',
          title: 'валюте',
        },
      ];
    },
  },

  methods: {
    initEntity(data) {
      this.setEntityData(new PromoCodeModel(data, this.languages));
      this.dateFinishConfig = this.getFinishDatePickerConfig();
    },

    setEntityData() {
      EntityPage.methods.setEntityData.apply(this, arguments);

      this.detectPromoType();
    },

    detectPromoType() {
      if (this.type === 'create') {
        this.promoType = 'percent';

        for (const key in this.savedPromoTypeValues[this.promoType]) {
          this.promoCode[key] = this.savedPromoTypeValues[this.promoType][key];
        }
      } else {
        if (this.promoCode.amount) {
          this.promoType = 'amount';
        } else {
          this.promoType = 'percent';
        }
      }
    },

    setPromoType(type, isInit = false) {
      for (const key in this.savedPromoTypeValues[this.promoType]) {
        this.savedPromoTypeValues[this.promoType][key] = this.promoCode[key];
      }

      this.promoType = type;

      for (const key in this.savedPromoTypeValues[type]) {
        this.promoCode[key] = this.savedPromoTypeValues[type][key];
      }
    },

    setUnlimited(key) {
      if (this.promoCode[key] < 0) {
        this.promoCode[key] = this.savedQuantities[key];
      } else {
        this.savedQuantities[key] = this.promoCode[key];
        this.promoCode[key] = -1;
      }
    },

    selectCondition(key) {
      this.selectedCondition = key;
    },

    addCondition() {
      if (this.selectedCondition && !(this.selectedCondition in this.promoCode.conditions)) {
        this.promoCode.conditions = {
          ... this.promoCode.conditions,
          [this.selectedCondition]: makeCondition(this.selectedCondition),
        };
      }

      this.selectedCondition = null;
    },

    removeCondition(key) {
      this.promoCode.conditions = Object.keys(this.promoCode.conditions).reduce((acc, conditionKey) => {
        if (conditionKey !== key) {
          acc[conditionKey] = this.promoCode.conditions[key];
        }

        return acc;
      }, {});
    },

    inputPromo(e) {
      this.promoCode.name = e.target.value.toUpperCase();
    },
  },
};

// id: '',
// date_start: '',
// date_finish: '',
// quantity: '',
// once: '',
// user_related: '',
// percent: '',
// amount: '',

</script>

<template>
  <div>
    <div class="block full">
      <div v-if="type === 'create'" class="block-title clearfix">
        <h1>
          <strong>
            Создание промокода
          </strong>
        </h1>

        <div class="block-title-control">
          <a class="btn btn-sm btn-default btn-alt" @click="redirectToTable">
            <i class="fa fa-arrow-left" />
          </a>

          <span class="btn-separator-xs" />

          <language-picker
            :languages="languages"
            :active-language-code.sync="activeLanguageCode"
            :class="{'has-error': formTranslatesHasError()}"
          />

          <span class="btn-separator-xs" />

          <a v-if="userCan('promo-codes.create')" class="btn btn-sm btn-success active" @click="save">
            <i class="fa fa-plus-circle" /> Создать
          </a>
        </div>
      </div>

      <div v-if="type === 'edit'" class="block-title clearfix">
        <h1>
          <strong>
            Редактирование промокода #{{ this.id }}
          </strong>
        </h1>

        <div class="block-title-control">
          <a class="btn btn-sm btn-default btn-alt" @click="redirectToTable">
            <i class="fa fa-arrow-left" />
          </a>

          <span class="btn-separator-xs" />

          <language-picker
            :languages="languages"
            :active-language-code.sync="activeLanguageCode"
            :class="{'has-error': formTranslatesHasError()}"
          />

          <span class="btn-separator-xs" />

          <a v-if="userCan('promo-codes.edit')" class="btn btn-sm btn-primary active" @click="save">
            <i class="fa fa-floppy-o" /> Сохранить
          </a>

          <a v-if="userCan('promo-codes.delete')" class="btn btn-sm btn-danger active" @click="remove">
            Удалить
          </a>
        </div>
      </div>

      <div v-if="promoCode" class="row">
        <div class="col-lg-6">
          <div class="block">
            <div class="block-title">
              <h2>
                <i class="fa fa-pencil" /> <strong>Основная</strong> информация
              </h2>
            </div>

            <div class="form-horizontal form-bordered">
              <div :class="`form-group${formErrors.has('name') ? ' has-error' : ''}`">
                <label class="col-md-3 control-label" for="name">
                  Код <span class="text-danger">*</span>
                </label>

                <div class="col-md-9">
                  <input
                    id="name"
                    v-validate="'required|min:3|max:255'"
                    type="text"
                    class="form-control"
                    name="name"
                    :value="promoCode.name"
                    @input="inputPromo"
                  >

                  <span v-show="formErrors.has('name')" class="help-block">
                    {{ formErrors.first('name') }}
                  </span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-3 control-label" for="promo-type-select">
                  Скидка в <span class="text-danger">*</span>
                </label>

                <div class="col-md-9">
                  <tree-select
                    id="promo-type-select"
                    name="promo-type-select"
                    :options="promoTypeSelectOptions"
                    :selected="promoType"
                    :params="{minimumResultsForSearch: -1, allowClear: false}"
                    placeholder="Выберите тип"
                    @update:selected="setPromoType"
                  />
                </div>
              </div>

              <div :class="`form-group${formErrors.has('enabled') ? ' has-error' : ''}`">
                <label class="col-md-3 control-label">
                  Задействован
                </label>

                <div class="col-md-9">
                  <label class="switch switch-primary">
                    <input v-model="promoCode.enabled" type="checkbox">
                    <span />
                  </label>

                  <span v-show="formErrors.has('enabled')" class="help-block">
                    {{ formErrors.first('enabled') }}
                  </span>
                </div>
              </div>

              <div v-if="promoCode.uses_count" class="form-group">
                <label class="col-md-3 control-label">
                  Количество использований
                </label>

                <div class="col-md-9">
                  <p class="form-control-static">
                    {{ promoCode.uses_count }}
                  </p>
                </div>
              </div>

              <div v-if="promoCode.created_at" class="form-group">
                <label class="col-md-3 control-label">
                  Дата создания
                </label>

                <div class="col-md-9">
                  <p class="form-control-static">
                    {{ promoCode.created_at }}
                  </p>
                </div>
              </div>

              <div v-if="promoCode.updated_at" class="form-group">
                <label class="col-md-3 control-label">
                  Последнее изменение
                </label>

                <div class="col-md-9">
                  <p class="form-control-static">
                    {{ promoCode.updated_at }}
                  </p>
                </div>
              </div>

            </div>
          </div>

          <!-- Скидка -->

          <div class="block">
            <div class="block-title">
              <h2>
                <i class="fa fa-percent" /> <strong>Скидка</strong>
              </h2>
            </div>

            <div v-if="promoType === 'percent'" class="form-horizontal form-bordered">

              <div :class="`form-group${formErrors.has('percent') ? ' has-error' : ''}`">
                <label class="col-md-3 control-label" for="percent">
                  Процент скидки <span class="text-danger">*</span>
                </label>

                <div class="col-md-9">
                  <input
                    id="percent"
                    v-model="promoCode.percent"
                    v-number
                    v-validate="'required|max_value:' + $root.config('shop.promo.discount.percent.max_percent')"
                    type="number"
                    class="form-control"
                    name="percent"
                  >

                  <span v-show="formErrors.has('percent')" class="help-block">
                    {{ formErrors.first('percent') }}
                  </span>
                </div>
              </div>

            </div>

            <div v-if="promoType === 'amount'" class="form-horizontal form-bordered">

              <div :class="`form-group${formErrors.has('amount') ? ' has-error' : ''}`">
                <label class="col-md-3 control-label" for="amount">
                  Сумма <span class="text-danger">*</span>
                </label>

                <div class="col-md-9">
                  <div class="input-group price-input-group">
                    <input
                      id="amount"
                      v-model="promoCode.amount"
                      v-number
                      v-validate="'required|max_value:2147483647'"
                      type="number"
                      class="form-control"
                      name="amount"
                    >

                    <div class="input-group-addon">
                      <tree-select
                        name="currency_code"
                        :options="currenciesToSelect"
                        :selected.sync="promoCode.currency_code"
                        :params="{minimumResultsForSearch: -1, allowClear: false}"
                      />
                    </div>
                  </div>
                </div>
              </div>

              <div :class="`form-group${formErrors.has('percent') ? ' has-error' : ''}`">
                <label class="col-md-3 control-label" for="percent">
                  Максимальный процент скидки <span class="text-danger">*</span>
                </label>

                <div class="col-md-9">
                  <input
                    id="percent"
                    v-model="promoCode.percent"
                    v-number
                    v-validate="'required|max_value:' + $root.config('shop.promo.discount.amount.max_percent')"
                    type="number"
                    class="form-control"
                    name="percent"
                  >

                  <span v-show="formErrors.has('percent')" class="help-block">
                    {{ formErrors.first('percent') }}
                  </span>
                </div>
              </div>

            </div>

          </div>

          <!-- Количество использований -->

          <div class="block">
            <div class="block-title">
              <h2>
                <i class="fa fa-hashtag" /> <strong>Количество</strong> использований
              </h2>
            </div>

            <div class="form-horizontal form-bordered">
              <div :class="`form-group${formErrors.has('quantity') ? ' has-error' : ''}`">
                <label class="col-md-3 control-label">
                  Общее
                </label>

                <div class="col-md-9">
                  <div class="clearfix">
                    <div class="pull-left">
                      <input
                        id="quantity"
                        v-model="promoCode.quantity < 0 ? savedQuantities.quantity : promoCode.quantity"
                        v-number
                        v-validate="promoCode.quantity < 0 ? '' : 'required|integer|min_value:1|max_value:2147483647'"
                        style="max-width: 100px"
                        class="form-control"
                        name="quantity"
                        :disabled="promoCode.quantity < 0"
                      >
                    </div>

                    <div class="pull-left unlimited">
                      <label class="switch switch-primary">
                        <input
                          id="quantity-unlimited"
                          type="checkbox"
                          :checked="promoCode.quantity < 0"
                          @change="setUnlimited('quantity')"
                        >
                        <span />
                      </label>

                      <label class="help-inline" for="quantity-unlimited">
                        Неограниченно
                      </label>
                    </div>
                  </div>

                  <span v-show="formErrors.has('quantity')" class="help-block">
                    {{ formErrors.first('quantity') }}
                  </span>
                </div>
              </div>

              <div :class="`form-group${formErrors.has('quantity_per_user') ? ' has-error' : ''}`">
                <label class="col-md-3 control-label">
                  На одного пользователя
                </label>

                <div class="col-md-9 clearfix">
                  <div class="clearfix">
                    <div class="pull-left">
                      <input
                        id="quantity_per_user"
                        v-model="promoCode.quantity_per_user < 0 ? savedQuantities.quantity_per_user : promoCode.quantity_per_user"
                        v-number
                        v-validate="promoCode.quantity_per_user < 0 ? '' : 'required|integer|min_value:1|max_value:2147483647'"
                        style="max-width: 100px"
                        class="form-control"
                        name="quantity_per_user"
                        :disabled="promoCode.quantity_per_user < 0"
                      >
                    </div>

                    <div class="pull-left unlimited">
                      <label class="switch switch-primary">
                        <input
                          id="quantity_per_user-unlimited"
                          type="checkbox"
                          :checked="promoCode.quantity_per_user < 0"
                          @change="setUnlimited('quantity_per_user')"
                        >
                        <span />
                      </label>

                      <label class="help-inline" for="quantity_per_user-unlimited">
                        Неограниченно
                      </label>
                    </div>
                  </div>

                  <span v-show="formErrors.has('quantity_per_user')" class="help-block">
                    {{ formErrors.first('quantity_per_user') }}
                  </span>
                </div>
              </div>
            </div>

          </div>

          <!-- Срок использования -->

          <div class="block">
            <div class="block-title">
              <h2>
                <i class="fa fa-calendar" /> <strong>Срок</strong> использования
              </h2>
            </div>

            <div class="form-horizontal form-bordered">

              <div :class="`form-group${formErrors.has('date_start') ? ' has-error' : ''}`">
                <label class="col-md-3 control-label" for="date_start">
                  Дата начала
                </label>

                <div class="col-md-9">
                  <date-picker
                    id="date_start"
                    ref="dateStart"
                    v-model="promoCode.date_start"
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
                    v-model="promoCode.date_finish"
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

            </div>
          </div>
        </div>

        <!-- Дополнительные условия -->

        <div class="col-lg-6">
          <div :class="`block${langSwitchHovered ? ' block-illuminated' : ''}`">
            <div class="block-title">
              <h2>
                <i class="fa fa-globe" /> <strong>Языковая</strong> информация
              </h2>
            </div>

            <template v-for="language in languages">
              <div :key="language.code" :class="`form-horizontal form-bordered${activeLanguageCode === language.code ? '' : ' in-space'}`">

                <div :class="`form-group${formErrors.has(`i18n.${language.code}.title`) ? ' has-error' : ''}`">
                  <label class="col-md-3 control-label" :for="`title-${language.code}`">
                    Название <span class="text-danger">*</span>
                  </label>

                  <div class="col-md-9">
                    <input
                      :id="`title-${language.code}`"
                      v-model="promoCode.i18n[language.code].title"
                      v-validate="'required|max:255'"
                      type="text"
                      class="form-control"
                      :name="`i18n.${language.code}.title`"
                    >

                    <span
                      v-show="formErrors.has(`i18n.${language.code}.title`)"
                      class="help-block"
                    >
                      {{ formErrors.first(`i18n.${language.code}.title`) }}
                    </span>
                  </div>
                </div>

                <div :class="`form-group${formErrors.has(`i18n.${language.code}.description`) ? ' has-error' : ''}`">
                  <label class="col-md-3 control-label" :for="`description-${language.code}`">Описание</label>

                  <div class="col-md-9">
                    <ckeditor
                      :id="`description-${language.code}`"
                      :content.sync="promoCode.i18n[language.code].description"
                      :name="`i18n.${language.code}.description`"
                    />

                    <span
                      v-show="formErrors.has(`i18n.${language.code}.description`)"
                      class="help-block"
                    >
                      {{ formErrors.first(`i18n.${language.code}.description`) }}
                    </span>
                  </div>
                </div>
              </div>
            </template>

          </div>

          <div v-if="hasNotSelectedConditions" class="block">
            <div class="block-title">
              <h2>
                <i class="fa fa-bars" /> <strong>Дополнительные</strong> условия
              </h2>
            </div>

            <div class="form-horizontal form-bordered">
              <div class="form-group">
                <label class="col-md-3 control-label" for="conditions">
                  Условия
                </label>

                <div class="col-md-9 clearfix">
                  <div class="input-group">
                    <tree-select
                      name="conditions"
                      :options="conditionsToSelect"
                      :params="{minimumResultsForSearch: -1}"
                      placeholder="Выберите условие"
                      @update:selected="selectCondition"
                    />

                    <a class="input-group-addon btn btn-primary" @click="addCondition">
                      <i class="fa fa-plus-circle" />
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Минимальная сумма заказа -->

          <div v-if="promoCode.conditions.min_summ" class="block">
            <div class="block-title clearfix">
              <h2>
                <i class="fa fa-shopping-cart" />
                <strong>Минимальная</strong> сумма заказа
              </h2>

              <div class="block-title-control">
                <a v-if="userCan('promo-codes.conditions')" class="btn btn-sm btn-danger active" @click="removeCondition('min_summ')">
                  <i class="fa fa-trash" /> Удалить
                </a>
              </div>
            </div>

            <div class="form-horizontal form-bordered">
              <div class="form-group">
                <div :class="`form-group${formErrors.has('conditions.min_summ.value') ? ' has-error' : ''}`">
                  <label class="col-md-3 control-label" for="condition-min-summ-value">
                    Сумма <span class="text-danger">*</span>
                  </label>

                  <div class="col-md-9">
                    <div class="input-group price-input-group">
                      <input
                        id="condition-min-summ-value"
                        v-model="promoCode.conditions.min_summ.value"
                        v-number
                        v-validate="'required|max_value:2147483647'"
                        type="number"
                        class="form-control"
                        name="conditions[min_summ][value]"
                      >

                      <div class="input-group-addon">
                        <tree-select
                          name="conditions[min_summ][currency_code]"
                          :options="currenciesToSelect"
                          :selected.sync="promoCode.conditions.min_summ.currency_code"
                          :params="{minimumResultsForSearch: -1, allowClear: false}"
                        />
                      </div>
                    </div>

                    <span v-show="formErrors.has('conditions.min_summ.value')" class="help-block">
                      {{ formErrors.first('conditions.min_summ.value') }}
                    </span>

                    <span v-show="formErrors.has('conditions.min_summ.currency_code')" class="help-block">
                      {{ formErrors.first('conditions.min_summ.currency_code') }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Минимальная цена товара в корзине -->

          <div v-if="promoCode.conditions.product_expensive" class="block">
            <div class="block-title clearfix">
              <h2>
                <i class="fa fa-tv" />
                <strong>Минимальная</strong> цена товара в корзине
              </h2>

              <div class="block-title-control">
                <a v-if="userCan('promo-codes.conditions')" class="btn btn-sm btn-danger active" @click="removeCondition('product_expensive')">
                  <i class="fa fa-trash" /> Удалить
                </a>
              </div>
            </div>

            <div class="form-horizontal form-bordered">
              <div class="form-group">
                <div :class="`form-group${formErrors.has('conditions.product_expensive.value') ? ' has-error' : ''}`">
                  <label class="col-md-3 control-label" for="product-expensive-summ-value">
                    Цена <span class="text-danger">*</span>
                  </label>

                  <div class="col-md-9">
                    <div class="input-group price-input-group">
                      <input
                        id="product-expensive-summ-value"
                        v-model="promoCode.conditions.product_expensive.value"
                        v-number
                        v-validate="'required|max_value:2147483647'"
                        type="number"
                        class="form-control"
                        name="conditions[product_expensive][value]"
                      >

                      <div class="input-group-addon">
                        <tree-select
                          name="conditions[product_expensive][currency_code]"
                          :options="currenciesToSelect"
                          :selected.sync="promoCode.conditions.product_expensive.currency_code"
                          :params="{minimumResultsForSearch: -1, allowClear: false}"
                        />
                      </div>
                    </div>

                    <span v-show="formErrors.has('conditions.product_expensive.value')" class="help-block">
                      {{ formErrors.first('conditions.product_expensive.value') }}
                    </span>

                    <span v-show="formErrors.has('conditions.product_expensive.currency_code')" class="help-block">
                      {{ formErrors.first('conditions.product_expensive.currency_code') }}
                    </span>
                  </div>

                </div>
              </div>
            </div>
          </div>

          <!-- Минимальное количество товаров в корзине -->

          <div v-if="promoCode.conditions.products_quantity" class="block">
            <div class="block-title clearfix">
              <h2>
                <i class="fa fa-shopping-cart" />
                <strong>Минимальное</strong> количество товаров в корзине
              </h2>

              <div class="block-title-control">
                <a v-if="userCan('promo-codes.conditions')" class="btn btn-sm btn-danger active" @click="removeCondition('products_quantity')">
                  <i class="fa fa-trash" /> Удалить
                </a>
              </div>
            </div>

            <div class="form-horizontal form-bordered">
              <div class="form-group">
                <div :class="`form-group${formErrors.has('conditions.products_quantity.value') ? ' has-error' : ''}`">
                  <label class="col-md-3 control-label" for="product-quantity-summ-value">
                    Количество <span class="text-danger">*</span>
                  </label>

                  <div class="col-md-9">
                    <input
                      id="product-quantity-summ-value"
                      v-model="promoCode.conditions.products_quantity.value"
                      v-number
                      v-validate="'required|max_value:2147483647'"
                      type="number"
                      class="form-control"
                      name="conditions[products_quantity][value]"
                    >

                    <span v-show="formErrors.has('conditions.products_quantity.value')" class="help-block">
                      {{ formErrors.first('conditions.products_quantity.value') }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Первый заказ -->

          <div v-if="promoCode.conditions.first_order" class="block">
            <div class="block-title clearfix">
              <h2>
                <i class="fa fa-certificate" />
                <strong>Первый</strong> заказ
              </h2>

              <div class="block-title-control">
                <a v-if="userCan('promo-codes.conditions')" class="btn btn-sm btn-danger active" @click="removeCondition('first_order')">
                  <i class="fa fa-trash" /> Удалить
                </a>
              </div>
            </div>

            <div class="form-group">
              Промокод будет срабатывать только для первого заказа учетной записи.
            </div>
          </div>

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
      title="Удаление промокода"
      title-tag="h3"
      centered
      ok-title="Удалить"
      cancel-title="Отмена"
      hide-header-close
      @ok="removeConfirm"
    >

      Вы действительно хотите удалить промокод?
    </b-modal>
  </div>
</template>

<style>
  .unlimited {
    margin: 3px 0 0 16px;
    user-select: none;
    cursor: pointer;
  }

  .unlimited .help-inline {
    vertical-align: middle;
    margin: 0 0 0 5px;
  }

  .date-picker {
    max-width: 150px;
  }

  .price-input-group {
    max-width: 200px;
  }

  .price-input-group .input-group-addon {
    width: 80px;
    padding: 0;
    border: none;
  }
</style>
