import Model from '../base/Model'

export default class ProductAttributesModel extends Model {
  getSchemaFields() {
    return {
      id: '',
      name: '',
      description: '',
      enabled: true,
      position: 0,

      created_at: null,
      updated_at: null,

      url(data) {
        return '/shop/suppliers/' + data.id
      }
    }
  }
}