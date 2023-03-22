<script>
//  import bModal from 'bootstrap-vue/es/components/modal/modal'

import CKEditor from '../../CKEditor';
import LanguagePicker from '../../LanguagePicker';

import EntityPage from '../../../mixins/EntityPage';
import Translatable from '../../../mixins/Translatable';

import PriceTypeModel from '../../../resources/shop/PriceTypeModel';

export default {
  name: 'PriceTypeEdit',

  components: {
    'ckeditor': CKEditor,
    //      bModal,
    LanguagePicker,
  },

  mixins: [
    EntityPage,
    Translatable,
  ],

  props: [
    'id',
  ],

  data() {
    return {
      entityName: 'price-type',
      priceType: null,
      usedMainData: [
        'languages',
      ],
    };
  },

  methods: {
    initEntity(data) {
      this.setEntityData(new PriceTypeModel(data, this.languages));
    },
  },
};
</script>

<template>
  <div>
    <div class="block full">
      <div v-if="type === 'create'" class="block-title clearfix">
        <h1>
          <strong>
            Создание типа цены
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

          <a v-if="userCan('price-types.create')" class="btn btn-sm btn-success active" @click="save">
            <i class="fa fa-plus-circle" /> Создать
          </a>
        </div>
      </div>

      <div v-if="type === 'edit'" class="block-title clearfix">
        <h1>
          <strong>
            Редактирование типа цены #{{ this.id }}
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

          <a v-if="userCan('price-types.edit')" class="btn btn-sm btn-primary active" @click="save">
            <i class="fa fa-floppy-o" /> Сохранить
          </a>

          <a v-if="userCan('price-types.delete')" class="btn btn-sm btn-danger active" @click="remove">
            Удалить
          </a>
        </div>
      </div>

      <div v-if="priceType" class="row">
        <div class="col-lg-12">
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
                      v-model="priceType.i18n[language.code].title"
                      v-validate="'required|max:255'"
                      type="text"
                      class="form-control"
                      :name="`i18n.${language.code}.title`"
                    >

                    <span v-show="formErrors.has(`i18n.${language.code}.title`)" class="help-block">
                      {{ formErrors.first(`i18n.${language.code}.title`) }}
                    </span>
                  </div>
                </div>

                <div :class="`form-group${formErrors.has(`i18n.${language.code}.description`) ? ' has-error' : ''}`">
                  <label class="col-md-3 control-label" :for="`description-${language.code}`">
                    Описание
                  </label>

                  <div class="col-md-9">
                    <ckeditor
                      :id="`description-${language.code}`"
                      :content.sync="priceType.i18n[language.code].description"
                      :name="`i18n.${language.code}.description`"
                    />

                    <span v-show="formErrors.has(`i18n.${language.code}.description`)" class="help-block">
                      {{ formErrors.first(`i18n.${language.code}.description`) }}
                    </span>
                  </div>
                </div>
              </div>
            </template>

          </div>
        </div>

        <div class="col-lg-12">
          <div :class="`block${langSwitchHovered ? ' block-illuminated' : ''}`">
            <div class="block-title">
              <h2>
                <i class="fa fa-pencil" /> <strong>Основная</strong> информация
              </h2>
            </div>

            <div class="form-horizontal form-bordered">

              <div :class="`form-group${formErrors.has('enabled') ? ' has-error' : ''}`">
                <label class="col-md-3 control-label">Включено</label>

                <div class="col-md-9">
                  <label class="switch switch-primary">
                    <input v-model="priceType.enabled" type="checkbox">
                    <span />
                  </label>

                  <span v-show="formErrors.has('enabled')" class="help-block">
                    {{ formErrors.first('enabled') }}
                  </span>
                </div>
              </div>

              <div v-if="priceType.created_at" class="form-group">
                <label class="col-md-3 control-label">
                  Дата создания
                </label>

                <div class="col-md-9">
                  <p class="form-control-static">
                    {{ priceType.created_at }}
                  </p>
                </div>
              </div>

              <div v-if="priceType.updated_at" class="form-group">
                <label class="col-md-3 control-label">
                  Последнее изменение
                </label>

                <div class="col-md-9">
                  <p class="form-control-static">
                    {{ priceType.updated_at }}
                  </p>
                </div>
              </div>

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
      title="Удаление типа цены"
      title-tag="h3"
      centered
      ok-title="Удалить"
      cancel-title="Отмена"
      hide-header-close
      @ok="removeConfirm"
    >

      Вы действительно хотите удалить этот тип цены?
    </b-modal>
  </div>
</template>
