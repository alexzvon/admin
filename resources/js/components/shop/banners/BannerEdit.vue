<template>

  <div>
    <div class="block full">
      <div v-if="type === 'create'" class="block-title clearfix">
        <h1>Создание баннера</h1>

        <div class="block-title-control">
          <a class="btn btn-sm btn-default btn-alt" @click="redirectToTable">
            <i class="fa fa-arrow-left" />
          </a>

          <span class="btn-separator-xs" />

          <a v-if="userCan('shop.banners.create')" class="btn btn-sm btn-success active" @click="save">
            <i class="fa fa-plus-circle" /> Создать
          </a>
        </div>
      </div>

      <div v-if="type === 'edit'" class="block-title">

        <h1>Редактирование баннера #{{ this.id }}</h1>

        <div class="block-title-control">
          <a class="btn btn-sm btn-default btn-alt" @click="redirectToTable">
            <i class="fa fa-arrow-left" />
          </a>

          <span class="btn-separator-xs" />

          <a v-if="userCan('shop.banners.edit')" class="btn btn-sm btn-primary active" @click="save">
            <i class="fa fa-floppy-o" /> Сохранить
          </a>

          <a v-if="userCan('shop.banners.delete')" class="btn btn-sm btn-danger active" @click="remove">
            Удалить
          </a>
        </div>
      </div>

      <div v-if="banner" class="row">

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
                    Заголовок
                  </label>

                  <div class="col-md-9">
                    <input
                      :id="`title-${language.code}`"
                      v-model="banner.i18n[language.code].title"
                      v-validate="'max:255'"
                      type="text"
                      class="form-control"
                      :name="`i18n.${language.code}.title`"
                    >

                    <div class="clearfix">
                      <text-length-checker
                        class="help-block pull-right media-right"
                        :text="banner.i18n[language.code].title"
                        :max="titleMaxSize"
                      />

                      <span v-show="formErrors.has(`i18n.${language.code}.title`)" class="help-block">
                        {{ formErrors.first(`i18n.${language.code}.title`) }}
                      </span>
                    </div>
                  </div>
                </div>

                <template v-if="bannerType$ === 'default'">
                  <div :class="`form-group clearfix${formErrors.has(`i18n.${language.code}.caption`) ? ' has-error' : ''}`">
                    <label class="col-md-3 control-label" :for="`caption-${language.code}`">
                      Описание
                    </label>

                    <div class="col-md-9">
                      <input
                        :id="`caption-${language.code}`"
                        v-model="banner.i18n[language.code].caption"
                        v-validate="'max:255'"
                        type="text"
                        class="form-control"
                        :name="`i18n.${language.code}.caption`"
                      >

                      <div class="clearfix">
                        <text-length-checker
                          class="help-block pull-right media-right"
                          :text="banner.i18n[language.code].caption"
                          max="60"
                        />

                        <span v-show="formErrors.has(`i18n.${language.code}.caption`)" class="help-block">
                          {{ formErrors.first(`i18n.${language.code}.caption`) }}
                        </span>
                      </div>
                    </div>
                  </div>
                </template>

                <div :class="`form-group${formErrors.has(`i18n.${language.code}.button`) ? ' has-error' : ''}`">
                  <label class="col-md-3 control-label" :for="`button-${language.code}`">
                    Надпись на кнопке
                  </label>

                  <div class="col-md-9">
                    <input
                      :id="`button-${language.code}`"
                      v-model="banner.i18n[language.code].button"
                      v-validate="'max:255'"
                      type="text"
                      class="form-control"
                      :name="`i18n.${language.code}.button`"
                    >

                    <span v-show="formErrors.has(`i18n.${language.code}.button`)" class="help-block">
                      {{ formErrors.first(`i18n.${language.code}.button`) }}
                    </span>
                  </div>
                </div>

                <div :class="`form-group${formErrors.has(`i18n.${language.code}.link`) ? ' has-error' : ''}`">
                  <label class="col-md-3 control-label" :for="`link-${language.code}`">
                    Ссылка
                  </label>

                  <div class="col-md-9">
                    <input
                      :id="`link-${language.code}`"
                      v-model="banner.i18n[language.code].link"
                      v-validate="'max:255'"
                      type="text"
                      class="form-control"
                      :name="`i18n.${language.code}.link`"
                    >

                    <span v-show="formErrors.has(`i18n.${language.code}.link`)" class="help-block">
                      {{ formErrors.first(`i18n.${language.code}.link`) }}
                    </span>
                  </div>
                </div>
              </div>
            </template>

          </div>

          <div class="block">
            <div class="block-title">
              <h2>
                <i class="fa fa-pencil" /> <strong>Основная</strong> информация
              </h2>
            </div>

            <div class="form-horizontal form-bordered">

              <template v-if="bannerPlacesSelectOptions.length > 1">
                <div :class="`form-group${formErrors.has('places') ? ' has-error' : ''}`">
                  <label class="col-md-3 control-label">
                    Места размещения
                  </label>

                  <div class="col-md-9">
                    <tree-select
                      :options="bannerPlacesSelectOptions"
                      :selected.sync="banner.places"
                      :multiple="true"
                      :params="{minimumResultsForSearch: -1, allowClear: false, closeOnSelect: false}"
                    />

                    <span v-show="formErrors.has('places')" class="help-block">
                      {{ formErrors.first("places") }}
                    </span>
                  </div>
                </div>
              </template>

              <div :class="`form-group${formErrors.has('enabled') ? ' has-error' : ''}`">
                <label class="col-md-3 control-label">
                  Опубликовано
                </label>

                <div class="col-md-9">
                  <label class="switch switch-primary">
                    <input v-model="banner.enabled" type="checkbox">
                    <span />
                  </label>

                  <span v-show="formErrors.has('enabled')" class="help-block">
                    {{ formErrors.first("enabled") }}
                  </span>
                </div>
              </div>

              <div v-if="banner.created_at" class="form-group">
                <label class="col-md-3 control-label">
                  Дата создания
                </label>

                <div class="col-md-9">
                  <p class="form-control-static">{{ banner.created_at }}</p>
                </div>
              </div>

              <div v-if="banner.updated_at" class="form-group">
                <label class="col-md-3 control-label">
                  Последнее изменение
                </label>

                <div class="col-md-9">
                  <p class="form-control-static">{{ banner.updated_at }}</p>
                </div>
              </div>

            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="block">
            <div class="block-title">
              <h2>
                <i class="fa fa-font" /> <strong>Цвет</strong> текста
              </h2>
            </div>

            <div class="form-horizontal form-bordered">

              <div :class="`form-group${formErrors.has('title_color') ? ' has-error' : ''}`">
                <label class="col-md-3 control-label">
                  Цвет заголовка <span class="text-danger">*</span>
                </label>

                <div class="col-md-9">
                  <color-select
                    :color="banner.title_color || undefined"
                    @update:color="setColor('title_color', $event)"
                  />

                  <span v-show="formErrors.has('title_color')" class="help-block">
                    {{ formErrors.first("title_color") }}
                  </span>
                </div>
              </div>

              <div :class="`form-group${formErrors.has('caption_color') ? ' has-error' : ''}`">
                <label class="col-md-3 control-label">
                  Цвет описания <span class="text-danger">*</span>
                </label>

                <div class="col-md-9">
                  <color-select
                    :color="banner.caption_color || undefined"
                    @update:color="setColor('caption_color', $event)"
                  />

                  <span v-show="formErrors.has('caption_color')" class="help-block">
                    {{ formErrors.first("caption_color") }}
                  </span>
                </div>
              </div>

              <div :class="`form-group${formErrors.has('button_color') ? ' has-error' : ''}`">
                <label class="col-md-3 control-label">
                  Цвет надписи на кнопке <span class="text-danger">*</span>
                </label>

                <div class="col-md-9">
                  <color-select
                    :color="banner.button_color || undefined"
                    @update:color="setColor('button_color', $event)"
                  />

                  <span v-show="formErrors.has('button_color')" class="help-block">
                    {{ formErrors.first("button_color") }}
                  </span>
                </div>
              </div>

              <div :class="`form-group${formErrors.has('button_background_color') ? ' has-error' : ''}`">
                <label class="col-md-3 control-label">
                  Цвет кнопки <span class="text-danger">*</span>
                </label>

                <div class="col-md-9">
                  <color-select
                    :color="banner.button_background_color || undefined"
                    @update:color="setColor('button_background_color', $event)"
                  />

                  <span v-show="formErrors.has('button_background_color')" class="help-block">
                    {{ formErrors.first("button_background_color") }}
                  </span>
                </div>
              </div>

            </div>
          </div>

          <div v-if="bannerType$ === 'default'" class="block">
            <div class="block-title">
              <h2>
                <i class="fa fa-image" /> <strong>Стандартный баннер</strong>
              </h2>
            </div>

            <div class="form-horizontal form-bordered">

              <div class="form-group">
                <label class="col-md-3 control-label">
                  Изображение
                </label>

                <div class="col-md-9">
                  <file-manager
                    id="small_image"
                    :file.sync="banner.small_image"
                  />
                </div>
              </div>

            </div>
          </div>

          <div class="block">
            <div class="block-title">
              <h2>
                <i class="fa fa-image" /> <strong>Фон</strong>
              </h2>
            </div>

            <div class="form-horizontal form-bordered">

              <div class="form-group">
                <label class="col-md-3 control-label">
                  Тип фона
                </label>

                <div class="col-md-9">
                  <tree-select
                    :options="backgroundTypes()"
                    :selected.sync="backgroundType"
                    :params="{minimumResultsForSearch: -1, allowClear: false}"
                  />
                </div>
              </div>

              <template v-if="backgroundType === 'gradient'">
                <div :class="`form-group${formErrors.has('gradient.color_from') ? ' has-error' : ''}`">
                  <label class="col-md-3 control-label">
                    Первый цвет <span class="text-danger">*</span>
                  </label>

                  <div class="col-md-9">
                    <color-select
                      :color="banner.gradient.color_from || undefined"
                      @update:color="setColor('gradient.color_from', $event)"
                    />

                    <span v-show="formErrors.has('gradient.color_from')" class="help-block">
                      {{ formErrors.first("gradient.color_from") }}
                    </span>
                  </div>
                </div>

                <div :class="`form-group${formErrors.has('gradient.color_to') ? ' has-error' : ''}`">
                  <label class="col-md-3 control-label">
                    Второй цвет <span class="text-danger">*</span>
                  </label>

                  <div class="col-md-9">
                    <color-select
                      :color="banner.gradient.color_to || undefined"
                      @update:color="setColor('gradient.color_to', $event)"
                    />

                    <span v-show="formErrors.has('gradient.color_to')" class="help-block">
                      {{ formErrors.first("gradient.color_to") }}
                    </span>
                  </div>
                </div>

                <div :class="`form-group${formErrors.has('gradient.type') ? ' has-error' : ''}`">
                  <label class="col-md-3 control-label">
                    Тип градиента <span class="text-danger">*</span>
                  </label>

                  <div class="col-md-9">
                    <tree-select
                      :options="gradientTypes()"
                      :selected.sync="banner.gradient.type"
                      :params="{minimumResultsForSearch: -1, allowClear: false}"
                    />

                    <span v-show="formErrors.has('gradient.type')" class="help-block">
                      {{ formErrors.first("gradient.type") }}
                    </span>
                  </div>
                </div>

                <div :class="`form-group${formErrors.has('gradient.angle') ? ' has-error' : ''}`">
                  <label class="col-md-3 control-label">
                    Угол градиента <span class="text-danger">*</span>
                  </label>

                  <div class="col-md-9">
                    <input
                      v-model="banner.gradient.angle"
                      v-number
                      v-validate="'min_value:0|max_value:360'"
                      type="number"
                      class="form-control"
                      :name="banner.gradient.angle"
                    >

                    <span v-show="formErrors.has('gradient.angle')" class="help-block">
                      {{ formErrors.first("gradient.angle") }}
                    </span>
                  </div>
                </div>
              </template>

              <template v-if="backgroundType === 'image'">

                <div class="form-group">
                  <label class="col-md-3 control-label">
                    Стандартный фон
                  </label>

                  <div class="col-md-9">
                    <file-manager
                      id="background_image_1"
                      :file.sync="banner.background_image_1"
                    />
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-3 control-label">
                    <template v-if="bannerType$ === 'default'">
                      Горизонтальный фон
                    </template>

                    <template v-if="bannerType$ === 'header'">
                      Фон в мобильной версии
                    </template>
                  </label>

                  <div class="col-md-9">
                    <file-manager
                      id="background_image_2"
                      :file.sync="banner.background_image_2"
                    />
                  </div>
                </div>
              </template>

            </div>
          </div>

        </div>

        <div class="col-lg-12">
          <div class="block">
            <div class="block-title">
              <h2>
                <i class="fa fa-pencil" /> <strong>Баннер</strong>
              </h2>
            </div>

            <div :class="'banner-preview banner-preview--' + bannerType$">
              <template v-if="bannerType$ === 'default'">
                <div class="banner-preview__row">
                  <div class="banner-preview__big">
                    <div class="banner-preview__info">
                      Стандартный - 583x490 px
                    </div>

                    <standart-banner
                      size="big"

                      :link="prepareUrl(banner.i18n[activeLanguageCode].link)"

                      :title="banner.i18n[activeLanguageCode].title"
                      :caption="banner.i18n[activeLanguageCode].caption"
                      :button-text="banner.i18n[activeLanguageCode].button"

                      :title-color="banner.title_color"
                      :caption-color="banner.caption_color"
                      :button-color="banner.button_color"
                      :button-background="banner.button_background_color"

                      :image="banner.small_image"
                      :background-image="backgroundType === 'image' ? banner.background_image_1 : undefined"

                      :gradient-from="banner.gradient.color_from"
                      :gradient-to="banner.gradient.color_to"
                      :greadient-type="banner.gradient.type"
                      :gradient-angle="banner.gradient.angle"
                    />
                  </div>

                  <div class="banner-preview__standart">
                    <div class="banner-preview__info">
                                            &nbsp;
                    </div>

                    <standart-banner
                      size="medium"
                      :link="prepareUrl(banner.i18n[activeLanguageCode].link)"

                      :title="banner.i18n[activeLanguageCode].title"
                      :caption="banner.i18n[activeLanguageCode].caption"
                      :button-text="banner.i18n[activeLanguageCode].button"

                      :title-color="banner.title_color"
                      :caption-color="banner.caption_color"
                      :button-color="banner.button_color"
                      :button-background="banner.button_background_color"

                      :image="banner.small_image"
                      :background-image="backgroundType === 'image' ? banner.background_image_1 : undefined"

                      :gradient-from="banner.gradient.color_from"
                      :gradient-to="banner.gradient.color_to"
                      :greadient-type="banner.gradient.type"
                      :gradient-angle="banner.gradient.angle"
                    />
                  </div>
                </div>

                <div class="banner-preview__row">
                  <div style="width: 100%">
                    <div class="banner-preview__info">
                      Горизонтальный - 856x96 px
                    </div>

                    <standart-banner
                      size="long"
                      :link="prepareUrl(banner.i18n[activeLanguageCode].link)"

                      :title="banner.i18n[activeLanguageCode].title"
                      :caption="banner.i18n[activeLanguageCode].caption"
                      :button-text="banner.i18n[activeLanguageCode].button"

                      :title-color="banner.title_color"
                      :caption-color="banner.caption_color"
                      :button-color="banner.button_color"
                      :button-background="banner.button_background_color"

                      :image="banner.small_image"
                      :background-image="backgroundType === 'image' ? banner.background_image_2 : undefined"

                      :gradient-from="banner.gradient.color_from"
                      :gradient-to="banner.gradient.color_to"
                      :greadient-type="banner.gradient.type"
                      :gradient-angle="banner.gradient.angle"
                    />
                  </div>
                </div>
              </template>

              <template v-if="bannerType$ === 'header'">
                <div class="banner-preview__row">
                  <div style="width: 100%;">
                    <div class="banner-preview__info">
                      В шапке, десктопный - 2450x56 px или 1920x56 px
                    </div>

                    <header-banner
                      size="desktop"
                      :image="backgroundType === 'image' ? banner.background_image_1 : undefined"

                      :link="prepareUrl(banner.i18n[activeLanguageCode].link)"

                      :title="banner.i18n[activeLanguageCode].title"
                      :button-text="banner.i18n[activeLanguageCode].button"

                      :title-color="banner.caption_color"
                      :caption-color="banner.caption_color"
                      :button-color="banner.button_color"
                      :button-background="banner.button_background_color"

                      :gradient-from="banner.gradient.color_from"
                      :gradient-to="banner.gradient.color_to"
                      :greadient-type="banner.gradient.type"
                      :gradient-angle="banner.gradient.angle"
                    />
                  </div>
                </div>

                <div class="banner-preview__row">
                  <div style="width: 375px">
                    <div class="banner-preview__info">
                      В шапке, мобильный - 375x56 px
                    </div>

                    <header-banner
                      size="mobile"
                      class="header-banner--mobile"
                      :image="backgroundType === 'image' ? banner.background_image_2 : undefined"

                      :link="prepareUrl(banner.i18n[activeLanguageCode].link)"

                      :title="banner.i18n[activeLanguageCode].title"
                      :button-text="banner.i18n[activeLanguageCode].button"

                      :title-color="banner.caption_color"
                      :caption-color="banner.caption_color"
                      :button-color="banner.button_color"
                      :button-background="banner.button_background_color"

                      :gradient-from="banner.gradient.color_from"
                      :gradient-to="banner.gradient.color_to"
                      :greadient-type="banner.gradient.type"
                      :gradient-angle="banner.gradient.angle"
                    />
                  </div>
                </div>
              </template>

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
      title="Удаление баннера"
      title-tag="h3"
      centered
      ok-title="Удалить"
      cancel-title="Отмена"
      hide-header-close
      @ok="removeConfirm"
    >

      Вы действительно хотите удалить баннер?
    </b-modal>
  </div>
</template>

<script>
import _ from 'lodash';
//    import bModal from "bootstrap-vue/es/components/modal/modal";

import Core from '../../../core';
import FileManager from '../../FileManager';
import TreeSelect from '../../TreeSelect';
import CKEditor from '../../CKEditor';
import LanguagePicker from '../../LanguagePicker';

import Page from '../../../mixins/Page';
import EntityPage from '../../../mixins/EntityPage';
import Translatable from '../../../mixins/Translatable';
import BannersMixin from './mixin';
import ColorSelect from '../../ColorSelect';
import StandartBanner from './preview/StandartBanner';
import HeaderBanner from './preview/HeaderBanner';
import TextLengthChecker from '../../TextLengthChecker';

import Number from '../../../directives/number';

import BannerModel from '../../../resources/shop/banner/BannerModel';

export default {
  name: 'BannerEdit',

  directives: {
    Number,
  },

  components: {
    TreeSelect,
    'ckeditor': CKEditor,
    FileManager,
    LanguagePicker,
    // bModal,
    ColorSelect,
    StandartBanner,
    HeaderBanner,
    TextLengthChecker,
  },

  mixins: [
    EntityPage,
    Translatable,
    BannersMixin,
  ],

  props: [
    'id',
    'bannerType',
  ],

  data() {
    return {
      entityName: 'banner',
      banner: null,

      backgroundType: 'gradient',

      usedMainData: [
        'languages',
        'banner-places',
      ],

      bannerPlaces: [],
      bannerType$: this.bannerType || 'default',

      reloadDataOnSave: true,
    };
  },

  computed: {
    titleMaxSize() {
      let size;

      switch (this.bannerType$) {
        case 'default':
          size = 30;
          break;

        case 'header':
          size = 50;
          break;
      }

      return size;
    },
  },

  methods: {
    getToSaveData() {
      const data = {
        ... this.getEntityModel(),
      };

      if (this.backgroundType === 'gradient') {
        data.background_image_1 = '';
        data.background_image_2 = '';
      }

      return data;
    },

    updateImage(label, images = []) {
      this.banner[label] = images.length ? images[0] : false;
    },

    setColor(label, color) {
      _.set(this.banner, label, color);
    },

    prepareUrl(url) {
      return Core.marketUrl(url);
    },

    gradientTypes() {
      return [
        {
          id: 'linear',
          title: 'Линейный',
        },

        {
          id: 'radial',
          title: 'Радиальный',
        },
      ];
    },

    backgroundTypes() {
      return [
        {
          id: 'image',
          title: 'Изображение',
        },

        {
          id: 'gradient',
          title: 'Градиент',
        },
      ];
    },

    detectBannerType() {
      if (this.banner.places.length > 0) {
        const placeId = this.banner.places[0];

        const place = this.bannerPlaces.find(item => item.id === placeId);

        if (place) {
          return place.type;
        }
      }

      return 'default';
    },

    removeBannerTypeFromUrl(url) {
      return url.replace('/' + this.bannerType$, '');
    },

    makePageUrl(segment, segmentIsUrl) {
      const url = Page.methods.makePageUrl.apply(this, [segment, segmentIsUrl]);

      return this.removeBannerTypeFromUrl(url);
    },

    getPathToTable() {
      return EntityPage.methods.getPathToTable.call(this) +
                    '?' +
                    encodeURIComponent('bannerType$') +
                    '=' +
                    encodeURIComponent(this.bannerType$);
    },

    /**
             * Инициализация модели данных.
             */
    initEntity(data) {
      this.setEntityData(new BannerModel(data, this.languages));

      if (this.type === 'edit') {
        this.bannerType$ = this.detectBannerType();
      }

      if (this.banner.background_image_1 || this.banner.background_image_2) {
        this.backgroundType = 'image';
      }

      this.banner.places = this.bannerPlacesSelectOptions.map(item => item.id);
    },
  },
};
</script>

