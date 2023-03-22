import ModelI18n from '../base/ModelI18n'

export default class MenuItemModel extends ModelI18n {
  getSchemaFields() {
    return {
      menu_id: '',
      item_id: true,
      item_type: true,
      image: '',
      hidden: '',
      parent_id: '',
      position: '',
      link: '',
      target: '',

      i18n: {
        title: '',
        description: '',
      }
    }
  }
}