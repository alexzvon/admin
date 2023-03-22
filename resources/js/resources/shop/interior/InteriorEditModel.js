import ModelI18n from '../../base/ModelI18n'

import InteriorPointModel from './InteriorPointModel'

export default class InteriorEditModel extends ModelI18n {
  getSchemaFields() {
    return {
      id: '',
      image: '',
      rooms: [],
      styles: [],

      points(data) {
        return (data.points || []).map(item => new InteriorPointModel(item))
      },

      i18n: {
        title: ''
      }
    }
  }
}
