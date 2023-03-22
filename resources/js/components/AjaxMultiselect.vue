<script>
    import _ from "lodash";
  import Core from '../core'
  import AxajMultiselectModel from '../resources/AxajMultiselectModel'
  import TreeSelectTranslatable from './TreeSelectTranslatable'

  export default {
    name: 'AjaxMultiselect',

    props: [
      'url',
      'languages',
      'activeLanguageCode',
      'selected',
      'options',
      'linkUrlMaker',
      'multiple'
    ],

    components: {
      TreeSelectTranslatable
    },

    watch: {
      options: 'updateOptions'
    },

    data() {
      return {
        request: null,
        options$: this.mapOptions(this.options),
        params: {
          ajax: {
            delay: 500,
            data: function (params) {
              return {
                q: params.term,
              };
            },

            transport: (params, success) => {
              if (this.request) {
                this.request.abort()
              }

              this.request = new Core.requestHandler('get', this.url, params.data)
                .success(success)
                .any(() => {
                  this.request = null
                })
                .start()
            },

            processResults: (response) => {
              return {
                results: this.mapOptions(response.data.result).map(option => ({
                  id: option.id,
                  text: option.i18n[this.activeLanguageCode].title,
                  option
                })),
                pagination: {
                  more: false
                }
              }
            },
          },

          templateSelection: option => {
            if (typeof this.linkUrlMaker === 'function') {
              return this.linkUrlMaker(option)
            }

            return option.text
          },

          events: {
            unselecting: e => {
              e.preventDefault()
              let optionId = e.params.args.data.id.toString()

              this.options$ = this.options$.filter(o => o.id.toString() !== optionId)
              let ids = this.options$.map(o => o.id)

              if (this.multiple) {
                this.$emit('update:selected', ids)
              }
              else {
                this.$emit('update:selected', ids.length ? ids[0] : '')
              }
            },

            selecting: e => {
              e.preventDefault()
              let option = e.params.args.data.option

              if (option) {
                if (! this.options$.find(o => o.id === option.id)) {
                  if (this.multiple) {
                    this.options$.push(option)

                    this.$emit('update:selected', [
                      ... this.selected,
                      option.id
                    ])
                  }
                  else {
                    this.options$ = [option]
                    this.$emit('update:selected', option.id)
                  }
                }
              }
            }
          }
        }
      }
    },

    methods: {
      updateOptions() {
        this.options$ = this.mapOptions(this.options)
      },

      mapOptions(data) {
        if (_.isNil(data)) {
          return []
        }

        if (! _.isArray(data)) {
          data = [data]
        }

        return data.map(item => new AxajMultiselectModel(item, this.languages))
      },
    },

    mounted() {
      ;(['update:selected']).forEach(eventName => {
        this.$refs.treeSelect.$on(eventName, data => {
          this.$emit(eventName, data)
        })
      })
    }
  }
</script>

<template>
  <tree-select-translatable
    ref="treeSelect"
    :languages="languages"
    :activeLanguageCode="activeLanguageCode"
    :options="options$"
    :selected="selected"
    :params="params"
    :multiple="!! multiple"
    placeholder="Поиск"
  ></tree-select-translatable>
</template>