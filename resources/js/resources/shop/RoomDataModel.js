import ModelI18n from '../base/ModelI18n'

export default class RoomDataModel extends ModelI18n {
  getSchemaFields() {
    return {
      id: '',
      enabled: false,

      i18n: {
        title: 'Не заполнено',
        description: ''
      }
    }
  }
}