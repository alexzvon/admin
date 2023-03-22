<script>
  export default {
    name: 'file-manager',

    props: [
      'id',
      'file'
    ],

    methods: {
      setFile(e) {
        this.$emit('update:file', e.target.value)
      }
    }
  }
</script>

<template>
  <div class="input-group">
    <input type="text" :value="file" :id="id" @change="setFile" @input="setFile" class="form-control">

    <a data-fancybox data-type="iframe" :data-src="'/elfinder/popup/' + id" href="javascript:;" class="input-group-addon btn btn-primary">
      <i class="fa fa-file-o"></i>
    </a>
  </div>
</template>