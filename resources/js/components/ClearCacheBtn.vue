<script>
  import Core from '../core'

  export default {
    name: "clear-cache-btn",

    methods: {
      clear() {
        new Core.requestHandler('get', `/api/cache`).start()
      }
    }
  }

</script>

<template>
  <a href="javascript:void(0)" @click="clear">
    <slot></slot> Отчистить кэш
  </a>
</template>