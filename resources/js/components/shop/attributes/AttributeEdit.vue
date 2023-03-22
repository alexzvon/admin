<template>
  <div>
    <div class="block full">
      <div v-if="type === 'create'" class="block-title clearfix">
        <h1>
          <strong>
            Создание аттрибута
          </strong>
        </h1>

        <div class="block-title-control">
          <a class="btn btn-sm btn-default btn-alt" @click="redirectToTable">
            <i class="fa fa-arrow-left" />
          </a>

          <span class="btn-separator-xs" />

          <a v-if="userCan('shop.attributes.create')" class="btn btn-sm btn-success active" @click="save">
            <i class="fa fa-plus-circle" /> Создать
          </a>
        </div>
      </div>

      <div v-if="type === 'edit'" class="block-title clearfix">
        <h1>
          <strong>
            Редактирование аттрибута #{{ this.id }}
          </strong>
        </h1>

        <div class="block-title-control">
          <a class="btn btn-sm btn-default btn-alt" @click="redirectToTable">
            <i class="fa fa-arrow-left" />
          </a>

          <span class="btn-separator-xs" />

          <a v-if="userCan('shop.attributes.save')" class="btn btn-sm btn-primary active" @click="save">
            <i class="fa fa-floppy-o" /> Сохранить
          </a>

          <a v-if="userCan('shop.attributes.delete')" class="btn btn-sm btn-danger active" @click="remove">
            Удалить
          </a>
        </div>
      </div>

      <div v-if="attribute" class="row">

        <div class="col-xl-6">

          <div :class="`block${langSwitchHovered ? ' block-illuminated' : ''}`">
            <div class="block-title clearfix">
              <h2>
                <i class="fa fa-globe" /> <strong>Языковая</strong> информация
              </h2>
            </div>

            <template v-for="language in languages">
              <div
                :key="language.code"
                :class="`form-horizontal form-bordered${activeLanguageCode === language.code ? '' : ' in-space'}`"
              >

                <div :class="`form-group${formErrors.has(`i18n.${language.code}.title`) ? ' has-error' : ''}`">
                  <label class="col-md-3 control-label" :for="`title-${language.code}`">
                    Название <span class="text-danger">*</span>
                  </label>

                  <div class="col-md-9">
                    <input
                      :id="`title-${language.code}`"
                      v-model="attribute.i18n[language.code].title"
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

          <div class="block">
            <div class="block-title">
              <h2>
                <i class="fa fa-pencil" /> <strong>Основная</strong> информация
              </h2>
            </div>

            <div class="form-horizontal form-bordered">

              <div :class="`form-group${formErrors.has('enabled') ? ' has-error' : ''}`">
                <label class="col-md-3 control-label">
                  Опубликовано
                </label>

                <div class="col-md-9">
                  <label class="switch switch-primary">
                    <input v-model="attribute.enabled" type="checkbox">
                    <span />
                  </label>

                  <span v-show="formErrors.has('enabled')" class="help-block">
                    {{ formErrors.first('enabled') }}
                  </span>
                </div>
              </div>

              <div
                v-if="userCan('shop.attributes.edit-hidden-params')"
                :class="`form-group${formErrors.has('selectable') ? ' has-error' : ''}`"
              >
                <label class="col-md-3 control-label">
                  Выбираемый
                </label>

                <div class="col-md-9">
                  <label class="switch switch-primary">
                    <input v-model="attribute.selectable" type="checkbox">
                    <span />
                  </label>

                  <span v-show="formErrors.has('selectable')" class="help-block">
                    {{ formErrors.first('selectable') }}
                  </span>

                  <span class="help-block">
                    * Дает возможность выбирать аттрибут покупателю в карточке товара.
                  </span>
                </div>
              </div>

              <div
                v-if="userCan('shop.attributes.edit-hidden-params')"
                :class="`form-group${formErrors.has('slug') ? ' has-error' : ''}`"
              >
                <label class="col-md-3 control-label" for="slug">
                  Класс в верстке
                </label>

                <div class="col-md-9">
                  <input
                    id="slug"
                    v-model="attribute.layout_class"
                    v-validate="'max:255'"
                    type="text"
                    class="form-control"
                    name="slug"
                  >

                  <span v-show="formErrors.has('layout_class')" class="help-block">
                    {{ formErrors.first('layout_class') }}
                  </span>
                </div>
              </div>

              <div v-if="attribute.created_at" class="form-group">
                <label class="col-md-3 control-label">
                  Дата создания
                </label>

                <div class="col-md-9">
                  <p class="form-control-static">
                    {{ attribute.created_at }}
                  </p>
                </div>
              </div>

              <div v-if="attribute.updated_at" class="form-group">
                <label class="col-md-3 control-label">
                  Последнее изменение
                </label>

                <div class="col-md-9">
                  <p class="form-control-static">
                    {{ attribute.updated_at }}
                  </p>
                </div>
              </div>

            </div>
          </div>
        </div>

        <div class="col-xl-6">
          <div :class="`block${langSwitchHovered ? ' block-illuminated' : ''}`">
            <div class="block-title clearfix">
              <h2>
                <i class="fa fa-list" /> <strong>Значения</strong>
              </h2>

              <div class="block-title-control">
                <a class="btn btn-sm btn-primary active" @click="addOption">
                  <i class="fa fa-plus-circle" /> Добавить
                </a>
              </div>
            </div>

            <div class="block-section table-responsive">
              <table class="table table-middle table-center table-condensed table-bordered table-hover dataTable table-sortable table-remove-restore">
                <tbody class="ui-sortable">
                  <tr
                    v-for="option in options"
                    :key="option.id"
                    :class="{'js-sort-item': !option.deleted, 'table-remove-restore__deleted': option.deleted}"
                  >
                    <td :class="{'table-sort-handler': true, 'js-sort-handler': !option.deleted}">
                      <span>
                        <input type="hidden" name="ids" :value="option.id">
                      </span>
                    </td>

                    <td>
                      <span>
                        {{ option.id }}
                      </span>
                    </td>

                    <td style="width: 100%">
                      <template v-for="language in languages">
                        <div
                          :key="language.code"
                          :class="{'has-error': formErrors.has(`options.${option.id}.i18n.${language.code}.value`), 'in-space': activeLanguageCode !== language.code}"
                        >
                          <input
                            :id="`option-${option.id}-${language.code}`"
                            v-model="option.i18n[language.code].value"
                            v-validate="'required|max:255'"
                            type="text"
                            class="form-control"
                            :name="`options.${option.id}.i18n.${language.code}.value`"
                          >

                          <span
                            v-show="formErrors.has(`options.${option.id}.i18n.${language.code}.value`)"
                            class="help-block"
                            style="margin-bottom: 0;"
                          >
                            {{ formErrors.first(`options.${option.id}.i18n.${language.code}.value`) }}
                          </span>
                        </div>
                      </template>
                    </td>

                    <td v-if="id === '20'">
                      <option-color-picker :option="option" :name="option.i18n.ru.value" />
                    </td>

                    <td>
                      <div class="table-control table-remove-restore__restore">
                        <toggle
                          :checked="option.enabled"
                          @change="changeOptionStatus(option)"
                        />
                      </div>
                    </td>

                    <td>
                      <div>
                        <div v-if="option.isNew || userCan('shop.attributes.remove-option')">
                          <a
                            v-if="!option.deleted"
                            class="btn btn-danger"
                            @click="removeOption(option)"
                          >
                            <i class="fa fa-times" />
                          </a>

                          <a
                            v-else
                            class="btn btn-success table-remove-restore__restore"
                            @click="restoreOption(option)"
                          >
                            <i class="fa fa-repeat" />
                          </a>
                        </div>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
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
      id="colorpickerModal"
      ref="colorpickerModal"
      title="Выбор цвета"
      title-tag="h3"
      centered
      ok-title="Ок"
      ok-only
      hide-header-close
    >
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6">
            <label for="sketchPicker">Выберите цвет</label>
            <sketch-picker
              id="sketchPicker"
              ref="sketchPicker"
              v-model="colors"
              @input="updateColor"
            />
          </div>
          <div class="col-md-6">
            <label for="attributeName">Введите название</label>
            <input
              id="attributeName"
              v-model="colorName"
              type="text"
              name="attributeName"
            >
          </div>
        </div>
      </div>

    </b-modal>

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

      Вы действительно хотите удалить этот аттрибут?
    </b-modal>
  </div>
</template>

<script>
import EntityPage from '../../../mixins/EntityPage';
import Translatable from '../../../mixins/Translatable';
import Sortable from '../../../mixins/Sortable';

//    import bModal from 'bootstrap-vue/es/components/modal/modal'
import LanguagePicker from '../../LanguagePicker';
import LanguageIdentif from '../../LanguageIdentif';
import Toggle from '../../Toggle';

import AttributeModel from '../../../resources/shop/AttributeModel';
import OptionModel from '../../../resources/shop/OptionModel';

import slider from 'vue-color/src/components/Slider.vue';
import sketch from 'vue-color/src/components/Sketch.vue';
import chrome from 'vue-color/src/components/Chrome.vue';
import photoshop from 'vue-color/src/components/Photoshop.vue';
import optionColorPicker from '../../../components/shop/attributes/OptionColorPicker';

export default {
  name: 'AttributeEdit',

  components: {
    optionColorPicker,
    LanguagePicker,
    LanguageIdentif,
    //           bModal,
    Toggle,
    'slider-picker': slider,
    'sketch-picker': sketch,
    'chrome-picker': chrome,
    'photoshop-picker': photoshop,
  },

  mixins: [
    EntityPage,
    Sortable,
    Translatable,
  ],

  props: [
    'id',
    'type',
  ],

  data() {
    return {
      $id: null,
      entityName: 'attribute',
      attribute: null,
      options: [],
      validationErrors: [],

      usedMainData: [
        'languages',
      ],

      sortableParams: {
        items: '.js-sort-item',
        handle: '.js-sort-handler',
        opacity: 0.9,
        start: function(e, helper) {
          const height = helper.item.height();
          helper.placeholder.css({
            height,
            visibility: 'visible',
          });
        },
        stop: this.htmlOptionPositionChanged,
      },
      colors: {
        hex: '#194d33',
        hsl: { h: 150, s: 0.5, l: 0.2, a: 1 },
        hsv: { h: 150, s: 0.66, v: 0.30, a: 1 },
        rgba: { r: 25, g: 77, b: 51, a: 1 },
        a: 1,
      },
      colorName: '',
      colorValueToSave: {
        hex: '#194d33',
        hsl: { h: 150, s: 0.5, l: 0.2, a: 1 },
        hsv: { h: 150, s: 0.66, v: 0.30, a: 1 },
        rgba: { r: 25, g: 77, b: 51, a: 1 },
        a: 1,
      },
      colorOptionToChange: null,

    };
  },

  watch: {
    options: 'refreshSort',
  },
  mounted() {
    this.$id = this.id;

    this.$root.$on('bv::modal::hide', (bvEvent, modalId) => {
      if (this.colorOptionToChange === null) {
        return;
      }
      const option = this.options.filter(elem => elem.id === this.colorOptionToChange)[0];
      option.i18n.ru.value = this.colorName;

      option.value.color = {};
      option.value.color.value = {};

      option.value.color.value = JSON.stringify(this.colorValueToSave);
      option.colors = this.colorValueToSave;
      option.rgba = `rgba(${option.colors.rgba.r}, ${option.colors.rgba.g}, ${option.colors.rgba.b}, ${option.colors.rgba.a})`;

      const optionName = `option-${option.id}`;
      const changedOption = this.$children.filter(item => item.$refs.hasOwnProperty(optionName))[0];
      changedOption.$forceUpdate();
    });
  },

  methods: {
    /**
             * Инициализация модели данных.
             */
    initEntity(data) {
      this.setEntityData(new AttributeModel(data, this.languages));

      if (this.type === 'edit') {
        this.initOptions(data.options);
      }
    },

    initOptions(data = []) {
      this.options = this.sortOptions(data.map(item => this.makeOption(item)));
    },

    getToSaveData() {
      return {
        ...this.getEntityModel(),
        options: this.getToSaveOptions(),
      };
    },

    getToSaveOptions() {
      return this.options.reduce((acc, option) => {
        if (option.deleted) {
          return acc;
        }

        const item = {
          position: option.position,
          enabled: option.enabled,
          i18n: option.i18n,
          value: option.value,
        };

        if (option.isNew) {
          item.isNew = 1;
        }

        acc[option.id] = item;

        return acc;
      }, {});
    },

    addOption() {
      if (parseInt(this.$id) === 20 && this.attribute.i18n.ru.title === 'Цвет') {
        this.options = this.sortOptions([
          ...this.options,
          this.makeColorOption(),
        ]);
      } else {
        this.options = this.sortOptions([
          ...this.options,
          this.makeOption(),
        ]);
      }
    },

    makeOption(data = {}) {
      const option = new OptionModel(data, this.languages);

      if (data.id) {
        option.isNew = false;
      } else {
        option.isNew = true;
        option.position = this.findLastOptionPosition() + 1;
      }

      return option;
    },

    makeColorOption(data = {}) {
      let option = {};
      if (data.id) {
        option = new OptionModel(data, this.languages);
        option.isNew = false;

        return option;
      } else {
        option = new OptionModel(data, this.languages);

        option.isNew = true;

        option.position = this.findFirstOptionPosition();

        if (parseInt(this.$id) === 20 && this.attribute.i18n.ru.title === 'Цвет') {
          this.colorName = '';
          this.colorOptionToChange = option.id;
          this.$refs.colorpickerModal.show();
        }
      }

      return option;
    },

    findLastOptionPosition() {
      return this.options.reduce((acc, { position }) => {
        return position > acc ? position : acc;
      }, 0);
    },

    findFirstOptionPosition() {
      return -1;
    },

    removeOption(option) {
      if (option.isNew) {
        this.options = this.options.filter(item => item.id !== option.id);
        return;
      }

      this.updateOption(option, {
        deleted: true,
      });

      this.getInitializedSortEls().disableSelection();
    },

    restoreOption(option) {
      this.updateOption(option, {
        deleted: false,
      });
    },

    changeOptionStatus(option) {
      this.updateOption(option, {
        enabled: !option.enabled,
      });
    },

    updateOption(option, data) {
      option = {
        ...option,
        ...data,
      };

      const options = this.options.map(item => {
        return option.id === item.id ? option : item;
      });

      this.options = this.sortOptions(options);
    },

    htmlOptionPositionChanged() {
      this.options = this.sortOptions(this.setDataBundlePositionsByIds(this.options, this.collectSortIds()));
    },

    sortOptions(options) {
      return this.sortByPositionAndDeletedToEnd(options);
    },

    sortByPositionAndDeletedToEnd(data) {
      return data.sort((a, b) => {
        if (!a.deleted === !b.deleted) {
          return a.position - b.position;
        }

        return a.deleted ? 1 : -1;
      });
    },

    updateColor(e) {
      this.colorValueToSave = e;
      this.colors = e;
    },
  },
};
</script>

<style>
    .option--color {
        content: '';
        width: 16px;
        height: 16px;
        left: 0;
        top: 50%;
        margin-top: 0;
        border-radius: 50%;
        overflow: hidden;
    }
</style>
