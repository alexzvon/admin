import ModelI18n from '../base/ModelI18n'

export default class AttributesTableModel extends ModelI18n {
  getSchemaFields() {
    return {
      id: '',
      enabled: true,

      url(data) {
        return '/shop/attributes/' + data.id
      },

      i18n: {
        title: '',
      }
    }
  }
}