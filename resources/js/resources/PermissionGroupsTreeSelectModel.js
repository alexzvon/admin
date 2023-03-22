import Model from './base/Model'

export default class PermissionGroupsTreeSelectModel extends Model {
  constructor(entityData) {
    super(entityData)

    let children = entityData.children

    if (children && children instanceof Array && children.length > 0)   {
      this.children = children.map(item => new PermissionGroupsTreeSelectModel(item))
    }
  }

  getSchemaFields() {
    return {
      id: '',
      parent_id: '',

      title: function (data) {
        return data.name
      }
    }
  }
}