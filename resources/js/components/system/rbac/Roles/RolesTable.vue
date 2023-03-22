<script>
//  import bModal from 'bootstrap-vue/es/components/modal/modal'

import TablePage from '../../../../mixins/TablePage';
import Toggle from '../../../Toggle';

import RolesTableModel from '../../../../resources/RolesTableModel';

export default {
  name: 'RolesTable',

  components: {
    // bModal,
    Toggle,
  },

  mixins: [
    TablePage,
  ],

  data() {
    return {
      tableItemsDataName: 'roles',
    };
  },

  methods: {
    initItems(items = []) {
      this.items = items.map(item => new RolesTableModel(item));
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
            Роли
          </strong>
        </h1>

        <div v-if="userCan('roles.create')" class="block-title-control">
          <router-link to="/system/rbac/roles/create" class="btn btn-sm btn-success active">
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

              <th style="width: 100%">
                Название
              </th>

              <th v-if="userCan('roles.delete')">
                <span class="table-column-delete" />
              </th>
            </tr>
          </thead>

          <tbody>
            <template v-if="items && items.length">
              <tr v-for="role in items">
                <td class="text-center">
                  <strong class="table-column-id">
                    <router-link :to="role.url">{{ role.id }}</router-link>
                  </strong>
                </td>

                <td style="width: 100%">
                  <router-link :to="role.url">{{ role.name }}</router-link>
                </td>

                <td v-if="userCan('roles.delete')">
                  <span class="table-column-delete">
                    <a class="btn btn-danger" @click="remove(role.id)">
                      <i class="fa fa-times" />
                    </a>
                  </span>
                </td>
              </tr>
            </template>

            <tr v-if="! (items && items.length)">
              <td class="text-center" colspan="4">
                Список ролей пуст.
              </td>
            </tr>
          </tbody>
        </table>

      </div>
    </div>

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

      Вы действительно хотите удалить поставщика?
    </b-modal>
  </div>
</template>
