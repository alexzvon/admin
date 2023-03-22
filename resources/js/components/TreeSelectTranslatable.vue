<script>
  import TreeSelect from './TreeSelect'

  export default {
    name: "tree-select-translatable",

    props: [
      'options',
      'selected',
      'placeholder',
      'disabled',
      'multiple',
      'activeLanguageCode',
      'defaultLanguageCode',
      'params',
      'disableParents'
    ],

    data() {
      return {
        translatedOptions: [],
      }
    },

    watch: {
      options: 'translateOptions',
      activeLanguageCode: 'translateOptions'
    },

    components: {
      TreeSelect
    },

    methods: {
      getSelected() {
        return this.$refs.treeSelect.rSelected
      },

      // todo: аналогичная функция в PricesTable

      getTitle(item) {
        let title = item.i18n[this.activeLanguageCode].title

        if (! title) {
          if (this.defaultLanguageCode) {
            title = item.i18n[this.defaultLanguageCode].title
          }
          else {
            title = 'Не заполнено'
          }
        }

        return title
      },

      translateOptions() {
        let build = tree => {
          return tree.map(item => {
            let res = {
              ... item,
              title: this.getTitle(item)
            }

            if (item.children) {
              if (this.disableParents) {
                res.disabled = true
              }

              res.children = build(item.children)
            }

            return res
          })
        }

        this.translatedOptions = build(this.options || [])
      },
    },

    created() {
      this.translateOptions()
    },

    mounted() {
      ;(['update:selected', 'create:options']).forEach(eventName => {
        this.$refs.treeSelect.$on(eventName, data => {
          this.$emit(eventName, data)
        })
      })
    },
  }
</script>

<template>
  <tree-select
    ref="treeSelect"
    :options="translatedOptions"
    :selected="selected"
    :placeholder="placeholder"
    :disabled="disabled"
    :multiple="multiple"
    :params="params" />
</template>