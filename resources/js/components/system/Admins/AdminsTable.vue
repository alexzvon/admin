<script>
//  import bModal from 'bootstrap-vue/es/components/modal/modal'

import TablePage from '../../../mixins/TablePage';
import StatusChangeable from '../../../mixins/StatusChangeable';
import Toggle from '../../Toggle';

import AdminsTableModel from '../../../resources/AdminsTableModel';

export default {
  name: 'AdminsTable',

  components: {
    // bModal,
    Toggle,
  },

  mixins: [
    StatusChangeable,
    TablePage,
  ],

  data() {
    return {
      tableItemsDataName: 'admins',
      roles: [],
      usedMainData: [
        'roles',
      ],
    };
  },

  methods: {
    initItems(items = []) {
      this.items = items.sort((a, b) => b.id - a.id).map(item => new AdminsTableModel(item));
    },

    getRoles(admin) {
      return admin.roles.map(roleId => {
        return this.roles.find(item => item.id === roleId)['name'];
      }).join(', ');
    },
  },
};
</script>

<template>
  <div>
    <div class="block full">
      <div class="block-title clearfix">
        <h1>
          <strong>
            Администраторы
          </strong>
        </h1>

        <div v-if="userCan('admins.create')" class="block-title-control">
          <router-link to="/system/admins/create" class="btn btn-sm btn-success active">
            <i class="fa fa-plus-circle" /> Создать
          </router-link>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-middle table-center table-condensed table-bordered table-hover">
          <thead>
            <tr>
              <th>
                <span class="table-column-id">
                  ID
                </span>
              </th>

              <th style="width: 50%">
                Название
              </th>

              <th style="width: 50%">
                Роли
              </th>

              <th v-if="userCan('admins.edit')">
                <span class="table-column-enabled">
                  Статус
                </span>
              </th>

              <th v-if="userCan('admins.delete')">
                <span class="table-column-delete" />
              </th>
            </tr>
          </thead>

          <tbody>
            <template v-if="items && items.length">
              <tr v-for="admin in items">
                <td class="text-center">
                  <strong class="table-column-id">
                    <router-link :to="admin.url">{{ admin.id }}</router-link>
                  </strong>
                </td>

                <td style="width: 50%">
                  <router-link :to="admin.url">{{ admin.name }}</router-link>
                </td>

                <td style="width: 50%" v-html="getRoles(admin)" />

                <td v-if="userCan('admins.edit')">
                  <span class="table-column-enabled">
                    <toggle :key="admin.id" :checked="admin.enabled" @change="statusChange(admin.id)" />
                  </span>
                </td>

                <td v-if="userCan('admins.delete')">
                  <span class="table-column-delete">
                    <a class="btn btn-danger" @click="remove(admin.id)">
                      <i class="fa fa-times" />
                    </a>
                  </span>
                </td>
              </tr>
            </template>

            <tr v-if="! (items && items.length)">
              <td class="text-center" colspan="5">
                Список администраторов пуст.
              </td>
            </tr>
          </tbody>
        </table>

      </div>
    </div>

    <b-modal
      id="removeModal"
      ref="removeModal"
      title="Удаление администратора"
      title-tag="h3"
      centered
      ok-title="Удалить"
      cancel-title="Отмена"
      hide-header-close
      @ok="removeConfirm"
    >

      Вы действительно хотите удалить администратора?
    </b-modal>
  </div>
</template>
