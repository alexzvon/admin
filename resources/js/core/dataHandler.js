import Core from './'

export default {
  key: null,
  namespace: '__mainData',
  storageKeyName: 'key',

  get(dataLabels = []) {
    this.data = {}

    if (dataLabels.length === 0) {
      return new Promise(resolve => resolve([]))
    }

    return this.getRelevantKey()
      .then(key => {
        if (key === this.getCurrentKey()) {
          return this.getFromStorage(dataLabels)
        }
        else {
          this.setCurrentKey(key)
          return this.getFromServer(dataLabels)
        }
      })
  },

  setCurrentKey(key) {
    if (this.getCurrentKey() !== key) {
      this.flush()
      this.key = key
      this.setItem(this.storageKeyName, key)
    }
  },

  getCurrentKey() {
    if (this.key === null) {
      this.key = this.getItem(this.storageKeyName)
    }

    return this.key
  },

  getRelevantKey() {
    return new Promise((resolve) => {
      new Core.requestHandler('get', '/api/data/relevantKey')
        .success(response => {
          const key = response.data.key
          resolve(key)
        })
        .start()
    })
  },

  getFromStorage(dataLabels) {
    dataLabels = this.prepareDataLabels(dataLabels)

    return new Promise(resolve => {
      for (let i = dataLabels.length; i >= 0; i--) {
        let label = dataLabels[i]
        let fromLocalStorage = this.getItem(label)

        if (typeof fromLocalStorage !== 'undefined' && fromLocalStorage !== null) {
          this.data[label] = fromLocalStorage
          dataLabels.splice(i, 1)
        }
      }

      if (dataLabels.length === 0) {
        resolve(this.data)
      }
      else {
        resolve(this.getFromServer(dataLabels))
      }
    })
  },

  getFromServer(dataLabels) {
    dataLabels = this.prepareDataLabels(dataLabels)

    return new Promise((resolve) => {
      new Core.requestHandler('get', '/api/data', {
        responseType: 'json',
        labels: dataLabels,
      })
        .success(response => {
          let data = response.data.data

          this.setDataToStorage(data)

          this.data = {
            ... this.data,
            ... data
          }

          resolve(this.data)
        })
        .start()
    })
  },

  prepareDataLabels(dataLabels = []) {
    return [
      ...dataLabels
    ]
  },

  setDataToStorage(data) {
    if ('key' in data) {
      this.setCurrentKey(data.key)
      delete data.key
    }

    for (let i in data) {
      this.setItem(i, data[i])
    }
  },

  getItem(label) {
    return Core.storage.get(this.getLabelWithNamespace(label))
  },

  setItem(label, data) {
    Core.storage.add(this.getLabelWithNamespace(label), data)
  },

  getLabelWithNamespace(label) {
    return `${this.namespace}.${label}`
  },

  flush() {
    Object.keys(localStorage).forEach(label => {
      if (label.indexOf(this.namespace + '.') === 0) {
        Core.storage.forget(label)
      }
    })
  },
}