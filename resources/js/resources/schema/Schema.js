import _ from "lodash";

export default class Schema {
  constructor(fieldSchema = {}) {
    this.schema = this.__init(fieldSchema)
  }

  __init(schema) {
    if (schema instanceof Array) {
      return schema
    }

    if (typeof schema === 'object') {
      return Object.keys(schema).map(name => {
        let defaultValue = schema[name]

        if ( typeof defaultValue === 'object' && defaultValue !== null) {
          defaultValue = this.__init(defaultValue)
        }

        return {
          name,
          default: defaultValue
        }
      })
    }

    return []
  }

  combine(data) {
    return this.__combine(this.schema, data)
  }

  __combine(schema, data) {
    if (typeof data !== 'object' || data === null) {
      data = {}
    }

    return schema.reduce((acc, item) => {
      if (item.default instanceof Schema) {
        acc[item.name] = item.default.combine(data[item.name])
        return acc
      }

      if (typeof item.default === 'object' && !_.isEmpty(item.default)) {
        acc[item.name] = this.__combine(item.default, data[item.name])
        return acc
      }

      if (typeof item.default === 'function') {
        acc[item.name] = item.default.call(item, data)
        return acc
      }

      acc[item.name] = (item.name in data) ? data[item.name] : item.default

      return acc
    }, {})
  }
}