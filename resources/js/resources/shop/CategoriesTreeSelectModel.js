import ModelI18n from '../base/ModelI18n'

export default class CategoriesTreeSelectModel extends ModelI18n {
  constructor(entityData, languages) {
    super(entityData, languages)

    let children = entityData.children

    if (children && children instanceof Array && children.length > 0) {
      this.children = children.map(item => new CategoriesTreeSelectModel(item, languages))
    }
  }

  getSchemaFields() {
    return {
      id: '',
      parent_id: '',

      i18n: {
        title: 'Не заполнено',
      }
    }
  }
}