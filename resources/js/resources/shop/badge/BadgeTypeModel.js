import ModelI18n from '../../base/ModelI18n'

export default class BadgeTypeModel extends ModelI18n {
  getSchemaFields() {
    return {
      id: '',
      icon: '',
      color: '#969CA3',
      has_value: false,
      text: '',

      created_at: null,
      updated_at: null,

      i18n: {
        title: '',
      }
    }
  }
}
