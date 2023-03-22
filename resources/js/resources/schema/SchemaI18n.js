import Schema from './Schema'

export default class SchemaI18n extends Schema {
  combine(data = [], languages = []) {
    return this.__combine(this.schema, data, languages)
  }

  __combine(schema, data = [], languages = []) {
    return languages.reduce((acc, language) => {
      acc[language.code] = super.__combine(schema, data.find(item => item.language_code === language.code))

      return acc
    }, {})
  }
}