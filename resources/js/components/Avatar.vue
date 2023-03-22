<script>
  import Core from '../core'

  const defaultAvatar = '/img/placeholders/avatars/avatar.jpg'

  export default {
    name: "avatar",

    props: {
      src: String
    },

    data() {
      return {
        src$: defaultAvatar
      }
    },

    methods: {
      setSrc(src) {
        if (! src) {
          this.src$ = defaultAvatar
        }
        else {
          this.src$ = src
        }
      }
    },

    mounted() {
      this.setSrc(this.src)

      Core.events.on('system.avatar-changed', (userId, src) => {
        if (Core.user.getId() === userId) {
          this.setSrc(src)
        }
      })
    },
  }
</script>

<template>
  <img :src="src$" alt="avatar">
</template>