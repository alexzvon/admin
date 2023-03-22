import Model from '../base/Model';

export default class ReturnsDataModel extends Model {
  getSchemaFields() {
    return {
      id: '',
      name: '',
    };
  }
}