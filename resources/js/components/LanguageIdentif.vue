<script>
  import Core from '../core'

  export default {
    name: "language-identif",

    props: [
      'language',
      'hideCode',
      'hideIcon',
    ],

    methods: {
      prettyCode(code) {
        return Core.capitalizeFirstLetter(code)
      },
    }
  }
</script>

<template>
  <span class="language-identif">
    <span class="language-identif__flag" :style="`background-image:url(${language.image});`" v-if="!hideIcon"></span>
    <span class="language-identif__code" v-if="!hideCode">{{ prettyCode(language.code) }}</span>
  </span>
</template>