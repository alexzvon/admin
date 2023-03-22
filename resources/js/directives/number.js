export default {
  number: {
    bind(el, binding, vnode) {
      el.addEventListener('keydown', binding.def.handler);
    },

    unbind(el, binding, vnode) {
      el.removeEventListener('input', binding.def.handler);
    },

    handler(e) {
      if (event.metaKey) return
      let charCode = (e.which) ? e.which : e.keyCode

      if (charCode === 32 || (charCode > 57 && charCode !== 188 && charCode !== 190)) {
        e.preventDefault()
      }
      else {
        let decimalSeparatorPresents = new RegExp(/[\.\,]/).test(e.target.value)

        if ( charCode === 188 && (e.key !== ',' || decimalSeparatorPresents) ) {
          e.preventDefault()
        }

        if ( charCode === 190 && (e.key !== '.' || decimalSeparatorPresents) ) {
          e.preventDefault()
        }
      }
    }
  }
}
