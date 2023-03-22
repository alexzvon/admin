import Model from '../base/Model';

export default class ReturnsModel extends Model {
  getSchemaFields() {
    return {
      name: '',
      description: '',
      enabled: true,
      warehouse_city: '',

      created_at: null,
      updated_at: null,
    };
  }
}