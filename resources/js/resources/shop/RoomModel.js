import ModelI18n from '../base/ModelI18n'

export default class RoomModel extends ModelI18n {
  getSchemaFields() {
    return {
      slug: '',
      enabled: true,

      image: false,

      created_at: null,
      updated_at: null,

      i18n: {
        title: '',
        description: '',
        meta_title: '',
        meta_description: ''
      }
    }
  }
}