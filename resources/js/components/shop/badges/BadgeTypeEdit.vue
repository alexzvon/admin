<script>
import Core from '../../../core';
//  import bModal from 'bootstrap-vue/es/components/modal/modal'

import LanguagePicker from '../../LanguagePicker';

import EntityPage from '../../../mixins/EntityPage';
import Translatable from '../../../mixins/Translatable';
import TreeSelect from '../../TreeSelect';

import BadgeTypeModel from '../../../resources/shop/badge/BadgeTypeModel';
import BadgePreview from './BadgePreview';

import ColorPicker from 'v-color';

export default {
  name: 'BadgeTypeEdit',

  components: {
    LanguagePicker,
    //      bModal,
    ColorPicker,
    BadgePreview,
    TreeSelect,
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
      entityName: 'badge-type',
      badgeType: null,

      usedMainData: [
        'languages',
      ],

      reloadDataOnSave: false,
    };
  },

  computed: {
    iconsToTree() {
      return (Core.config('shop.badges.icons') || []).map(item => {
        return {
          id: item,
          title: item,
        };
      });
    },
  },

  methods: {
    /**
       * Инициализация модели данных.
       */
    initEntity(data) {
      this.setEntityData(new BadgeTypeModel(data, this.languages));
    },

    setColor(color) {
      this.badgeType.color = color.hex;
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
            Создание бейджа
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

          <a v-if="userCan('badge-types.create')" class="btn btn-sm btn-success active" @click="save">
            <i class="fa fa-plus-circle" /> Создать
          </a>
        </div>
      </div>

      <div v-if="type === 'edit'" class="block-title">
        <h1>
          <strong>
            Редактирование бейджа #{{ id }}
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

          <a v-if="userCan('badge-types.edit')" class="btn btn-sm btn-primary active" @click="save">
            <i class="fa fa-floppy-o" /> Сохранить
          </a>

          <a v-if="userCan('badge-types.delete')" class="btn btn-sm btn-danger active" @click="remove">
            Удалить
          </a>
        </div>
      </div>

      <div v-if="badgeType" class="row">

        <div class="col-lg-6">
          <div class="block">
            <div class="block-title clearfix">
              <h2>
                <i class="fa fa-pencil" />
                <strong>Основная</strong> информация
              </h2>

              <div class="block-title-control">
                <badge-preview
                  :color="badgeType.color"
                  :icon="badgeType.icon"
                  :title="badgeType.i18n[activeLanguageCode].title"
                  :text="badgeType.has_value ? '-20%' : undefined"
                />
              </div>
            </div>

            <div class="form-horizontal form-bordered">

              <div :class="`form-group${formErrors.has('icon') ? ' has-error' : ''}`">
                <label class="col-md-3 control-label">
                  Иконка <span class="text-danger">*</span>
                </label>

                <div class="col-md-9">
                  <tree-select
                    :options="iconsToTree"
                    :selected.sync="badgeType.icon"
                    placeholder="Выберите иконку"
                  />

                  <span v-show="formErrors.has('icon')" class="help-block">
                    {{ formErrors.first('icon') }}
                  </span>
                </div>
              </div>

              <div :class="`form-group${formErrors.has('color') ? ' has-error' : ''}`">
                <label class="col-md-3 control-label">
                  Цвет <span class="text-danger">*</span>
                </label>

                <div class="col-md-9">
                  <color-picker
                    :color="badgeType.color || undefined"
                    @change="setColor"
                  />

                  <span v-show="formErrors.has('color')" class="help-block">
                    {{ formErrors.first('color') }}
                  </span>
                </div>
              </div>

              <!--<div :class="`form-group${formErrors.has('has_value') ? ' has-error' : ''}`">-->
              <!--<label class="col-md-3 control-label">-->
              <!--Заполняемый-->
              <!--</label>-->

              <!--<div class="col-md-9">-->
              <!--<label class="switch switch-primary">-->
              <!--<input type="checkbox" id="has_value" name="has_value" v-model="badgeType.has_value">-->
              <!--<span></span>-->
              <!--</label>-->

              <!--<span v-show="formErrors.has('has_value')" class="help-block">-->
              <!--{{ formErrors.first('has_value') }}-->
              <!--</span>-->
              <!--</div>-->
              <!--</div>-->

              <div v-if="badgeType.created_at" class="form-group">
                <label class="col-md-3 control-label">
                  Дата создания
                </label>

                <div class="col-md-9">
                  <p class="form-control-static">
                    {{ badgeType.created_at }}
                  </p>
                </div>
              </div>

              <div v-if="badgeType.updated_at" class="form-group">
                <label class="col-md-3 control-label">
                  Последнее изменение
                </label>

                <div class="col-md-9">
                  <p class="form-control-static">
                    {{ badgeType.updated_at }}
                  </p>
                </div>
              </div>

            </div>
          </div>
        </div>

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
                      v-model="badgeType.i18n[language.code].title"
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

              </div>
            </template>
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

