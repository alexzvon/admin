import ModelI18n from '../base/ModelI18n'

export default class StyleDataModel extends ModelI18n {
  getSchemaFields() {
    return {
      id: '',
      enabled: false,

      i18n: {
        title: 'Не заполнено',
        description: '',
        short_description: '',
      }
    }
  }
}