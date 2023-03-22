import Model from '../../base/Model'

export default class InteriorPointModel extends Model {
  getSchemaFields() {
    return {
      id: '',
      x: '',
      y: '',
      position_x: '',
      position_y: '',
      is_similar: false,
      product: null,

      product_id(data) {
        return data.product ? data.product.id : ''
      }
    }
  }
}
