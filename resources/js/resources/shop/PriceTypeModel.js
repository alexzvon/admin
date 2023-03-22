import ModelI18n from '../base/ModelI18n'

export default class PriceTypeModel extends ModelI18n {
  getSchemaFields() {
    return {
      id: '',
      enabled: true,

      created_at: null,
      updated_at: null,

      i18n: {
        title: '',
        description: ''
      }
    }
  }
}