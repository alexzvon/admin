let messages = {
  'error' : 'Ошибка',
  'warning': 'Предупреждение'
}

export default class HandleableException {
  constructor(message, type = 'error') {
    this.message = message
    this.type = type
  }

  toString() {
    return `${messages[this.type]}: ${this.message}`
  }

  getNotifyParams() {
    return {
      title: messages[this.type],
      text: this.message,
      type: this.type
    }
  }
}