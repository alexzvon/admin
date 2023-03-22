import Model from '../base/Model'

export default class SupplierDataModel extends Model {
  getSchemaFields() {
    return {
      id: '',
      name: '',
    }
  }
}