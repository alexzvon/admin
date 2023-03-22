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
            Редактирование свойств акции стимулирования быстрой покупки
          </strong>
        </h1>

        <div class="block-title-control">
          <a v-if="userCan('shop.attributes.save')" class="btn btn-sm btn-primary active" @click="save">
            <i class="fa fa-floppy-o" /> Сохранить
          </a>
        </div>
      </div>

      <div v-if="items" class="row">

        <div class="col-xl-6">
          <div class="block">
            <div class="block-title">
              <h2>
                <i class="fa fa-pencil" /> <strong>Основная</strong> информация
              </h2>
            </div>

            <div class="form-horizontal form-bordered">

              <div :class="`form-group${formErrors.has('is_enabled') ? ' has-error' : ''}`">
                <label class="col-md-3 control-label">
                  Включена
                </label>

                <div class="col-md-9">
                  <label class="switch switch-primary">
                    <input v-model="items.is_enabled" type="checkbox">
                    <span />
                  </label>

                  <span v-show="formErrors.has('is_enabled')" class="help-block">
                    {{ formErrors.first('is_enabled') }}
                  </span>
                </div>
              </div>

              <div
                v-if="userCan('shop.attributes.edit-hidden-params')"
                :class="`form-group${formErrors.has('selectable') ? ' has-error' : ''}`"
              >
                <label class="col-md-3 control-label">
                  Процент скидки
                </label>

                <div class="col-md-9">
                  <label>
                    <input v-model="items.percent_of_discount" type="number" max="99" min="1" class="form-control">
                    <span />
                  </label>

                  <span v-show="formErrors.has('percent_of_discount')" class="help-block">
                    {{ formErrors.first('percent_of_discount') }}
                  </span>

                  <span class="help-block">
                    * Не более 99%.
                  </span>
                </div>
              </div>

              <div
                v-if="userCan('shop.attributes.edit-hidden-params')"
                :class="`form-group${formErrors.has('selectable') ? ' has-error' : ''}`"
              >
                <label class="col-md-3 control-label">
                  Время действия акции
                </label>

                <div class="col-md-9">
                  <label>
                    <input v-model="items.time_in_minutes" type="number" max="60" min="1" class="form-control">
                  </label>
                  <span />

                  <span v-show="formErrors.has('time_in_minutes')" class="help-block">
                    {{ formErrors.first('time_in_minutes') }}
                  </span>

                  <span class="help-block">
                    * в минутах.
                  </span>
                </div>
              </div>

              <div v-if="items.created_at" class="form-group">
                <label class="col-md-3 control-label">
                  Дата создания
                </label>

                <div class="col-md-9">
                  <p class="form-control-static">
                    {{ items.created_at }}
                  </p>
                </div>
              </div>

              <div v-if="items.updated_at" class="form-group">
                <label class="col-md-3 control-label">
                  Последнее изменение
                </label>

                <div class="col-md-9">
                  <p class="form-control-static">
                    {{ items.updated_at }}
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
import EntityPage from '../../mixins/EntityPage';
import Translatable from '../../mixins/Translatable';
import TablePage from '../../mixins/TablePage';

//    import bModal from 'bootstrap-vue/es/components/modal/modal'

export default {
  name: 'FastSaleEdit',

  directives: {
    Number,
  },

  mixins: [
    EntityPage,
    Translatable,
    TablePage,
  ],

  // components: {
  //     bModal,
  // },

  data() {
    return {
      entityName: 'fastSale',
      tableItemsDataName: 'fastSale',
      // fastSale: null,

      backgroundType: 'gradient',

      usedMainData: [
        'languages',
      ],

      bannerPlaces: [],
      bannerType$: this.bannerType || 'default',

      reloadDataOnSave: true,
    };
  },

  computed: {
    fastSale: {
      get: function() {
        return this.items;
      },

      set: function(newVal) {
        this.items = newVal;
      },

    },
  },
};
</script>

<style scoped>

</style>
