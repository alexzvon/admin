import ModelI18n from '../../base/ModelI18n'

export default class BadgeTypesTableModel extends ModelI18n {
  getSchemaFields() {
    return {
      id: '',
      icon: 'symbol-plus',
      color: '#969CA3',
      has_value: false,
      position: 0,

      created_at: null,
      updated_at: null,

      i18n: {
        title: '',
      },

      url(data) {
        return '/shop/badge-types/' + data.id
      },
    }
  }
}
