import Model from './base/Model'

export default class RoleEditModel extends Model {
  getSchemaFields() {
    return {
      id: '',
      name: '',
      importance: 999,

      created_at: null,
      updated_at: null,
    }
  }
}