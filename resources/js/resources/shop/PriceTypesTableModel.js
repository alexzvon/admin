import ModelI18n from '../base/ModelI18n'

export default class PriceTypeModel extends ModelI18n {
  getSchemaFields() {
    return {
      id: '',
      enabled: true,
      position: 0,

      created_at: null,
      updated_at: null,

      url: function (data) {
        return '/shop/price-types/' + data.id
      },

      i18n: {
        title: '<span class="label label-danger">Не заполнено</span>'
      }
    }
  }
}