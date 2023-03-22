import ModelI18n from '../base/ModelI18n'
import HasImage from "../base/HasImage";

export default class StylesTableModel extends ModelI18n {
  getSchemaFields() {
    return {
      id: '',
      slug: '',
      enabled: true,
      products_count: 0,
      position: 0,

      image: HasImage('small'),

      created_at: null,
      updated_at: null,

      url(data) {
        return '/shop/styles/' + data.id
      },

      siteUrl(data) {
        return '/styles/' + data.slug
      },

      i18n: {
        title: '<span class="label label-danger">Не заполнено</span>'
      }
    }
  }
}