<script>
  import Core from '../../core'
  import BaseConverter from './BaseConverter'

  export default {
    name: "size-converter",

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
            name: 'мм',
            multiplier: 1,
          },
          {
            name: 'см',
            multiplier: 0.1,
            precision: 1,
          },
          {
            name: 'м',
            multiplier: 0.001,
            precision: 3,
          }
        ]
      }
    },
  }
</script>

<template>
    <base-converter :value="value" :parameters="parameters"></base-converter>
</template>