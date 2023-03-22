import Core from '../core'

import Base from './Base'
import DataHandler from './DataHandler'

// todo: history api

export default {
  mixins: [
    Base,
    DataHandler
  ],

  data() {
    return {
      componentInitializedWithUrl: null
    }
  },

  methods: {

    makePageUrl(segment, segmentIsUrl = false) {
      let url = segmentIsUrl ? segment : this.componentInitializedWithUrl.replace('create', '')

      url = '/' + Core.trim(url, '/')

      if (segment && !segmentIsUrl) {
        url += '/' + Core.trim(segment, '/')
      }

      return url
    },

    /*
      Подготавливает url.
    */
    makePageApiUrl(segment, segmentIsUrl) {
      let url = this.makePageUrl(segment, segmentIsUrl)

      return `/api${url}`
    },
  },

  created() {
    this.componentInitializedWithUrl = this.$route.path
  }
}