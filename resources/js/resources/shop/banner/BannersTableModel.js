import ModelI18n from '../../base/ModelI18n'

export default class BannersTableModel extends ModelI18n {
  getSchemaFields() {
    return {
      id: '',

      gradient: {
        color_from: '',
        color_to: '',
        angle: '',
        type: '',
      },

      color: '',
      button_color: '',
      button_background_color: '',

      type: '',
      position: '',

      places: [],

      enabled: true,

      created_at: null,
      updated_at: null,

      url(data) {
        return '/shop/banners/' + data.id
      },

      i18n: {
        title: '',
        caption: '',
        button: '',
      }
    }
  }
}
