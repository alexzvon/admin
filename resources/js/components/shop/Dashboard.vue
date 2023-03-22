<template>
  <div v-if="items.length > 0" class="content-header">
    <ul class="nav-horizontal text-center">
      <template v-for="item in preparedItems">
        <li
          v-if="item.url"
          :key="item.url"
          :class="{ active: isActive(item) }"
        >
          <router-link :to="item.url"><i :class="item.icon" /> {{ item.title }}</router-link>
        </li>
      </template>
    </ul>
  </div>
</template>

<script>
import Core from '../../core';
import Base from '../../mixins/Base';
import _ from 'lodash';

export default {
//    module.exports = {
  name: 'Dashboard',
  mixins: [Base],
  props: ['active'],
  data() {
    return {
      items: require('./../../../json/jsonMenuItems').default,
    };
  },
  computed: {
    preparedItems() {
      const items = [];
      _.each(this.items, item => {
        if (item.hasOwnProperty('url') && this.isItemPermissionAllow(item)) {
          items.push(item);
        }
        if (item.hasOwnProperty('children')) {
          _.each(item.children, child => {
            if (child.hasOwnProperty('url') && this.isItemPermissionAllow(child)) {
              items.push(child);
            }
          });
        }
      });
      return items;
    },
  },
  methods: {
    isActive(item) {
      return item.url && item.url.indexOf(this.active) !== -1;
    },
    isItemPermissionAllow(item) {
      if (item.hasOwnProperty('permission')) {
        return Core.user.can(item.permission);
      }
      return true;
    },
  },
};

</script>
