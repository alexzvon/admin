<template>
  <component
    :is="buttonText ? 'div' : 'a'"
    :href="buttonText ? null : link"
    :style="{backgroundImage: background}"
    class="header-banner"
    target="_blank"
  >
    <div class="header-banner__content">
      <div
        :style="{color: titleColor}"
        class="header-banner__title"
      >
        <font-resizer
          style="width: 100%"
          :min-size="size === 'mobile' ? 12 : 14"
          :max-size="size === 'mobile' ? 16 : 18"
        >
          <span v-html="title"></span>
        </font-resizer>
      </div>

      <div v-if="buttonText" class="header-banner__button">
        <a :style="buttonStyle" :href="link" class="button button-long button-small button-shadow" target="_blank">
          {{ buttonText }}
        </a>
      </div>
    </div>
  </component>

</template>

<script>
  import Mixin from './mixin'

  export default {
    name: 'header-banner',

    mixins: [
      Mixin
    ],

    props: {
      size: String
    },

    computed: {
      background() {
        if (this.image) {
          return 'url(' + this.image + ')'
        }

        return this.gradient
      },
    }
  }
</script>
