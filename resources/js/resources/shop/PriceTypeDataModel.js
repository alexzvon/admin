import ModelI18n from '../base/ModelI18n'

export default class PriceTypeDataModel extends ModelI18n {
  getSchemaFields() {
    return {
      id: '',
      enabled: false,

      i18n: {
        title: '',
        description: ''
      }
    }
  }
}