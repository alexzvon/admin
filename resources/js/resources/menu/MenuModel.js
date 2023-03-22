import Model from '../base/Model'

export default class MenuModel extends Model {
  getSchemaFields() {
    return {
      name: '',
      title: '',

      description: '',
      template: '',

      created_at: null,
      updated_at: null,
    }
  }
}