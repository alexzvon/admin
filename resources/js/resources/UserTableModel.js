import _ from "lodash";
import Model from './base/Model';

export default class UserTableModel extends Model {
  getSchemaFields() {
    return {
      id: '',
      first_name: '',
      last_name: '',
      email: '',
      phone: '',

      url(data) {
        return 'shop/customers/' + data.id
      },

      full_name(data) {
          return _.trim(data.first_name + ' ' + data.last_name)
      },
    }
  }
}