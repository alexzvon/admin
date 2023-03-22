import DatePicker from './DatePicker'

export default {
  mixins: [
    DatePicker
  ],

  methods: {
    getFinishDatePickerConfig() {
      let config = {
        ... this.getBaseDatePickerConfig()
      }

      let entity = this.getEntityModel()

      if (entity.date_start) {
        config.minDate = this.getDateMoment(entity.date_start).add(1, 'days')
      }

      return config
    },

    dateChanged(field) {
      let entity = this.getEntityModel()

      if (field === 'date_start') {
        this.dateFinishConfig = this.getFinishDatePickerConfig()
      }

      if (this.dateFinishConfig.minDate) {
        let dayFinish = this.getDateMoment(entity.date_finish || 0)

        if (dayFinish.isBefore(this.dateFinishConfig.minDate)) {
          entity.date_finish = this.dateFinishConfig.minDate
        }
      }
    }
  }
}