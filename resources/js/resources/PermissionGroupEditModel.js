import Model from './base/Model'

export default class PermissionGroupEditModel extends Model {
  getSchemaFields() {
    return {
      name: '',
      parent_id: '',
      created_at: null,
      updated_at: null,

      url(data) {
        return '/system/rbac/permissions/' + data.id
      },
    }
  }
}