import ModelI18n from './base/ModelI18n'

export default class AxajMultiselectModel extends ModelI18n {
  constructor(entityData = {}, languages = []) {
    super(entityData, languages)

    for (let lang in this.i18n) {
      this.i18n[lang].title = '#' + this.id + ' - ' + this.i18n[lang].title
    }
  }

  getSchemaFields() {
    return {
      id: '',

      i18n: {
        title: 'Не заполнено',
      }
    }
  }
}

