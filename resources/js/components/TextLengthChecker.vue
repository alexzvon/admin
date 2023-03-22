<template>
  <span class="text-length-checker">
    <template v-if="hasMax">
      Осталось символов: <span :class="{'text-danger': reachedMax}">{{ symbolsLeft }}</span>
    </template>

    <template v-else>
      Символов в тексте: <span>{{ textLength }}</span>
    </template>
  </span>

</template>

<script>
  export default {
    name: 'text-length-checker',

    props: [
      'text',
      'max'
    ],

    data() {
      return {
        el: document.createElement('test')
      }
    },

    computed: {
      text$() {
        this.el.innerHTML = this.text

        return this.el.innerText
      },

      textLength() {
        return this.text$ && this.text$.length ? this.text$.length : 0
      },

      hasMax() {
        return !!this.max
      },

      reachedMax() {
        return this.symbolsLeft === 0
      },

      symbolsLeft() {
        return Math.max(this.max - this.textLength, 0)
      }
    }
  }
</script>