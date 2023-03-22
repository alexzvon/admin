import Core from '.'

export default {
  stagesStorageKey: 'stages',
  stages: false,

  set(name, value) {
    this.init()
    this.stages[name] = value
    Core.storage.add(this.stagesStorageKey, this.stages)
  },

  get(name) {
    this.init()
    return this.stages[name]
  },

  init() {
    if (this.stages === false) {
      this.stages = Core.storage.get(this.stagesStorageKey, this.stages) || {}
    }
  }
}