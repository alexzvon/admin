<script>
  import Core from '../../../../core'
  import DataHandler from '../../../../mixins/DataHandler'
  import InteriorProductPreviewModel from '../../../../resources/shop/interior/InteriorProductPreviewModel'

  let translate = ({x,y,startX,startY}, onUpdate) => (
    (dragEvent) => {

      x += dragEvent.pageX - startX
      y += dragEvent.pageY - startY

      onUpdate({x,y})

      startX = dragEvent.pageX
      startY = dragEvent.pageY
    }
  )

  export default {
    name: 'interior-point',

    mixins: [
      DataHandler
    ],

    props: {
      is_similar: null,

      legendPosition: null,

      x: {
        type: null,
        required: true,
      },

      y: {
        type: null,
        required: true
      },

      selectOn: {
        validator(value) {
          return ['dblclick', 'mousedown', 'click'].indexOf(value) !== -1
        },

        default: 'mousedown'
      },

      selected: {
        type: Boolean,
        default: true
      },

      productId: null,

      activeLanguageCode: null
    },

    data() {
      return {
        product: null,
        languages: null,
        usedMainData: [
          'languages',
        ]
      }
    },

    watch: {
      productId: 'getProductInfo'
    },

    created() {
      this.loadData()
        .then(() => {
          this.getProductInfo()
        })
    },

    methods: {
      getProductInfo() {
        if (! this.productId) {
          this.product = null
          return
        }

        new Core.requestHandler('get', '/api/shop/interiors/product/' + this.productId, {})
          .success(response => {
            this.product = new InteriorProductPreviewModel(response.data.product, this.languages)
          })
          .start()
      },

      handleTranslation(event) {
        event.stopPropagation()

        const drag = translate({
          x: this.x,
          y: this.y,
          startX: event.pageX,
          startY: event.pageY
        }, (payload) => {
          this.$emit("update", payload)
        })

        this.onDrag(drag);
      },

      onDrag(drag) {
        const up = () => {
          document.removeEventListener('mousemove', drag)
          document.removeEventListener('mouseup', up)
        };

        document.addEventListener('mousemove', drag)
        document.addEventListener('mouseup', up)
      },

      mousedown(event){
        this.$emit("mousedown", event)

        if(this.selectOn === 'mousedown' || this.selected === true) {
          this.$emit('onSelect')
          this.handleTranslation(event)
        }
      },
    },

    computed: {
      className() {
        let classNameModifs = [
          this.is_similar ? 'similar' : 'exactly',
          this.legendPosition.x,
          this.legendPosition.y
        ]

        return 'interior-point ' + classNameModifs.map(modif => 'interior-point--' + modif).join(' ')
      },
    }
  }
</script>

<template>
  <div :class="className" @mousedown="mousedown">
    <div class="interior-point__center"></div>

    <div class="interior-point__legend">
      <template v-if="product">
        <div class="product-preview">

          <div class="product-preview__image-box">
            <img
              class="product-preview__image"
              :src="$helpers.getImagePath(product.image.src)"
              :srcset="$helpers.getImagePath(product.image.srcset) + ' 2x'"
            >
          </div>

          <div class="product-preview__name">
            {{ product.i18n[activeLanguageCode].title }}
          </div>

          <div class="product-preview__price">
            {{ product.price.formatted }}
          </div>

          <div class="product-preview__button">
            <div class="button">
              Смотреть
            </div>
          </div>
        </div>
      </template>

      <template v-else>
        <div class="interior-point-loading"></div>
      </template>
    </div>
  </div>
</template>