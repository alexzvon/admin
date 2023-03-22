import Model from '../base/Model';
import ModelI18n from "../base/ModelI18n";

export default class ReturnsTableModel extends ModelI18n  {
  getSchemaFields() {
    console.dir({
      id: '',
      name: '',
      description: '',
      enabled: true,
      product: null,
      position: 0,

      created_at: null,
      updated_at: null,

      url(data) {
        return '/shop/returns/' + data.id;
      }
    });
    return {
      id: '',
      name: '',
      description: '',
      enabled: true,
      position: 0,
      product: null,

      created_at: null,
      updated_at: null,

      url(data) {
        return '/shop/returns/' + data.id;
      }
    };
  }
}