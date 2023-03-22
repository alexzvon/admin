<script>
  import DataHandler from '../../../mixins/DataHandler'
  import BadgePreview from './BadgePreview'
  import Loading from '../../Loading'

  export default {
    name: 'badges-select',

    mixins: [
      DataHandler,
    ],

    components: {
      BadgePreview,
      Loading
    },

    props: {
      id: null,
      languages: null,
      activeLanguageCode: null,
      selected: {
        default() {
          return []
        }
      }
    },

    data() {
      return {
        usedMainData: [
          'badge-types',
        ],

        badgeTypes: null,
        loading: true,
      }
    },

    created() {
      this.loadData()
        .then(() => this.loading = false)
    },

    methods: {
      isActive(badgeType) {
        return this.selected.indexOf(badgeType.id) !== -1
      },

      toggle(badgeType) {
        if (this.isActive(badgeType)) {
          this.select(this.selected.filter(id => badgeType.id !== id))
        }
        else {
          this.select([
            ... this.selected,
            badgeType.id
          ])
        }
      },

      select(selected) {
        this.$emit('update:selected', selected)
      }
    }
  }
</script>

<template>
  <loading :loading="loading" style="min-height: 32px">
    <div class="badges-select">
      <div class="badges-select__container">
        <div v-for="badgeType in badgeTypes" :key="badgeType.id" class="badges-select__item">
          <div @click="toggle(badgeType)" :class="{'badges-select__button': true, 'is-active': isActive(badgeType)}">
            <badge-preview v-if="!badgeType.text"
              :icon="badgeType.icon"
              :color="badgeType.color"
              :title="badgeType.i18n[activeLanguageCode].title"
            ></badge-preview>
              <badge-preview v-else
              :text="badgeType.text"
              :color="badgeType.color"
              :title="badgeType.i18n[activeLanguageCode].title"
            ></badge-preview>
          </div>
        </div>
      </div>
    </div>
  </loading>
</template>

