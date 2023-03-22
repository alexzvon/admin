export default class PricesTableModel {
 constructor(prices = []) {
    return prices.reduce((acc, item) => {
      if (! (item.price_type_id in acc)) {
        acc[item.price_type_id] = {}
      }

      acc[item.price_type_id][item.currency_code] = item.value

      return acc
    }, {})
  }
}