import Model from './base/Model'
import PermissionGroupsTreeSelectModel from "./PermissionGroupsTreeSelectModel";

export default class PermissionGroupsTableModel extends Model {
  constructor(entityData) {
    super(entityData)

    let children = entityData.children

    if (children && children instanceof Array && children.length > 0)   {
      this.children = children.map(item => new PermissionGroupsTableModel(item))
    }
  }

  getSchemaFields() {
    return {
      id: '',
      parent_id: '',
      name: '',
      position: 0,

      url(data) {
        return '/system/rbac/permission-groups/' + data.id
      },
    }
  }
}