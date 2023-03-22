<script>
  export default {
    name: 'toggle',

    props: [
      'checked'
    ],

    watch: {
      checked: 'updateChecked'
    },

    data() {
      return {
        сhecked: this.checked
      }
    },

    methods: {
      onChange: function() {
        this.сhecked = !this.сhecked
        this.$emit('change', this.сhecked)
      },

      updateChecked() {
        this.сhecked = this.checked
      }
    }
  }
</script>

<template>
  <label class="switch switch-info">
    <input type="checkbox" v-on:click="onChange" v-model="сhecked">
    <span></span>
  </label>
</template>