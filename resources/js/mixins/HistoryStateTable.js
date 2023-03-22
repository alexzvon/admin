import Core from '../core';
import _ from "lodash";

export default {
  created() {
    this.setStateFromUri()
  },

  methods: {
    setStateFromUri() {
      let query = window.location.search.replace('?', '')

      if (_.isEmpty(query)) return

      this.parseUriStringParams(query)
    },

    parseUriStringParams(query) {
      query.split('&').forEach(item => {
        item = item.split('=')

        let key = decodeURIComponent(item[0])
        let value = decodeURIComponent(item[1])

        this[key] = this.getValid(key, value)
      })
    },

    getInitialValue(key) {
      return _.get(this.getDefaultState(), key)
    },

    getDefaultState() {
      return {}
    },

    getValidSortBy(value) {
      if (this.fields.find(field => field.key === value)) {
        return value
      }

      return this.getInitialValue('sortBy')
    },

    getValidSortDesc(value) {
      if (_.isBoolean(value)) {
        return value
      }

      if (value === 'true') {
        return true
      }

      if (value === 'false') {
        return false
      }

      return this.getInitialValue('sortDesc')
    },

    getValidPage(value) {
      value = parseInt(value)

      return isNaN(value) ? this.getInitialValue('page') : value
    },

    getValidType(value) {
      return value in this.types ? value : this.getInitialValue('type')
    },

    getValidPerPage(value) {
      value = parseInt(value)

      if (this.perPageOptions.indexOf(value) !== -1) {
        return value
      }

      return this.perPageOptions[0]
    },

    getValidSearchPhrase(value) {
      return value
    },

    getValid(key, value) {
      let methodName = 'getValid' + Core.camelize(decodeURIComponent(key), true)

      return this[methodName](value)
    },

    setHistoryState() {
      let queryArr = Object.keys(this.getDefaultState()).reduce((acc, key) => {
        let validValue = this.getValid(key, this[key])

        if (validValue !== this.getInitialValue(key)) {
          acc.push(encodeURIComponent(key) + '=' + encodeURIComponent(this[key]))
        }

        return acc
      }, [])

      let pathWithQuery = window.location.pathname

      if (queryArr.length > 0) {
        pathWithQuery += '?' + queryArr.join('&')
      }

      if (this.$router.path !== pathWithQuery) {
        this.$router.push(pathWithQuery)
      }
    },
  },

  computed: {
    showedFrom() {
      return this.nanToNum((this.page - 1) * this.perPage + 1)
    },

    showedTo() {
      let to = this.page * this.perPage
      return this.nanToNum(to > this.totalRows ? this.totalRows : to)
    },

    showPagination() {
      return this.perPage < this.totalRows;
    },
  }
}