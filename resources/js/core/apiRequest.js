import Core from './'

const availableMessageTypes = [
  'alert',
  'success',
  'warning',
  'error',
  'info',
]

export default class apiRequest {
  constructor(method = 'get', url, data = {}, configParams = {}) {
    this.status = 'crashed'
    this.callbacks = []
    this.fetchRequestCancel = false
    this.isDone = false
    this.response = null
    this.currentUrl = null
    this.started = false

    let config = {
      method: method,
      url: url,
    }

    data = {
      api_token: Core.getApiToken(),
      ... data
    }

    if (['post', 'put', 'patch'].indexOf(method) !== -1) {
      config.data = data
    }
    else {
      config.params = data
    }

    this.config = {
      ...config,
      ...configParams,
      cancelToken: new axios.CancelToken(c => this.fetchRequestCancel = c)
    }

    return this
  }

  start() {
    if (this.started) return
    this.started = true
    this.currentUrl = window.location.href

    axios.request(this.config)
      .then(response => {
        this._handleResponse(response)
      })
      .catch(error => {
        if (axios.isCancel(error)) return

        let response = error.response || {}

        if (response.status >= 500) {
          Core.notify('Техническая ошибка. Обратитесь к разработчикам.', {type: 'error'})
          return this._done('crashed')
        }
        else if (response.status === 422) {
          this.response = response;

          return this._done('422')
        }
        else if (response.status === 404) {
          return this._done('404')
        }
        else if (response.status === 401) {
          Core.notify(response.data.message, {type: 'warning'})
          return this._done('unauthorized')
        }
        else if ('data' in response && typeof response.data === 'object' && response.data !== null && Object.keys(response.data).length !== 0) {
          this._handleResponse(response)
        }
      })

    return this
  }

  abort() {
    if (!this.isDone && typeof this.fetchRequestCancel === 'function') {
      this.fetchRequestCancel()
      this.fetchRequestCancel = false
    }
  }

  _handleResponse(response) {
    this.response = response;

    const data = response.data

    if (data.redirect && this.currentUrl === window.location.href) {
      let redirect = data.redirect

      if (redirect.indexOf('/') === 0) {
        redirect = '/' + Core.trim(redirect, '/')
      }

      window.location.href = redirect
      return
    }

    if (data.message) {
      let status = availableMessageTypes.indexOf(data.status) !== -1 ? data.status : undefined

      Core.notify(data.message, {type: status})
    }

    if (data.withData) {
      Core.dataHandler.setDataToStorage(data.withData)
    }

    this._done(data.status)
  }

  _done(status = 'success') {
    this.isDone = true
    this.status = status

    this.callbacks.forEach(callback => {
      Core.runCallback(callback, this.response)
    })
  }

  _onDone(callback) {
    if (this.isDone) {
      Core.runCallback(callback, this.response)
    }
    else {
      this.callbacks.push(callback)
    }
  }

  success(callback) {
    this._onDone(() => {
      if (this.status === 'success') {
        Core.runCallback(callback, this.response)
      }
    })

    return this
  }

  fail(callback) {
    this._onDone(() => {
      if (this.status === 'error' || this.status === 'unauthorized') {
        Core.runCallback(callback, this.response)
      }
    })

    return this
  }

  unauthorized(callback) {
    this._onDone(() => {
      if (this.status === 'unauthorized') {
        Core.runCallback(callback, this.response)
      }
    })

    return this
  }

  crash(callback) {
    this._onDone(() => {
      if (this.status !== 'crashed') return

      Core.runCallback(callback)
    })

    return this
  }

  notFound(callback) {
    this._onDone(() => {
      if (this.status !== '404') return

      Core.runCallback(callback)
    })

    return this
  }

  invalid(callback) {
    this._onDone(() => {
      if (this.status !== '422') return

      Core.runCallback(callback, this.response)
    })

    return this
  }

  any(callback) {
    this._onDone(callback)

    return this
  }
}