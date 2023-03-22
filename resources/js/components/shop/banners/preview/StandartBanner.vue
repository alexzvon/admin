<script>
  import Mixin from './mixin'

  export default {
    name: "standart-banner",

    mixins: [
      Mixin
    ],

    props: {
      backgroundImage: null,
      caption: String,
      captionColor: String,
      size: String,
    },
  }
</script>

<template>
  <component
    :is="buttonText ? 'div' : 'a'"
    :href="buttonText ? null : link"
    :style="{backgroundImage: background}"
    :class="'banner banner--' + size"
    target="_blank"
  >
    <div class="banner__top">
      <div
        v-if="image && size !== 'long'"
        class="banner__image"
        :style="'background-image: url(' + image + ')'"
      ></div>

      <div
        v-else
        :style="{color: titleColor}"
        class="banner__title"
      >
        <span v-if="size === 'long'">
          <span class="text-content" v-html="title"></span>
        </span>

        <font-resizer
          v-else
          style="width: 100%"
          :min-size="size === 'medium' ? 24 : 30"
          :max-size="140"
        >
          <span v-html="title"></span>
        </font-resizer>
      </div>
    </div>

    <div class="banner__center">
      <div v-html="caption" :style="{color: captionColor}" class="banner__caption"></div>
    </div>

    <div class="banner__bottom">
      <a
        v-if="buttonText"
        :style="buttonStyle"
        :href="link"
        class="button button-long button-shadow"
        target="_blank"
      >
        {{ buttonText }}
      </a>
    </div>
  </component>
</template>