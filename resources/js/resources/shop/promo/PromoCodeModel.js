import ModelI18n from '../../base/ModelI18n'

export default class PromoCodeModel extends ModelI18n {
  constructor(entityData, languages) {
    super(entityData, languages)

    if (entityData && 'conditions' in entityData) {
      this.conditions = entityData.conditions.reduce((acc, condition) => {
        acc[condition.type] = condition.params

        return acc
      }, {})
    }
    else {
      this.conditions = {}
    }
  }

  getSchemaFields() {
    return {
      id: '',
      name: '',
      date_start: null,
      date_finish: null,

      quantity: 1000,
      quantity_per_user: 1,
      percent: '',
      amount: '',
      currency_code: null,
      uses_count: 0,
      enabled: true,

      conditions: {},

      created_at: null,
      updated_at: null,

      i18n: {
        title: '',
        description: '',
      }
    }
  }
}
