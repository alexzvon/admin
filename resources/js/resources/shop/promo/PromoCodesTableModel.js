import Model from '../../base/Model'
import Core from '../../../core'

export default class PromoCodesTableModel extends Model {
  constructor(entityData, currencies) {
    super(entityData)

    if (entityData && 'conditions' in entityData) {
      this.setConditions(entityData.conditions, currencies)
    }
    else {
      this.conditions = []
    }

    this.setDiscount(currencies)
    this.setQuantityStrings()
    this.setUsesPercent()
  }

  setDiscount(currencies) {
    if (this.amount) {
      this.discount = Core.formatPrice(this.amount, currencies.find(currency => currency.code === this.currency_code))

      this.discount += ', но не более ' + this.percent + '% от суммы покупки'
    }
    else {
      this.discount = this.percent + '%'
    }
  }

  setConditions(conditions, currencies) {
    this.conditions = conditions.reduce((acc, condition) => {
      if ('params' in condition && 'currency_code' in condition.params) {
        condition.params.currency = currencies.find(currency => currency.code === condition.params.currency_code)
      }

      let cond

      switch (condition.type) {
        case 'min_summ':
        case 'product_expensive':
          cond = new CurrencyCondition(condition)
          break

        case 'products_quantity':
          cond = new QuantityCondition(condition)
          break

        default:
          cond = new Condition(condition)
          break
      }

      acc.push(cond)

      return acc
    }, [])
  }

  setQuantityStrings() {
    let quantity = []

    if (this.quantity < 0) {
      if (this.quantity_per_user < 0) {
        quantity.push('Не ограничено')
      }
      else {
        quantity.push('<strong>' + this.quantity_per_user + '</strong> ' + Core.pluralize(this.quantity_per_user, ['раз', 'раза', 'раз']) + ' на пользователя')
      }
    }
    else {
      quantity.push('Количество возможных применений: <strong>' + this.quantity + '</strong> ' + Core.pluralize(this.quantity, ['раз', 'раза', 'раз']))

      if (this.quantity_per_user > 0) {
        quantity.push('<strong>' + this.quantity_per_user + '</strong>  ' + Core.pluralize(this.quantity_per_user, ['раз', 'раза', 'раз']) + ' на пользователя')
      }
    }

    this.quantityStrings = quantity
  }

  setUsesPercent() {
    if (this.quantity > 0 && this.uses_count > 0) {
      this.uses_percent = Math.round(this.uses_count / this.quantity * 100, 0)
    }
    else {
      this.uses_percent = 0
    }
  }

  getSchemaFields() {
    return {
      id: '',
      name: '',
      date_start: '',
      date_finish: '',
      quantity: 0,
      quantity_per_user: 0,
      percent: '',
      amount: '',
      currency_code: null,
      uses_count: 0,
      enabled: true,

      url(data) {
        return '/shop/promo-codes/' + data.id
      },

      created_at: null,
      updated_at: null,
    }
  }
}

class Condition {
  constructor(data) {
    this.type = data.type
    this.params = data.params
  }

  labelText() {
    return Core.translate('shop.promo.conditions.types.' + this.type)
  }

  toString() {
    return '<strong>' + this.labelText() + '</strong>'
  }
}

class CurrencyCondition extends Condition {
  toString() {
    return '<strong>' + this.labelText() + ':</strong>&nbsp;' + Core.formatPrice(this.params.value, this.params.currency)
  }
}

class QuantityCondition extends Condition {
  toString() {
    return '<strong>' + this.labelText() + ':</strong>&nbsp;' + this.params.value
  }
}