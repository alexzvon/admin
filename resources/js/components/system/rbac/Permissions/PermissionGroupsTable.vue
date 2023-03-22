<script>
import Core from '../../../../core';

import PermissionGroupsTableTree from './PermissionGroupsTableTree';

import TablePage from '../../../../mixins/TablePage';
import Sortable from '../../../../mixins/Sortable';
import StatusChangeable from '../../../../mixins/StatusChangeable';

//  import bModal from 'bootstrap-vue/es/components/modal/modal'

import PermissionGroupsTableModel from '../../../../resources/PermissionGroupsTableModel';

export default {
  name: 'PermissionsGroupsTable',

  components: {
    // bModal,
    PermissionGroupsTableTree,
  },

  mixins: [
    Sortable,
    StatusChangeable,
    TablePage,
  ],

  data() {
    return {
      tableItemsDataName: 'permission-groups',
    };
  },

  methods: {
    initItems(items = []) {
      this.items = this.getSortedData(items).map(item => new PermissionGroupsTableModel(item));
    },

    getSortParams() {
      return {
        ... this.getDefaultSortParams(),
        items: '> .js-sort-item',
      };
    },

    expandAll() {
      Core.events.trigger('permissions-groups-expand-all');
    },

    compressAll() {
      Core.events.trigger('permissions-groups-compress-all');
    },
  },
};
</script>

<template>
  <div>
    <div class="block full">
      <div class="block-title clearfix">
        <h1><strong>Права</strong></h1>

        <div class="block-title-control">
          <a class="btn btn-default btn-sm btn-alt" @click="expandAll">
            <i class="fa fa-expand" /> Раскрыть все
          </a>

          <a class="btn btn-default btn-sm btn-alt" @click="compressAll">
            <i class="fa fa-compress" /> Свернуть все
          </a>

          <span class="btn-separator-xs" />

          <router-link v-if="userCan('permissions.create')" to="/system/rbac/permission-groups/create" class="btn btn-sm btn-success active">
            <i class="fa fa-plus-circle" /> Создать
          </router-link>
        </div>
      </div>

      <div class="table-responsive">

        <div class="table table-middle table-center table-condensed table-bordered table-hover table-sortable">
          <div class="table-head">
            <div class="table-row">
              <div v-if="userCan('permissions.edit')" class="table-cell table-cell-column-sort" />

              <div class="table-cell table-cell-column-id">
                ID
              </div>

              <div class="table-cell">
                Название
              </div>

              <div v-if="userCan('permissions.delete')" class="table-cell table-cell-column-delete" />
            </div>
          </div>

          <permission-groups-table-tree
            v-if="items && items.length"
            :tree="items"
            :level="0"
            :status-change="statusChange"
            :remove="remove"
          />

          <div v-if="!items.length" class="table-group">
            <div class="table-row">
              <div class="table-cell table-cell-empty text-center">
                Список групп прав пуст.
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

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

      Вы действительно хотите удалить эту группу?
    </b-modal>
  </div>
</template>
