import Schema from '../schema/Schema'
import BaseModel from './BaseModel'

export default class Model extends BaseModel {
  constructor(entityData = {}) {
    super(entityData)

    let modelData = (new Schema(this.getSchemaFields())).combine(entityData)

    for (let i in modelData) {
      this[i] = modelData[i]
    }
  }
}