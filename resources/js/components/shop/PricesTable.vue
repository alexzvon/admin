<script>
    import _ from "lodash";
  import { ErrorBag } from 'vee-validate'
  import number from '../../directives/number'
  import Loading from '../Loading.vue'
  import CurrencyConverter from '../converters/CurrencyConverter'
  import DataHandler from '../../mixins/DataHandler'

  export default {
    mixins: [
      DataHandler,
    ],

    props: {
      prices: {
        type: Object,
        required: true
      },

      errors: {
        type: ErrorBag,
        default: () => new ErrorBag()
      },

      activeLanguageCode: String,

      availablePriceTypes: {
        default: false
      }
    },

    directives: {
      ... number,
    },

    data() {
      return {
        loading: true,
        languages: [],
        defaultLanguageCode: null,
        priceTypes: [],
        currencies: [],
        prices$: this.prices,
        usedMainData: [
          'languages',
          'price-types',
          'currencies'
        ]
      }
    },

    components: {
      Loading,
      CurrencyConverter
    },

    watch: {
      'prices': 'initPrices'
    },

    methods: {
      getCurrency(currencyCode) {
        return this.currencies.find(item => item.code === currencyCode)
      },

      onChange() {
        this.$emit('update:prices', this.prices$)
      },

      initLanguages(languages) {
        this.languages = languages.filter(language => {
          if (language.default) {
            this.defaultLanguageCode = language.code
          }

          return language.enabled
        }).sort((a, b) => a.position - b.position)
      },

      initPriceTypes(priceTypes) {
        DataHandler.methods.initPriceTypes.call(this, priceTypes)

        if (! this.availablePriceTypes) {
          return
        }

        let availablePriceTypes = this.availablePriceTypes

        if (! _.isArray(availablePriceTypes)) {
          availablePriceTypes = [availablePriceTypes]
        }

        this.priceTypes = this.priceTypes.filter(item => availablePriceTypes.indexOf(item.id) !== -1)
      },

      initPrices() {
        this.prices$ = this.priceTypes.reduce((acc, priceType) => {
          acc[priceType.id] = this.currencies.reduce((acc, currency) => {
            if (priceType.id in this.prices && currency.code in this.prices[priceType.id]) {
              acc[currency.code] = this.prices[priceType.id][currency.code]
            }
            else {
              acc[currency.code] = ''
            }

            return acc
          }, {})

          return acc
        }, {})
      },

      hasError(name) {
        return this.errors.has(name) || this.formErrors.has(name)
      },

      getError(name) {
        return this.errors.first(name) || this.formErrors.first(name)
      },

      // todo: аналогичная функция в TreeSelectTranslatable

      getTitle(priceType) {
        let title = priceType.i18n[this.activeLanguageCode].title

        if (! title && this.defaultLanguageCode) {
          title = priceType.i18n[this.defaultLanguageCode].title
        }

        return title
      },

      getCurrencyMaxValue(currency) {
        return 2147483647 / Math.pow(10 , currency.precision)
      }
    },

    created() {
      this.loadData()
        .then(() => {
          this.initPrices()
          this.loading = false
        })
    },
  }
</script>


<template>
  <div class="table-responsive">
    <loading :loading="loading">
      <table class="table table-bordered table-striped table-vcenter prices-table" v-if="! loading" style="min-width: 956px">
        <thead>
          <tr>
            <th>Тип</th>

            <th v-for="currency in currencies">
              {{ currency.name }}, {{ currency.code }}
            </th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="priceType in priceTypes">
            <td>
              <span class="prices-table__type">{{ getTitle(priceType) }}</span>
            </td>

            <td v-for="currency in currencies" :class="{'has-error': hasError(`prices.${priceType.id}.${currency.code}`)}">
              <div class="input-group">
                <input
                  type="text"
                  v-model="prices$[priceType.id][currency.code]"
                  :name="`prices.${priceType.id}.${currency.code}`"
                  @input="onChange" class="form-control"
                  v-number
                  v-validate="`decimal|min_value:1|max_value:${getCurrencyMaxValue(currency)}`">

                <currency-converter :value="prices$[priceType.id][currency.code]" :currency="getCurrency(currency.code)" />
              </div>

              <span v-show="hasError(`prices.${priceType.id}.${currency.code}`)" class="help-block">
                {{ getError(`prices.${priceType.id}.${currency.code}`) }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </loading>
  </div>
</template>
