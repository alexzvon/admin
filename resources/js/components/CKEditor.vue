<script>
  export default {
    name: 'ckeditor',

    props: [
      'className',
      'content'
    ],

    watch: {
      '$route': 'reset'
    },

    mounted() {
      return;
      // недоделано
      this.instance = CKEDITOR.replace( this.$el, {
        extraPlugins: 'divarea',
        startupFocus : true,
        filebrowserBrowseUrl: '/elfinder/ckeditor'
      })

      this.instance.on('change', () => {
        this.$emit('update:content', this.instance.getData())
      })

      this.instance.on("instanceReady", () => {
        this.instance.editable().$.blur()
      });

      let iframe = document.createElement('iframe')

      iframe.src = '/elfinder/popup/aszasd'

      iframe.style.width = '1000px'
      iframe.style.height = '500px'

      window.processSelectedFile = function() {
        console.log(arguments)
      }

    },

    methods: {
      input() {
        this.$emit('update:content', this.$refs.textarea.value)
      },

      destroy() {
        if (!this.destroyed) {
          this.instance.focusManager.blur(true)
          this.instance.removeAllListeners()
          this.instance.destroy(true)
          this.destroyed = true
        }
      },

      reset() {
        this.instance.setData(this.content)
      }
    },

    beforeDestroy() {
      // this.destroy()
    }
  }
</script>

<template>
  <!--<textarea :class="`ckeditor${className ? ' ' + className : ''}`">{{ content }}</textarea>-->
  <textarea ref="textarea" :class="`form-control${className ? ' ' + className : ''}`" @input="input">{{ content }}</textarea>
</template>

