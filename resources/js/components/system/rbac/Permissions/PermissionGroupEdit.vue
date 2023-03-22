<script>
import Core from '../../../../core';

import EntityPage from '../../../../mixins/EntityPage';
import Sortable from '../../../../mixins/Sortable';

import TreeSelect from '../../../../components/TreeSelect';

//  import bModal from 'bootstrap-vue/es/components/modal/modal'

import PermissionGroupEditModel from '../../../../resources/PermissionGroupEditModel';
import PermissionEditModel from '../../../../resources/PermissionEditModel';
import PermissionGroupsTreeSelectModel from '../../../../resources/PermissionGroupsTreeSelectModel';

export default {
  name: 'PermissionGroupEdit',

  components: {
    TreeSelect,
    // bModal,
  },

  mixins: [
    EntityPage,
    Sortable,
  ],

  props: [
    'id',
    'type',
  ],

  data() {
    return {
      entityName: 'permission-group',
      permissionGroup: null,
      permissions: [],
      groupsTree: [],
      validationErrors: [],
    };
  },

  watch: {
    permissions: 'refreshSort',
  },

  created() {
    this.$on('beforeDataLoad', a => {
      a.add(new Core.requestHandler('get', '/api/system/rbac/permission-groups'));
      a.onDone(data => {
        this.groupsTree = data['permission-groups'].map(item => new PermissionGroupsTreeSelectModel(item));
      });
    });
  },

  methods: {
    /**
       * Инициализация модели данных.
       */
    initEntity(data = {}) {
      this.setEntityData(new PermissionGroupEditModel(data));

      if (this.type === 'edit') {
        this.initPermissions(data.permissions);
      }
    },

    initPermissions(data = []) {
      this.permissions = this.getSortedData(data).map(item => this.makePermission(item));
    },

    getToSaveData() {
      return {
        ... this.getEntityModel(),
        permissions: this.getToSavePermissions(),
      };
    },

    getToSavePermissions() {
      return this.permissions.reduce((acc, permission) => {
        if (permission.deleted) {
          return acc;
        }

        const item = {
          name: permission.name,
          position: permission.position,
        };

        if (permission.isNew) {
          item.isNew = 1;
        }

        acc[permission.id] = item;

        return acc;
      }, {});
    },

    addPermission() {
      this.permissions = this.sortPermissions([
        ... this.permissions,
        this.makePermission(),
      ]);
    },

    makePermission(data = {}) {
      const item = new PermissionEditModel(data);

      if (data.id) {
        item.isNew = false;
      } else {
        item.isNew = true;
        item.position = this.findLastPosition() + 1;
      }

      return item;
    },

    findLastPosition() {
      return this.permissions.reduce((acc, { position }) => {
        return position > acc ? position : acc;
      }, 0);
    },

    removePermission(permission) {
      if (permission.isNew) {
        this.permissions = this.permissions.filter(item => item.id !== permission.id);
        return;
      }

      this.updatePermission(permission, {
        deleted: true,
      });

      this.getInitializedSortEls().disableSelection();
    },

    restorePermission(permission) {
      this.updatePermission(permission, {
        deleted: false,
      });
    },

    updatePermission(permission, data) {
      permission = {
        ... permission,
        ... data,
      };

      const permissions = this.permissions.map(item => {
        return permission.id === item.id ? permission : item;
      });

      this.permissions = this.sortPermissions(permissions);
    },

    changePosition() {
      this.permissions = this.sortPermissions(this.setDataBundlePositionsByIds(this.permissions, this.collectSortIds()));
    },

    sortPermissions(permissions) {
      return this.sortByPositionAndDeletedToEnd(permissions);
    },

    sortByPositionAndDeletedToEnd(data) {
      return data.sort((a, b) => {
        if (!a.deleted === !b.deleted) {
          return a.position - b.position;
        }

        return a.deleted ? 1 : -1;
      });
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
            Создание группы прав
          </strong>
        </h1>

        <div class="block-title-control">
          <a class="btn btn-sm btn-default btn-alt" @click="redirectToTable">
            <i class="fa fa-arrow-left" />
          </a>

          <span class="btn-separator-xs" />

          <a v-if="userCan('rbac.groups.create')" class="btn btn-sm btn-success active" @click="save">
            <i class="fa fa-plus-circle" /> Создать
          </a>
        </div>
      </div>

      <div v-if="type === 'edit'" class="block-title clearfix">
        <h1>
          <strong>
            Редактирование группы прав #{{ this.id }}
          </strong>
        </h1>

        <div class="block-title-control">
          <a class="btn btn-sm btn-default btn-alt" @click="redirectToTable">
            <i class="fa fa-arrow-left" />
          </a>

          <span class="btn-separator-xs" />

          <a v-if="userCan('rbac.groups.save')" class="btn btn-sm btn-primary active" @click="save">
            <i class="fa fa-floppy-o" /> Сохранить
          </a>

          <a v-if="userCan('rbac.groups.delete')" class="btn btn-sm btn-danger active" @click="remove">
            Удалить
          </a>
        </div>
      </div>

      <div v-if="permissionGroup" class="row">

        <div class="col-xl-6">

          <div class="block">
            <div class="block-title clearfix">
              <h2>
                <i class="fa fa-pencil" /> <strong>Основная</strong> информация
              </h2>
            </div>

            <div class="form-horizontal form-bordered">

              <div :class="`form-group${formErrors.has('name') ? ' has-error' : ''}`">
                <label class="col-md-3 control-label" for="name">
                  Название <span class="text-danger">*</span>
                </label>

                <div class="col-md-9">
                  <input
                    id="name"
                    v-model="permissionGroup.name"
                    v-validate="'required|max:255'"
                    type="text"
                    class="form-control"
                    name="name"
                  >

                  <span v-show="formErrors.has('name')" class="help-block">
                    {{ formErrors.first('name') }}
                  </span>
                </div>
              </div>

              <div v-if="groupsTree.length > 0" :class="`form-group${formErrors.has('parent_id') ? ' has-error' : ''}`">
                <label class="col-md-3 control-label">
                  Родительская группа
                </label>

                <div class="col-md-8">
                  <tree-select
                    :options="groupsTree"
                    :selected.sync="permissionGroup.parent_id"
                    :disabled="id"
                    placeholder="Выберите группу"
                  />

                  <span v-show="formErrors.has('parent_id')" class="help-block">
                    {{ formErrors.first('parent_id') }}
                  </span>
                </div>
              </div>

              <div v-if="permissionGroup.created_at" class="form-group">
                <label class="col-md-3 control-label">
                  Дата создания
                </label>

                <div class="col-md-9">
                  <p class="form-control-static">
                    {{ permissionGroup.created_at }}
                  </p>
                </div>
              </div>

              <div v-if="permissionGroup.updated_at" class="form-group">
                <label class="col-md-3 control-label">
                  Последнее изменение
                </label>

                <div class="col-md-9">
                  <p class="form-control-static">
                    {{ permissionGroup.updated_at }}
                  </p>
                </div>
              </div>

            </div>

          </div>
        </div>

        <div class="col-xl-6">
          <div class="block">
            <div class="block-title clearfix">
              <h2>
                <i class="fa fa-list" /> <strong>Значения</strong>
              </h2>

              <div class="block-title-control">
                <a class="btn btn-sm btn-primary active" @click="addPermission">
                  <i class="fa fa-plus-circle" /> Добавить
                </a>
              </div>
            </div>

            <div class="block-section table-responsive">
              <table class="table table-middle table-center table-condensed table-bordered table-hover dataTable table-sortable table-remove-restore">
                <tbody class="ui-sortable">
                  <tr v-for="permission in permissions" :key="permission.id" :class="{'js-sort-item': !permission.deleted, 'table-remove-restore__deleted': permission.deleted}">
                    <td :class="{'table-sort-handler': true, 'js-sort-handler': !permission.deleted}">
                      <span>
                        <input type="hidden" name="ids" :value="permission.id">
                      </span>
                    </td>

                    <td style="width: 100%">
                      <div :class="{'has-error': formErrors.has(`permissions.${permission.id}.name`)}">
                        <input
                          :id="`permission-${permission.id}`"
                          v-model="permission.name"
                          v-validate="'required|max:255'"
                          type="text"
                          class="form-control"
                          :name="`permissions.${permission.id}.name`"
                        >

                        <span v-show="formErrors.has(`permissions.${permission.id}.name`)" class="help-block" style="margin-bottom: 0;">
                          {{ formErrors.first(`permissions.${permission.id}.name`) }}
                        </span>
                      </div>
                    </td>

                    <td>
                      <div>
                        <div v-if="permission.isNew || userCan('rbac.groups.remove-permission')">
                          <a v-if="permission.deleted" class="btn btn-success table-remove-restore__restore" @click="restorePermission(permission)">
                            <i class="fa fa-repeat" />
                          </a>

                          <a v-else class="btn btn-danger" @click="removePermission(permission)">
                            <i class="fa fa-times" />
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

