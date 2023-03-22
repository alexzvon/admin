import ModelI18n from '../base/ModelI18n'

export default class ProductAttributesModel extends ModelI18n {
  getSchemaFields() {
    return {
      id: '',
      enabled: true,

      i18n: {
        title: '',
      }
    }
  }
}