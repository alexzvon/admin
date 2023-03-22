<script>
    import _ from "lodash";
  import $ from 'jquery'

  export default {
    name: 'dropdown',

    props: [
      'className',
      'options',
      'position'
    ],

    data() {
      return {
        opened: false,
      }
    },

    mounted() {
      this.clickHandler = e => {
        if ($(e.target).closest(this.$el).length) return
        this.close()
      }

      $(document).on('click', this.clickHandler)
    },

    beforeDestroy() {
      $(document).off('click', this.clickHandler)
    },

    methods: {
      open() {
        this.opened = true
      },

      close() {
        this.opened = false
      },

      toggle() {
        this.opened = !this.opened
      },

      setClick(el, click) {
        el.data = el.data || {}
        el.data.on = el.data.on || {}

        let onClick = el.data.on.click

        if (typeof onClick === 'function') {
          el.data.on.click = function() {
            click()
            onClick.apply(onClick, arguments)
          }
        }
        else {
          el.data.on.click = click
        }
      }
    },

    render(createElement) {
      if (!this.options) return
      let _ = this
      //
      const btn = this.$slots['button'][0]

      if (btn) {
        this.setClick(btn, () => _.toggle())
      }

      const $scoped = _.$scopedSlots

      const options = createElement(
        'ul',
        {
          class: 'dropdown-menu dropdown-custom' + (_.position ? ' dropdown-menu-' + _.position : '')
        },

        _.options.reduce((acc, item) => {
          let option = $scoped.option(item)[0]

          this.setClick(option, () => _.close())

          let keyCounter = 0

          if (option.tag !== 'li') {
            option = createElement(
              'li',
              {
                class: item.isActive ? 'active' : '',
                key: item.id || item.code || keyCounter++
              },
              [option]
            )
          }

          acc.push(option)

          return acc
        }, [])
      )

      return createElement(
        'div',
        {
          class: _.className + (_.opened ? ' open' : ''),
        },
        [
          btn,
          options
        ]
      )
    }
  }
</script>