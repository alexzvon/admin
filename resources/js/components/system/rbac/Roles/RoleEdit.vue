<script>
import Core from '../../../../core';

import Toggle from '../../../Toggle';

//  import bModal from 'bootstrap-vue/es/components/modal/modal'

import EntityPage from '../../../../mixins/EntityPage';

import number from '../../../../directives/number';

import RoleEditModel from '../../../../resources/RoleEditModel';

export default {
  name: 'RoleEdit',

  components: {
    // bModal,
    Toggle,
  },

  mixins: [
    EntityPage,
  ],

  props: [
    'id',
  ],

  directives: {
    ... number,
  },

  data() {
    return {
      entityName: 'role',
      role: null,
      permissionGroups: [],
      selectedPermissions: [],
    };
  },

  created() {
    this.$on('beforeDataLoad', a => {
      a.add(new Core.requestHandler('get', '/api/system/rbac/permission-groups/all'));

      a.onDone(data => {
        this.permissionGroups = this.makeGroup(data['permission-groups']);
      });
    });
  },

  methods: {
    initEntity(data = {}) {
      this.setEntityData(new RoleEditModel(data));
      this.selectedPermissions = data.permissions || [];
    },

    // todo: это чудо надо понять, простить и переделать
    makeGroup(list = [], acc = []) {
      return list.reduce((acc, item) => {
        if (item.permissions instanceof Array && item.permissions.length > 0) {
          const group = {
            id: item.id,
            namespace: item.namespace,
            permissions: item.permissions.map(({ id, name }) => ({
              id,
              name,
              enabled: this.selectedPermissions.indexOf(id) !== -1,
            })),
          };

          this.checkGroupEnabled(group);

          acc.push(group);
        }

        if (item.children instanceof Array && item.children.length > 0) {
          this.makeGroup(item.children, acc);
        }

        return acc;
      }, acc);
    },

    checkGroupEnabled(group) {
      for (let i = 0; i < group.permissions.length; i++) {
        if (group.permissions[i].enabled) {
          group.enabled = true;
          return;
        }
      }

      group.enabled = false;
    },

    statusChange(permission, group) {
      permission.enabled = !permission.enabled;

      this.checkGroupEnabled(group);
    },

    statusChangeAll(group) {
      group.enabled = !group.enabled;

      group.permissions.forEach(permission => {
        permission.enabled = group.enabled;
      });
    },

    getToSaveData() {
      return {
        ... this.getEntityModel(),
        permissions: this.getPermissionsToSave(),
      };
    },

    getPermissionsToSave() {
      return this.permissionGroups.reduce((acc, group) => {
        return group.permissions.reduce((acc, permission) => {
          if (permission.enabled) {
            acc.push(permission.id);
          }

          return acc;
        }, acc);
      }, []);
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
            Создание роли
          </strong>
        </h1>

        <div class="block-title-control">
          <a class="btn btn-sm btn-default btn-alt" @click="redirectToTable">
            <i class="fa fa-arrow-left" />
          </a>

          <span class="btn-separator-xs" />

          <a v-if="userCan('roles.create')" class="btn btn-sm btn-success active" @click="save">
            <i class="fa fa-plus-circle" /> Создать
          </a>
        </div>
      </div>

      <div v-if="type === 'edit'" class="block-title clearfix">
        <h1>
          <strong>
            Редактирование роли #{{ this.id }}
          </strong>
        </h1>

        <div class="block-title-control">
          <a class="btn btn-sm btn-default btn-alt" @click="redirectToTable">
            <i class="fa fa-arrow-left" />
          </a>

          <span class="btn-separator-xs" />

          <a v-if="userCan('roles.edit')" class="btn btn-sm btn-primary active" @click="save">
            <i class="fa fa-floppy-o" /> Сохранить
          </a>

          <a v-if="userCan('roles.delete')" class="btn btn-sm btn-danger active" @click="remove">
            Удалить
          </a>
        </div>
      </div>

      <div class="row">

        <div class="col-sm-12">

          <div class="block">
            <div class="block-title clearfix">
              <h2>
                <i class="fa fa-pencil" /> <strong>Основная</strong> информация
              </h2>
            </div>

            <div v-if="role" class="form-horizontal form-bordered">
              <div :class="`form-group${formErrors.has('name') ? ' has-error' : ''}`">
                <label class="col-md-3 control-label" for="name">
                  Название <span class="text-danger">*</span>
                </label>

                <div class="col-md-9">
                  <input id="name" v-model="role.name" v-validate="'required|max:255'" type="text" class="form-control" name="name">

                  <span v-show="formErrors.has('name')" class="help-block">
                    {{ formErrors.first('name') }}
                  </span>
                </div>
              </div>

              <div :class="`form-group${formErrors.has('importance') ? ' has-error' : ''}`">
                <label class="col-md-3 control-label" for="importance">
                  Важность <span class="text-danger">*</span>
                </label>

                <div class="col-md-9">
                  <input id="importance" v-model="role.importance" v-number v-validate="'required|integer|min_value:1|max_value:999'" type="text" class="form-control" name="importance">

                  <span class="help-block">
                    * чем больше - тем менее важна роль
                  </span>

                  <span v-show="formErrors.has('importance')" class="help-block">
                    {{ formErrors.first('importance') }}
                  </span>
                </div>
              </div>

              <div v-if="role.created_at" class="form-group">
                <label class="col-md-3 control-label">
                  Дата создания
                </label>

                <div class="col-md-9">
                  <p class="form-control-static">
                    {{ role.created_at }}
                  </p>
                </div>
              </div>

              <div v-if="role.updated_at" class="form-group">
                <label class="col-md-3 control-label">
                  Последнее изменение
                </label>

                <div class="col-md-9">
                  <p class="form-control-static">
                    {{ role.updated_at }}
                  </p>
                </div>
              </div>

            </div>
          </div>
        </div>

        <div v-if="permissionGroups.length > 0" class="col-sm-12">
          <div class="block">
            <div class="block-title clearfix">
              <h2>
                <i class="fa fa-star" /> <strong>Права</strong>
              </h2>
            </div>

            <div class="row" style="display: flex; flex-wrap: wrap">
              <div v-for="group in permissionGroups" :key="group.id" class="col-sm-6 col-lg-4 col-xl-3">
                <table class="table table-bordered table-middle">
                  <thead>
                    <tr class="active">
                      <th>
                        <div class="table-column-enabled">
                          <toggle :checked="group.enabled" class="switch-success" @change="statusChangeAll(group)" />
                        </div>
                      </th>

                      <th style="width:100%">
                        <span style="cursor: pointer;" @click="statusChangeAll(group)">
                          {{ group.namespace }}
                        </span>
                      </th>
                    </tr>
                  </thead>

                  <tbody>
                    <tr v-for="permission in group.permissions" :key="permission.id">
                      <td>
                        <div class="table-column-enabled">
                          <toggle :checked="permission.enabled" @change="statusChange(permission, group)" />
                        </div>
                      </td>

                      <td style="width:100%">
                        {{ permission.name }}
                      </td>
                    </tr>
                  </tbody>
                </table>
                <div />
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
      title="Удаление роли"
      title-tag="h3"
      centered
      ok-title="Удалить"
      cancel-title="Отмена"
      hide-header-close
      @ok="removeConfirm"
    >

      Вы действительно хотите удалить эту роль?
    </b-modal>
  </div>
</template>

