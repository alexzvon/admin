import ModelI18n from '../../base/ModelI18n'

export default class InteriorTableModel extends ModelI18n {
  getSchemaFields() {
    return {
      id: '',
      image: '',
      rooms: [],
      styles: [],

      url(data) {
        return '/shop/interiors/' + data.id
      },

      i18n: {
        title: '<span class="label label-danger">Не заполнено</span>'
      }
    }
  }
}
