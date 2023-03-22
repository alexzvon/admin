import Model from './base/Model'

export default class AdminEditModel extends Model {
  getSchemaFields() {
    return {
      id: '',
      name: '',
      email: '',
      roles: [],
      enabled: true,

      image: false,

      created_at: null,
      updated_at: null,
    }
  }
}