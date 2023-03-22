<script>
import Core from '../../../core';

//  import bModal from 'bootstrap-vue/es/components/modal/modal'

import TreeSelect from '../../TreeSelect';

import EntityPage from '../../../mixins/EntityPage';
import ImageUpload from '../../../mixins/ImageUpload';

import AdminEditModel from '../../../resources/AdminEditModel';

export default {
  name: 'AdminEdit',

  components: {
    // bModal,
    TreeSelect,
  },

  mixins: [
    EntityPage,
    ImageUpload,
  ],

  props: [
    'id',
  ],

  data() {
    return {
      entityName: 'admin',
      admin: null,
      roles: [],
      usedMainData: [
        'roles',
      ],
    };
  },

  methods: {
    initEntity(data) {
      this.setEntityData(new AdminEditModel(data));

      const entity = this.getEntityModel();

      Core.events.trigger('system.avatar-changed', entity.id, entity.image ? entity.image.small.srcset : false);
    },

    initRoles(roles = []) {
      this.roles = this.getSortedData(roles).map(({ id, name }) => ({
        id,
        title: name,
      }));
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
            Создание администратора
          </strong>
        </h1>

        <div class="block-title-control">
          <a class="btn btn-sm btn-default btn-alt" @click="redirectToTable">
            <i class="fa fa-arrow-left" />
          </a>

          <span class="btn-separator-xs" />

          <a v-if="userCan('admins.create')" class="btn btn-sm btn-success active" @click="save">
            <i class="fa fa-plus-circle" /> Создать
          </a>
        </div>
      </div>

      <div v-if="type === 'edit'" class="block-title clearfix">
        <h1>
          <strong>
            Редактирование администратора #{{ this.id }}
          </strong>
        </h1>

        <div class="block-title-control">
          <a class="btn btn-sm btn-default btn-alt" @click="redirectToTable">
            <i class="fa fa-arrow-left" />
          </a>

          <span class="btn-separator-xs" />

          <a v-if="userCan('admins.edit')" class="btn btn-sm btn-primary active" @click="save">
            <i class="fa fa-floppy-o" /> Сохранить
          </a>

          <a v-if="userCan('admins.delete')" class="btn btn-sm btn-danger active" @click="remove">
            Удалить
          </a>
        </div>
      </div>

      <div v-if="admin" class="form-horizontal form-bordered">
        <div :class="`form-group${formErrors.has('name') ? ' has-error' : ''}`">
          <label class="col-md-3 control-label" for="name">
            Имя <span class="text-danger">*</span>
          </label>

          <div class="col-md-9">
            <input id="name" v-model="admin.name" v-validate="'required|max:255'" type="text" class="form-control" name="name">

            <span v-show="formErrors.has('name')" class="help-block">
              {{ formErrors.first('name') }}
            </span>
          </div>
        </div>

        <div :class="`form-group${formErrors.has('name') ? ' has-error' : ''}`">
          <label class="col-md-3 control-label" for="email">
            Email <span class="text-danger">*</span>
          </label>

          <div class="col-md-9">
            <input id="email" v-model="admin.email" v-validate="'required|email|max:255'" type="email" class="form-control" name="email">

            <span v-show="formErrors.has('email')" class="help-block">
              {{ formErrors.first('email') }}
            </span>
          </div>
        </div>

        <div :class="`form-group${formErrors.has('roles') ? ' has-error' : ''}`">
          <label class="col-md-3 control-label">
            Роли
          </label>

          <div class="col-md-8">
            <tree-select
              :options="roles"
              :selected.sync="admin.roles"
              :multiple="true"
              :params="{closeOnSelect: false}"
              placeholder="Выберите роль"
            />

            <span v-show="formErrors.has('roles')" class="help-block">
              {{ formErrors.first('roles') }}
            </span>
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-3 control-label">
            Аватар
          </label>

          <div class="col-md-9">
            <dropzone-gallery
              v-if="type === 'edit'"
              ref="gallery"
              :params="{maxFiles: 1}"
              :url="makePageApiUrl('image')"
              :images="dropzoneImage"
              :safe-delete="false"
              :errors="formErrors"
              @update:images="updateImage"
            />
          </div>
        </div>

        <div :class="`form-group${formErrors.has('enabled') ? ' has-error' : ''}`">
          <label class="col-md-3 control-label">
            Включен
          </label>

          <div class="col-md-9">
            <label class="switch switch-primary">
              <input v-model="admin.enabled" type="checkbox">
              <span />
            </label>

            <span v-show="formErrors.has('enabled')" class="help-block">
              {{ formErrors.first('enabled') }}
            </span>
          </div>
        </div>

        <div v-if="admin.created_at" class="form-group">
          <label class="col-md-3 control-label">
            Дата создания
          </label>

          <div class="col-md-9">
            <p class="form-control-static">
              {{ admin.created_at }}
            </p>
          </div>
        </div>

        <div v-if="admin.updated_at" class="form-group">
          <label class="col-md-3 control-label">
            Последнее изменение
          </label>

          <div class="col-md-9">
            <p class="form-control-static">
              {{ admin.updated_at }}
            </p>
          </div>
        </div>

      </div>

      <div class="block full">
        <div class="block-title clearfix">
          <h1>
            <strong>
              Изменение пароля
            </strong>
          </h1>
        </div>

        <div v-if="admin" class="form-horizontal form-bordered">
          <div :class="`form-group${formErrors.has('old_password') ? ' has-error' : ''}`">
            <label class="col-md-3 control-label" for="old-password">
              Старый <span class="text-danger">*</span>
            </label>

            <div class="col-md-9">
              <input id="old-password" v-model="admin['old_password']" v-validate="'max:255'" type="password" class="form-control" name="old_password">

              <span v-show="formErrors.has('old_password')" class="help-block">
                {{ formErrors.first('old_password') }}
              </span>
            </div>
          </div>

          <div :class="`form-group${formErrors.has('new_password') ? ' has-error' : ''}`">
            <label class="col-md-3 control-label" for="new-password">
              Новый <span class="text-danger">*</span>
            </label>

            <div class="col-md-9">
              <input id="new-password" v-model="admin['new_password']" v-validate="'min:8|max:255'" type="password" class="form-control" name="new_password">

              <span v-show="formErrors.has('new_password')" class="help-block">
                {{ formErrors.first('new_password') }}
              </span>
            </div>
          </div>

          <div :class="`form-group${formErrors.has('new_password_confirmation') ? ' has-error' : ''}`">
            <label class="col-md-3 control-label" for="confirm-password">
              Подтверждение <span class="text-danger">*</span>
            </label>

            <div class="col-md-9">
              <input id="confirm-password" v-model="admin['new_password_confirmation']" v-validate="'max:255|confirmed:new_password'" type="password" class="form-control" name="new_password_confirmation">

              <span v-show="formErrors.has('new_password_confirmation')" class="help-block">
                {{ formErrors.first('new_password_confirmation') }}
              </span>
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
      title="Удаление поставщика"
      title-tag="h3"
      centered
      ok-title="Удалить"
      cancel-title="Отмена"
      hide-header-close
      @ok="removeConfirm"
    >

      Вы действительно хотите удалить этого администратора?
    </b-modal>
  </div>
</template>

