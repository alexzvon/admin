import ModelI18n from '../base/ModelI18n'
import HasImage from '../base/HasImage'

export default class ProductsTableModel extends ModelI18n {
  getSchemaFields() {
    return {
      id: '',

      image: HasImage('thumb'),

      is_payable: false,
      prices: [],
      created_at: '',
      enabled: false,

      url: function (data) {
        return '/shop/products/' + data.id
      },

      i18n: {
        title: '<span class="label label-danger">Не заполнено</span>'
      },
    }
  }
}
