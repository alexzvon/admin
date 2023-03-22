import _ from "lodash";
import Model from './base/Model';
import UserTableModel from './UserTableModel';
import ProductsTableModel from './shop/ProductsTableModel';

export default class ReviewsModel extends Model {
  constructor(entityData = {}, languages = []) {
      super(entityData)

      if ('product' in entityData) {
          this.product = new ProductsTableModel(entityData.product, languages)
      }
  }

  getSchemaFields() {
    return {
      id: '',

      user(data) {
          return new UserTableModel(data.user)
      },

      text(data) {
        return _.truncate(data.comment, 100)
      },

      url(data) {
        return '/reviews/' + data.id
      },

      language_code: '',
      rate: '',
      advantages: '',
      disadvantages: '',
      comment: '',
      usage_time: null,
      confirmed: false,
      enabled: true,

      created_at: null,
      updated_at: null,
    }
  }
}