import Core from '../core'

import { Validator } from 'vee-validate'
import Base from './Base'


export default {
  mixins: [Base],

  methods: {
    /*
      Установка ошибок валидации, пришедших с сервера
     */
    setValidationErrors(errors = {}) {
      for (let fieldName in errors) {
        if (this.formErrors.has(fieldName)) {
          this.formErrors.remove(fieldName)
        }

        this.formErrors.add(fieldName, errors[fieldName])
      }
    },

    /*
      Добавление метода валидации - проверки url-а
     */
    extendSlugChecker() {
      Validator.extend('slug_exist', {
        getMessage: field => `Slug занят.`,
        validate: slug => new Promise(resolve => {
          new Core.requestHandler('get', this.makePageApiUrl('slug'), {slug})
            .success(() => resolve( {valid: true} ))
            .fail(() => resolve( {valid: false} ))
            .start()
        })
      })
    },
  },

  created() {
    this.extendSlugChecker()
  }
}