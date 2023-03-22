<script>
  import Core from '../../../core'

  import Toggle from '../../Toggle'
  import Base from '../../../mixins/Base'

  export default {
    name: 'categories-table-tree',

    mixins: [
      Base
    ],

    props: [
      'tree',
      'level',
      'statusChange',
      'remove',
      'parentId',
      'activeLanguageCode'
    ],

    data() {
      return {
        expanded: []
      }
    },

    components: {
      'toggle': Toggle,
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
        Core.events.on('categories-expand-all', () => this.expandAll()),
        Core.events.on('categories-compress-all', () => this.compressAll())
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
        <div v-if="userCan('shop.categories.edit')" class="table-cell table-cell-column-sort table-sort-handler js-sort-handler">
          <span></span>
        </div>

        <div class="table-cell text-center table-cell-column-id">
          <router-link :to="item.url">
            {{ item.id }}
          </router-link>
        </div>

        <div class="table-cell text-center table-cell-column-image">
          <router-link :to="item.url">
            <div class="product-preview-image">
              <img :src="$helpers.getImagePath(item.image.src)" :srcset="`${$helpers.getImagePath(item.image.srcset)} 2x`">
            </div>
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
            <span v-html="item.i18n[activeLanguageCode].title"></span>
          </router-link>
        </div>

        <div class="table-cell table-cell-column-slug">
          <a :href="item.siteUrl" target="_blank" rel="external">
            <i class="fa fa-external-link"></i>

            <div class="table-slug">
              {{ item.slug }}
            </div>
          </a>
        </div>

        <div class="table-cell table-cell-column-num">
          {{ item.products_count }}
        </div>

        <div v-if="userCan('shop.categories.edit')" class="table-cell table-cell-column-enabled">
          <toggle @change="statusChange(item.id)" :checked="item.enabled" :key="item.id" />
        </div>

        <div v-if="userCan('shop.categories.delete')" class="table-cell table-cell-column-delete">
          <a class="btn btn-danger" @click="remove(item.id)">
            <i class="fa fa-times"></i>
          </a>
        </div>
      </div>

      <categories-table-tree
        v-if="item.children"
        v-show="isExpanded(item.id)"
        :tree="item.children"
        :level="parseInt(level) + 1"
        :statusChange="statusChange"
        :remove="remove"
        :activeLanguageCode="activeLanguageCode"
        :parentId="item.id"
      ></categories-table-tree>
    </div>
  </div>
</template>