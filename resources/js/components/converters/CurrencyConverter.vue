<script>
  import Core from '../../core'
  import BaseConverter from './BaseConverter'

  export default {
    name: "currency-converter",

    props: [
      'value',
      'currency'
    ],

    components: {
      BaseConverter
    },

    data() {
      return {
        parameters: [
          {
            formatFunc: value => Core.formatPrice(value, this.currency)
          }
        ]
      }
    },
  }
</script>

<template>
    <base-converter :value="value" :parameters="parameters"></base-converter>
</template>
