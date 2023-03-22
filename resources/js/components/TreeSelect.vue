<script>
  import 'select2-bootstrap-theme/dist/select2-bootstrap.css'

  export default {
    name: 'tree-select',

    props: [
      'options',
      'selected',
      'placeholder',
      'disabled',
      'multiple',
      'params',
    ],

    data() {
      return {
        inited: false,
        destroyed: false,
        rSelected: null,
        buildedOptions: []
      }
    },

    watch: {
      'options': 'reset',
      'selected': 'select',
    },

    methods: {
      getSelected() {
        let findEnabledOption = (id) => {
          return (this.buildedOptions || []).find(item => {
            if (! id) return false

            return item.id === id.toString() && !item.disabled
          })
        }

        if (this.multiple) {
          if (this.selected instanceof Array) {
            return this.selected.filter(id => findEnabledOption(id))
          }

          return findEnabledOption(this.selected) ? [this.selected] : []
        }

        return findEnabledOption(this.selected) ? this.selected : undefined
      },

      buildOptions () {
        if (! this.options) {
          return
        }

        let disabled = this.disabled || []

        if (! (disabled instanceof Array)) {
          disabled = [disabled]
        }

        disabled = disabled.map(id => id.toString())

        const build = (list = [], level = 0, parentId = 0, acc) => {
          if (! list.length) return false

          return list.reduce((acc, item) => {
            let itemDisabled = item.disabled
            let itemId = item.id.toString()

            if (!itemDisabled && disabled.indexOf(itemId) !== -1) {
              itemDisabled = true
            }
            else if (disabled.indexOf(parentId.toString()) !== -1) {
              itemDisabled = true
              disabled.push(itemId)
            }

            let pad = ('').padStart(level, '—')

            if (pad) {
              pad += ' '
            }

            acc.push({
              id: itemId,
              disabled: itemDisabled,
              title: pad + item.title,
              selected: this.isSelected(item.id)
            })

            build(item.children, level + 1, item.id, acc)

            return acc
          }, acc || [])
        }

        this.buildedOptions = build(this.options) || []
      },

      isSelected(id) {
        if (typeof this.selected === 'undefined' || this.selected === null) return false

        if (this.selected instanceof Array) {
          return this.selected.indexOf(id) !== -1
        }

        return id.toString() === this.selected.toString()
      },

      formatOption(state) {
        if (!state.id) return state.text
        return state.text.replace('—', '').trim()
      },

      select() {
        this.$nextTick(() => {
          this.$$el.val(this.selected)
          this.$$el.trigger('change')
        })
      },

      destroy() {
        if (this.destroyed) return

        this.$$el.val('')
        this.$$el.trigger('change')
        this.$$el.select2('destroy')
        this.$$el.off()

        this.destroyed = true
        this.inited = false
      },

      reset() {
        this.destroy()

        this.$nextTick(() => {
          this.initOptions()
          this.initSelect2()
        })
      },

      setSelected(selected, needToEmit = true) {
        this.rSelected = selected

        if (needToEmit) {
          this.$emit('update:selected', this.rSelected)
        }
      },

      createOption(text) {
        this.$emit('create:options', text)
      },

      initOptions() {
        this.buildOptions()
        this.setSelected(this.getSelected(), false)
      },

      initSelect2() {
        if (this.inited) {
          return
        }

        this.$$el = $(this.$el)

        let params = {
          ... this.params
        }

        this.$$el.select2({
          allowClear: true,
          placeholder: this.placeholder,
          theme: "bootstrap",
          templateSelection: this.formatOption,
          ... params
        })

        this.bindSelect2Events()
        this.select()

        this.destroyed = false
        this.inited = true
      },

      bindSelect2Events() {
        let events = {
          selecting: e => {
            let {id, text} = e.params.args.data

            if (id === text && !this.options.find(item => item.id === id)) {
              this.createOption(text)
              this.$$el.select2('close')
            }
            else {
              if (this.multiple) {
                this.setSelected([
                  ... this.rSelected,
                  id
                ])
              }
              else {
                this.setSelected(id)
              }
            }
          },

          unselecting: e => {
            if (this.multiple) {
              this.setSelected(this.rSelected.filter(id => id.toString() !== e.params.args.data.id.toString()))
            }
            else {
              this.setSelected(null)
            }
          }
        }

        if (this.params && this.params.events) {
          events = {
            ... events,
            ... this.params.events
          }
        }

        for (let name in events) {
          this.$$el.on(`select2:${name}`, events[name])
        }
      }
    },

    created() {
      this.initOptions()
    },

    mounted() {
      this.initSelect2()
    },

    beforeDestroy() {
      this.$$el.select2('destroy')
    }
  }
</script>

<template>
  <select class="select2" :multiple="multiple" style="width:100%">
    <option v-for="option in buildedOptions" :value="option.id" :disabled="option.disabled" :selected="option.selected" :key="option.id">
      {{ option.title }}
    </option>
  </select>
</template>