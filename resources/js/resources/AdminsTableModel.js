import Model from './base/Model'

export default class AdminsTableModel extends Model {
  getSchemaFields() {
    return {
      id: '',
      name: '',
      email: '',
      roles: [],
      enabled: false,

      url: function (data) {
        return '/system/admins/' + data.id
      },
    }
  }
}