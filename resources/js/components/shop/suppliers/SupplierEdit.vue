<template>
  <div>

    <div class="block full">

      <template v-if="type === 'create'">

        <div class="block-title clearfix">
          <h1>Создание поставщика</h1>
          <div class="block-title-control">
            <a class="btn btn-sm btn-default btn-alt" @click="redirectToTable">
              <i class="fa fa-arrow-left" />
            </a>
            <span class="btn-separator-xs" />
            <a v-if="$core.user.can('shop.suppliers.create')" class="btn btn-sm btn-success active" @click="save">
              <i class="fa fa-plus-circle" /> Создать
            </a>
          </div>
        </div>
      </template>

      <template v-if="type === 'edit'">
        <div v-if="type === 'edit'" class="block-title clearfix">
          <h1>Редактирование поставщика #{{ this.id }}</h1>
          <div class="block-title-control">
            <a class="btn btn-sm btn-default btn-alt" @click="redirectToTable">
              <i class="fa fa-arrow-left" />
            </a>
            <span class="btn-separator-xs" />
            <a v-if="$core.user.can('shop.suppliers.edit')" class="btn btn-sm btn-primary active" @click="save">
              <i class="fa fa-floppy-o" /> Сохранить
            </a>
            <a v-if="$core.user.can('shop.suppliers.delete')" class="btn btn-sm btn-danger active" @click="remove">
              Удалить
            </a>
          </div>
        </div>

      </template>

      <div v-if="supplier" class="form-horizontal form-bordered">

        <div :class="`form-group${formErrors.has('name') ? ' has-error' : ''}`">
          <label class="col-md-3 control-label" for="name">
            Название <span class="text-danger">*</span>
          </label>
          <div class="col-md-9">
            <input id="name" v-model="supplier.name" v-validate="'required|max:255'" type="text" class="form-control" name="name">
            <span v-show="formErrors.has('name')" class="help-block">
              {{ formErrors.first("name") }}
            </span>
          </div>
        </div>

        <div :class="`form-group${formErrors.has('description') ? ' has-error' : ''}`">
          <label class="col-md-3 control-label" for="description">
            Описание
          </label>
          <div class="col-md-9">
            <wysiwyg
              placeholder="Описание.."
              :html="supplier.description"
            />
            <!--<ckeditor id="description" :content.sync="supplier.description" name="description"/>-->
            <span v-show="formErrors.has('description')" class="help-block">
              {{ formErrors.first("description") }}
            </span>
          </div>
        </div>

        <div :class="`form-group${formErrors.has('enabled') ? ' has-error' : ''}`">
          <label class="col-md-3 control-label">
            Опубликовано
          </label>
          <div class="col-md-9">
            <label class="switch switch-primary">
              <input v-model="supplier.enabled" type="checkbox">
              <span />
            </label>
            <span v-show="formErrors.has('enabled')" class="help-block">
              {{ formErrors.first("enabled") }}
            </span>
          </div>
        </div>
        <div :class="`form-group${formErrors.has('enabled') ? ' has-error' : ''}`">
          <label class="col-md-3 control-label">
            Город расположения склада
          </label>
          <div class="col-md-9">
            <input
              id="city"
              v-model="supplier.warehouse_city"
              v-validate="'required|max:255'"
              type="text"
              class="form-control"
              name="warehouse_city"
            >
            <span v-show="formErrors.has('warehouse_city')" class="help-block">
              {{ formErrors.first("warehouse_city") }}
            </span>
          </div>
        </div>

        <div v-if="supplier.created_at" class="form-group">
          <label class="col-md-3 control-label">
            Дата создания
          </label>
          <div class="col-md-9">
            <p class="form-control-static">{{ supplier.created_at }}</p>
          </div>
        </div>

        <div v-if="supplier.updated_at" class="form-group">
          <label class="col-md-3 control-label">
            Последнее изменение
          </label>
          <div class="col-md-9">
            <p class="form-control-static">{{ supplier.updated_at }}</p>
          </div>
        </div>

      </div>
    </div>

    <div class="card mb-50">
      <div class="card__title">Свойства товаров поставщика</div>
      <div class="card__content">

        <attributor-add
          relation="supplier"
          :relation_id="+id"
          :only-global="false"
        />

        <attributor-edit
          v-for="attribute in $store.getters['attributes/usedAttributes']"
          :id="attribute.id"
          :key="'attributeEdit_'+attribute.id"
          relation="supplier"
          :relation_id="+id"
          :options-editable="true"
        />

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
      title="Удаление поставщика"
      title-tag="h3"
      centered
      ok-title="Удалить"
      cancel-title="Отмена"
      hide-header-close
      @ok="removeConfirm"
    >

      Вы действительно хотите удалить этого поставщика?
    </b-modal>
  </div>
</template>

<script>
//    import bModal from "bootstrap-vue/es/components/modal/modal";

import CKEditor from '../../CKEditor';
import LanguagePicker from '../../LanguagePicker';

import EntityPage from '../../../mixins/EntityPage';

import SupplierModel from '../../../resources/shop/SupplierModel';

export default {
  name: 'SupplierEdit',

  components: {
    'ckeditor': CKEditor,
    LanguagePicker,
    //            bModal
  },

  mixins: [
    EntityPage,
  ],

  props: [
    'id',
  ],

  data() {
    return {
      entityName: 'supplier',
      supplier: null,
    };
  },
  beforeMount() {
    this.$store.dispatch('priceTypes/get');
    this.$store.dispatch('categories/get');
    this.$store.dispatch('attributes/loadData');
    this.getAttributeRelations();
  },

  methods: {
    initEntity(data) {
      this.setEntityData(new SupplierModel(data));
    },
    addAttributeOption(payload) {
      payload.relation = 'supplier';
      payload.relation_id = this.id;
      payload.enabled = true;
      if (!this.$store.getters['attributes/isOptionIdUsed'](payload.option_id)) {
        this.$store.dispatch('attributes/addAttributeOptionToSupplier', payload).then(() => {
          this.getAttributeRelations();
        });
      }
    },
    deleteAttributeOption(payload) {
      this.$store.dispatch('attributes/deleteAttributeOptionFromSupplier', {
        relation: 'supplier',
        relation_id: +this.id,
        attribute_id: +payload.attribute_id,
        option_id: +payload.option_id,
      });
    },
    getAttributeRelations() {
      return this.$store.dispatch('attributes/getAttributesAndOptionsByRelation', {
        relation: 'supplier',
        relation_id: +this.id,
      });
    },
  },
};
</script>

