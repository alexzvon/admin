import FontResizer from '../../../FontResizer'

export default {
  components: {
    FontResizer
  },

  props: {
    image: null,

    gradientFrom: {
      default: '#fcc600'
    },

    gradientTo: {
      default: '#fdda55'
    },

    gradientType: {
      default: 'linear'
    },

    gradientAngle: {
      default: 45,
    },

    title: String,
    caption: String,
    buttonText: String,

    link: String,

    titleColor: String,
    captionColor: String,

    buttonColor: String,
    buttonBackground: String,
  },

  computed: {
    gradient() {
      if (this.gradientType === 'radial') {
        return `radial-gradient(${this.gradientFrom}, ${this.gradientTo})`
      }

      return `linear-gradient(${this.gradientAngle}deg, ${this.gradientFrom} 0%, ${this.gradientTo} 100%)`
    },

    buttonStyle() {
      let style = {}

      if (this.buttonColor) {
        style.color = this.buttonColor
      }

      if (this.buttonBackground) {
        style.backgroundColor = this.buttonBackground
      }

      return style
    },

    background() {
      if (this.backgroundImage) {
        return 'url(' + this.backgroundImage + ')'
      }

      return this.gradient
    },
  }
}