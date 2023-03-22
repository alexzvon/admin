<script>
  import Core from '../../../../core'

  import Base from '../../../../mixins/Base'

  export default {
    name: 'permissions-groups-table-tree',

    mixins: [
      Base
    ],

    props: [
      'tree',
      'level',
      'statusChange',
      'remove',
      'parentId',
    ],

    data() {
      return {
        expanded: []
      }
    },

    methods: {
      expandAll() {
        let expanded = []

        this.tree.forEach(category => {
          if (category.children) {
            expanded.push(category.id)
          }
        })

        this.expanded = expanded
      },

      compressAll() {
        this.expanded = []
      },

      expand(categoryId) {
        const index = this.expanded.indexOf(categoryId)

        if (index === -1) {
          this.expanded.push(categoryId)
        }
        else {
          this.expanded.splice(index, 1)
        }
      },

      isExpanded(categoryId) {
        return this.expanded.indexOf(categoryId) !== -1
      }
    },

    computed: {
      sortedTree() {
        return this.tree.sort((a, b) => {
          return a.position - b.position
        })
      }
    },

    mounted() {
      this.eventsDestroyers = [
        Core.events.on('permissions-groups-expand-all', () => this.expandAll()),
        Core.events.on('permissions-groups-compress-all', () => this.compressAll())
      ]
    },

    beforeDestroy() {
      this.eventsDestroyers.forEach(destroyEventFunc => destroyEventFunc())
    },
  }
</script>

<template>
  <div :class="'ui-sortable table-group table-level-' + level">
    <div v-for="item in sortedTree" class="js-sort-item" :key="item.id">
      <input type="hidden" :value="item.id" name="ids">

      <div class="table-row">
        <div class="table-cell table-cell-column-sort table-sort-handler js-sort-handler">
          <span></span>
        </div>

        <div class="table-cell text-center table-cell-column-id">
          <router-link :to="item.url">
            <strong>
              {{ item.id }}
            </strong>
          </router-link>
        </div>

        <div class="table-cell lev">
          <span
            v-if="item.children"
            @click="expand(item.id)"
            :class="`btn btn-primary btn-expand${isExpanded(item.id) ? ' btn-alt' : ''}`">

            <i class="fa fa-plus" v-if="!isExpanded(item.id)"></i>
            <i class="fa fa-minus" v-else="isExpanded(item.id)"></i>
          </span>

          <router-link :to="item.url">
            <strong>{{ item.name }}</strong>
          </router-link>
        </div>

        <div v-if="userCan('item.delete')" class="table-cell table-cell-column-delete">
          <a class="btn btn-danger" @click="remove(item.id)">
            <i class="fa fa-times"></i>
          </a>
        </div>
      </div>

      <permissions-groups-table-tree
        v-if="item.children"
        v-show="isExpanded(item.id)"
        :tree="item.children"
        :level="parseInt(level) + 1"
        :statusChange="statusChange"
        :remove="remove"
        :parentId="item.id" />
    </div>
  </div>
</template>