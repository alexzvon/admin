import _ from "lodash";
import datePicker from 'vue-bootstrap-datetimepicker'
import moment from 'moment'

export default {
  components: {
    datePicker,
  },

  methods: {
    datePickerShow(field) {
      let entity = this.getEntityModel()

      if (_.isNil(_.get(entity, field))) {
        let date = new Date()
        date.setHours(0, 0, 0, 0)

        _.set(entity, field, this.getDateMoment(date))
        this.dateChanged(field)
      }
    },

    getBaseDatePickerConfig() {
      return {
        locale: 'ru',

        format: this.getDateFormat(),
        useCurrent: false,
        sideBySide: false,
        showClear: true,
        showClose: true,
      }
    },

    getDateFormat() {
      return 'DD-MM-YYYY HH:mm:ss'
    },

    getDateMoment(date) {
      return moment(date, this.getDateFormat())
    },

    dateChanged(field) {

    },
  }
}