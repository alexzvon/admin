import SchemaI18n from '../schema/SchemaI18n'

import Model from "./Model";

export default class ModelI18n extends Model {
  constructor(entityData = {}, languages = []) {
    super(entityData);

    let i18nData = (new SchemaI18n(this.getSchemaFields().i18n)).combine(entityData.i18n, languages);

    this.i18n = {};

    for (let i in i18nData) {
      this.i18n[i] = i18nData[i];
    }
  }
}

