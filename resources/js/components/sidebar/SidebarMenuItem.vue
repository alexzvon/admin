<template>
  <li>
    <div class="sidebar-nav-menu">
      <div class="sidebar-nav__item" @click="clickItem()">
        <span>
          <i class="sidebar-nav-icon" :class="item.icon" />
          <span class="sidebar-nav-mini-hide">{{ item.title }}</span>
        </span>
        <i
          v-if="hasChilds"
          class="fa sidebar-nav-indicator sidebar-nav-mini-hide"
          :class="[expandChildren ? 'fa-angle-down' : 'fa-angle-right']"
        />
      </div>
    </div>

    <div
      v-if="hasChilds"
      class="sidebar-nav-sub"
      :class="{active:expandChildren}"
    >
      <ul>
        <SidebarMenuItem
          v-for="item in item.children"
          v-if="item.hasOwnProperty('permission') ? $core.user.hasPermission(item.permission) : true"
          :key="item.title"
          :item="item"
        />
      </ul>
    </div>

  </li>
</template>

<script>

export default {
//    module.exports = {
  name: 'SidebarMenuItem',
  props: [
    'item',
  ],
  data() {
    return {
      expandChildren: false,
    };
  },
  computed: {
    hasChilds() {
      return this.item.hasOwnProperty('children')
        ? this.item.children.length > 0
        : false;
    },
  },
  methods: {
    clickItem() {
      if (this.item.hasOwnProperty('onClick')) {
        this.item.onClick();
      }
      if (this.item.hasOwnProperty('url')) {
        this.$router.push(this.item.url);
      } else {
        this.expandChildren = !this.expandChildren;
      }
    },
  },
  components: {
    SidebarMenuItem: require('./SidebarMenuItem.vue').default,
  },
};
</script>

<style lang="scss">
    .sidebar-nav-sub{
        height: 0;
        overflow: hidden;
        position: relative;
        transition: all .228s ease-out;
        transform: translate3d(0, 0, 0);
        & > ul{
            display: block;
            li a{
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }
        &.active {
            height: auto;
        }
    }
</style>
