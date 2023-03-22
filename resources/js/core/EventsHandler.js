import Core from './';
import _ from "lodash";

export default class EventsHandler {
  constructor() {
    this.events = {}
  }

  __checkName(name) {
    if (typeof name !== 'string') {
      throw new Error('The event name must be a string type.')
    }
  }

  __checkCallback(callback) {
    if (typeof callback !== 'function') {
      throw new Error('The callback must be a function type.')
    }
  }

  on(name, callback) {
    this.__checkName(name)
    this.__checkCallback(callback)

    if (! this.events[name]) {
      this.events[name] = []
    }

    this.events[name].push(callback)

    return () => {
      this.off(name, callback)
    }
  }

  one(name, callback) {
    this.__checkName(name)
    this.__checkCallback(callback)

    let _ = this
    let handler = function() {
      callback.apply(null, arguments)
      _.off(name, handler)
    }

    this.on(name, handler)
  }

  off(name, callback) {
    this.__checkName(name)

    if (!this.events[name]) return

    if (typeof callback === 'undefined') {
      delete this.events[name]
    }
    else if (typeof callback === 'function') {
      let index = this.events[name].indexOf(callback)

      if (index !== -1) {
        if (this.events[name].length === 1) {
          delete this.events[name]
        }
        else {
          this.events[name].splice(index, 1)
        }
      }
    }
  }

  trigger(name) {
    this.__checkName(name)

    let data = [].slice.call(arguments, 1)

    if (! this.events[name]) return

    this.events[name].forEach(eventCallback => {
      eventCallback.apply( eventCallback, data)
    })
  }
}