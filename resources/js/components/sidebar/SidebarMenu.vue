<template>
  <div>
    <ul class="sidebar-nav">
      <SidebarMenuItem
        v-for="item in items"
        v-if="item.hasOwnProperty('permission') ? $core.user.hasPermission(item.permission) : true"
        :key="item.title"
        :item="item"
      />
    </ul>
  </div>
</template>

<script>
// import _ from "lodash";
// import Core from "../../core";
import Base from '../../mixins/Base';

export default {
  name: 'SidebarMenu',

  mixins: [
    Base,
  ],
  data() {
    return {
      items: require('../../../json/jsonMenuItems').default,
    };
  },

  watch: {
    '$route': 'checkExpanded',
  },
  components: {
    SidebarMenuItem: require('./SidebarMenuItem.vue').default,
  },
  methods: {
    init() {
      [].forEach.call(document.querySelectorAll('.sidebar-nav-sub'), el => {
        el.setAttribute('data-height', this.getMenuHeight(el));
      });

      this.checkExpanded();
    },

    getMenuHeight(elMenu) {
      return elMenu.childNodes[0].scrollHeight;
    },

    getParents(el, selector = '*') {
      const parents = [];
      let p = el.parentNode;

      while (p !== this.$el) {
        if (p.matches(selector)) {
          parents.push(p);
        }

        el = p;
        p = p.parentNode;
      }

      return parents;
    },

    checkExpanded() {
      this.$nextTick(() => {
        const elsToCLose = this.$el.querySelectorAll('.sidebar-nav-menu.open:not(.manual) + .sidebar-nav-sub')

                    ;[].forEach.call(elsToCLose, elMenu => {
          this.closeItem(elMenu.previousSibling, elMenu);
        });

        const elActiveLink = this.$el.querySelector('a.active');

        if (!elActiveLink) {
          return;
        }

        const elClosestMenu = elActiveLink.closest('.sidebar-nav-sub');

        if (!elClosestMenu) {
          return;
        }

        this.expand(elClosestMenu.previousSibling, elClosestMenu);
      });
    },

    getChildrensHeight(elMenu) {
      return [].reduce.call(elMenu.querySelectorAll('.sidebar-nav-menu.open + .sidebar-nav-sub'), (acc, el) => {
        return acc + parseInt(el.getAttribute('data-height'));
      }, 0);
    },

    expandItem(elLink, elMenu) {
      elLink.classList.add('open');

      const height = parseInt(elMenu.getAttribute('data-height')) + this.getChildrensHeight(elMenu);
      elMenu.style.height = height + 'px';
    },

    expand(elLink, elMenu) {
      this.expandItem(elLink, elMenu)

      ;[].forEach.call(this.getParents(elLink, '.sidebar-nav-sub'), el => {
        this.expandItem(el.previousSibling, el);
      });
    },

    closeItem(elLink, elMenu) {
      elLink.classList.remove('open');
      elMenu.removeAttribute('style');
    },

    close(elLink, elMenu) {
      Array.prototype.slice.call(elMenu.querySelectorAll('.sidebar-nav-sub')).reverse().forEach(el => {
        this.closeItem(el.previousSibling, el);
      });

      this.closeItem(elLink, elMenu)

      ;[].forEach.call(this.getParents(elLink, '.sidebar-nav-sub'), el => {
        this.expandItem(el.previousSibling, el);
      });
    },

    expandToggle(elLink, elMenu) {
      if (elLink.classList.contains('open')) {
        this.close(elLink, elMenu);
      } else {
        this.expand(elLink, elMenu);
      }
    },

    expandLinkClick(elLink) {
      elLink.classList.toggle('manual');
      this.expandToggle(elLink, elLink.nextSibling);
    },

    prepareItems(menuItems = []) {
      return menuItems.reduce((acc, item) => {
        if (item.permission && !this.userCan(item.permission)) {
          return acc;
        }
        if (item.children instanceof Array && item.children.length > 0) {
          const children = this.prepareItems(item.children);
          if (children.length === 0) {
            return acc;
          }
          acc.push({ ...item, children });
          return acc;
        }
        acc.push(item);
        return acc;
      }, []);
    },
  },
};
</script>

<style lang="scss">
    .sidebar-nav {
        list-style: none;
        margin: 0;
        padding: 10px 0 0;
    }

    .sidebar-nav .sidebar-header:first-child {
        margin-top: 0;
    }

    .sidebar-nav__item {
        display: flex;
        flex-direction:row;
        align-items:center;
        justify-content:space-between;
        color: #eaedf1;
        padding: 0 10px 0 5px;
        min-height: 40px;
        line-height: 140%;
        border-left: 5px solid transparent;
        font-size: 14px;
    }

    .sidebar-nav__item:hover,
    .sidebar-nav__item:focus,
    .sidebar-nav__item.open,
    .sidebar-nav li.active > .sidebar-nav__item{
        color: #ffffff;
        text-decoration: none;
        background: rgba(0, 0, 0, 0.15);
    }

    .sidebar-nav__item.active {
        border-color: #1bbae1;
        background: rgba(0, 0, 0, 0.3);
    }

    .sidebar-nav__item > .sidebar-nav-indicator {
        float: right;
        line-height: inherit;
        margin-left: 4px;
        -webkit-transition: all 0.15s ease-out;
        transition: all 0.15s ease-out;
    }

    .sidebar-nav__item > .sidebar-nav-icon,
    .sidebar-nav__item > .sidebar-nav-indicator {
        display: inline-block;
        opacity: 0.5;
        width: 18px;
        font-size: 14px;
        text-align: center;
    }

    .sidebar-nav__item:hover,
    .sidebar-nav__item:hover > .sidebar-nav-icon,
    .sidebar-nav__item:hover > .sidebar-nav-indicator,
    .sidebar-nav__item.active,
    .sidebar-nav__item.active > .sidebar-nav-icon,
    .sidebar-nav__item.active > .sidebar-nav-indicator,
    .sidebar-nav__item.open,
    .sidebar-nav__item.open > .sidebar-nav-icon,
    .sidebar-nav__item.open > .sidebar-nav-indicator,
    .sidebar-nav li.active > a,
    .sidebar-nav li.active > a > .sidebar-nav-icon,
    .sidebar-nav li.active > a > .sidebar-nav-indicator {
        opacity: 1;
    }

    .sidebar-nav__item.active > .sidebar-nav-indicator,
    .sidebar-nav__item.open > .sidebar-nav-indicator,
    .sidebar-nav li.active > a > .sidebar-nav-indicator {
        -webkit-transform: rotate(-90deg);
        transform: rotate(-90deg);
    }

    .sidebar-nav ul {
        list-style: none;
        padding: 0;
        margin: 0;
        background: rgba(0, 0, 0, 0.3);
    }

    .sidebar-nav li.active > ul {
        display: block;
    }

    .sidebar-nav ul a {
        margin: 0;
        padding-left: 15px;
        min-height: 36px;
        line-height: 140%;
    }

    .sidebar-nav ul a.active,
    .sidebar-nav ul a.active:hover {
        border-color: #1bbae1;
    }

    .sidebar-nav ul ul {
        background: rgba(0, 0, 0, 0.4);
    }

    .sidebar-nav ul ul a {
        padding-left: 20px;
    }

    .sidebar-nav ul ul ul a {
        padding-left: 30px;
    }

    .sidebar-nav ul ul ul ul a {
        padding-left: 40px;
    }
    .sidebar-nav{
        &-icon {
            margin-right: 7px;
        }
        li {
            position: relative;
        }
        ul{
            display: block;
        }
    }
</style>
