import ModelI18n from '../../base/ModelI18n'
import HasImage from '../../base/HasImage'

export default class InteriorProductPreviewModel extends ModelI18n {
  getSchemaFields() {
    return {
      id: '',
      image: HasImage('small'),
      price: '',

      i18n: {
        title: '<span class="label label-danger">Не заполнено</span>'
      },
    }
  }
}
