<script>
//  import bFormInput from 'bootstrap-vue/es/components/form-input/form-input'

export default {
  name: 'SearchInput',

  props: [
    'phrase',
    'keyUpTimeout',
  ],

  data() {
    return {
      searchPhrase: this.phrase || '',
    };
  },

  // components : {
  //   'b-form-input': bFormInput,
  // },

  methods: {
    onSearch(ctx) {
      this.$emit('change', this.searchPhrase);
    },

    onSearchKeyup() {
      clearTimeout(this.timeout);
      this.timeout = setTimeout(() => {
        this.onSearch();
      }, this.keyUpTimeout || 300);
    },
  },
};
</script>

<template>
  <b-form-input v-model="searchPhrase" @change="onSearch" @keyup.native="onSearchKeyup" />
</template>

<style lang="scss">
    .form-inline .form-control{
        width: 100%;
    }
</style>
