<template>
  <div class="shop-badge" :style="{backgroundColor: color}" :data-original-title="title">
    <div class="shop-badge__content">
      <template v-if="icon">
        <svg class="shop-badge__icon">
          <use :xlink:href="'/vendor/images/badges.svg#' + icon"></use>
        </svg>
      </template>

      <template v-if="text">
        <div class="shop-badge__text">
          {{ text }}
        </div>
      </template>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'BadgePreview',

    props: [
      'icon',
      'color',
      'title',
      'text'
    ],

    mounted() {
      $(this.$el).tooltip()
    }
  }
</script>