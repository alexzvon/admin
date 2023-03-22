import ModelI18n from '../base/ModelI18n'

export default class AttributeModel extends ModelI18n {
  getSchemaFields() {
    return {
      layout_class: '',
      selectable: false,
      enabled: true,
      i18n: {
        title: '',
      }
    }
  }
}