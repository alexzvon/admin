<script>
  import Core from '../../core'
  import BaseConverter from './BaseConverter'

  export default {
    name: "weight-converter",

    props: [
      'value',
    ],

    components: {
      BaseConverter
    },

    data() {
      return {
        parameters: [
          {
            name: 'г',
            multiplier: 1,
          },
          {
            name: 'кг',
            multiplier: 0.001,
            precision: 3,
            default: 1,
          }
        ]
      }
    },
  }
</script>

<template>
    <base-converter :value="value" :parameters="parameters"></base-converter>
</template>