import Model from '../../base/Model'
import ProductsTableModel from '../ProductsTableModel'

// todo: Может быть не только товар
export default class SaleTableModel extends Model {
  constructor(entityData, languages) {
    super(entityData)

    let productData = entityData && entityData.product ? entityData.product : undefined

    this.product = new ProductsTableModel(productData, languages)
  }

  getSchemaFields() {
    return {
      id: '',
      date_start: '',
      date_finish: '',
      enabled: true,
      prices: [],

      product_id(data) {
        return data.product ? data.product.id : ''
      },

      url(data) {
        return '/shop/sale/' + data.id
      },
    }
  }
}