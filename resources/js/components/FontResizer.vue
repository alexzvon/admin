<template>
  <div class="font-resizer">
    <div :style="contentStyle" class="font-resizer__content js-fr-content text-content">
      <render :vnode="$slots.default"></render>
    </div>

    <div :style="standartStyle" class="font-resizer__standard js-fr-standart">
      <render :vnode="$slots.default"></render>
    </div>
  </div>
</template>


<script>
  export default {
    name: 'font-resizer',

    components: {
      Render: {
        functional: true,
        render: (h, ctx) => ctx.props.vnode
      }
    },

    props: [
      'minSize',
      'maxSize',
      'tag'
    ],

    data() {
      return {
        width: 0,
        charWidth: 0,
        charLineHeight: 0,
        textLength: 0,
        els: {
          standart: null,
          content: null,
        }
      }
    },

    updated() {
        this.update()
    },

    mounted() {
      this.els.standart = this.$el.querySelector('.js-fr-standart')
      this.els.content = this.$el.querySelector('.js-fr-content')

      // this.$root.$on('resize', this.update)
      this.update()
    },

    beforeDestroy() {
      this.$root.$off('resize', this.update)
    },

    methods: {
      update() {
        this.$nextTick(this.setSizes)
      },

      setSizes() {
        this.textLength = this.els.content.innerText.length

        this.els.standart.innerHTML = this.els.content.innerHTML

        let standartWidth = this.els.standart.scrollWidth

        this.charWidth = standartWidth / this.textLength * 1.05
        this.charLineHeight = this.els.standart.clientHeight / this.minSize

        this.width = this.$el.clientWidth
      },

      getTextLinesCount() {
        // console.log(
        //   this.els.content.clientWidth,
        //   this.charWidth * this.textLength,
        //   Math.ceil(this.charWidth * this.textLength / this.els.content.clientWidth)
        // )

        return Math.ceil(this.charWidth * this.textLength / this.els.content.clientWidth)
      },

      getFontSizeByTextLength(textLength) {
        let currentCharLength = this.width / textLength

        return Math.floor(currentCharLength / this.charWidth * this.minSize)
      }
    },

    computed: {
      contentFontSize() {
        if (this.charWidth === 0 || this.textLength === 0) {
          return this.minSize
        }

        let fontSize = this.getFontSizeByTextLength(this.textLength)

        if (fontSize < this.minSize) {
          fontSize = this.getFontSizeByTextLength(this.textLength / this.getTextLinesCount() * 1.1)
        }

        fontSize = Math.max(this.minSize, fontSize)

        if (this.maxSize) {
          fontSize = Math.min(this.maxSize, fontSize)
        }

        return fontSize
      },

      contentStyle() {
        return {
          'font-size': this.contentFontSize + 'px'
        }
      },

      standartStyle() {
        return {
          'font-size': this.minSize + 'px'
        }
      },
    }
  }
</script>

<style lang="scss">
  .font-resizer {
    &__standard {
      position: absolute;
      left: -99999px;
    }
  }
</style>