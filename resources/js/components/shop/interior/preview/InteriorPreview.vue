<script>
  import InteriorPoint from './InteriorPoint'
  import Core from '../../../../core'

  export default {
    name: 'interior-preview',

    components: {
      InteriorPoint
    },

    props: {
      image: {
        type: String,
        default: '/img/no-photo.jpg'
      },
      points: null,
      activeLanguageCode: null
    },

    data() {
      return {
        pointSize: 28,
        imageCb: false
      }
    },

    watch: {
      'points': 'setPoints',
      'image': 'setImage'
    },

    mounted() {
      this.setImage()
    },

    methods: {
      setPoints() {
        this.points.forEach(point => {
          let posX = point.position_x ? point.position_x : 50
          let posY = point.position_y ? point.position_y : 50

          point.x = (this.$el.scrollWidth - this.pointSizeCorrection) / 100 * parseFloat(posX)
          point.y = (this.$el.scrollHeight - this.pointSizeCorrection) / 100 * parseFloat(posY)
        })

        this.recalculatePointsPosition()
      },

      setImage() {
        if (! this.imageCb) {
          this.imageCb = true

          Core.onImageLoaded(this.$el.querySelector('img'), () => {
            this.imageCb = false
            this.setPoints()
          })
        }
      },

      recalculatePointsPosition() {
        this.points.forEach(point => this.setPointPosition(point, point))
      },

      setPointPosition(point, coordinates) {
        this.setPointAxesCoordinates(point, coordinates.x, 'x', this.$el.scrollWidth)
        this.setPointAxesCoordinates(point, coordinates.y, 'y', this.$el.scrollHeight)
      },

      setPointAxesCoordinates(point, value, axes, max) {
        let pos = Math.max(0, value)
        pos = Math.min(max - this.pointSizeCorrection, pos)

        point[axes] = pos
        point['position_' + axes] = (pos / (max - this.pointSizeCorrection) * 100).toFixed(8)
      },

      pointStyle(point) {
        return 'left: calc(' + point.position_x + '% - 18px); top: calc(' + point.position_y + '% - 18px);'
      },

      getPointLegendPosition(point) {
        return {
          x: parseInt(point.position_x) >= 50 ? 'left' : 'right',
          y: parseInt(point.position_y) >= 50 ? 'top' : 'bottom'
        }
      },
    },

    computed: {
      previewStyle() {
        if (this.image) {
          return 'float: left;'
        }
      },

      pointSizeCorrection() {
        return this.pointSize / 2
      },
    }
  }
</script>

<template>
  <div class="interior-preview" :style="previewStyle">
    <img class="interior-preview__image" :src="image">

    <template v-for="point in points">
      <div
        :key="point.key"
        class="interior-preview__point"
        :style="pointStyle(point)"
      >
        <interior-point
          :x="point.x"
          :y="point.y"
          :is-similar="point.is_similar"
          :product-id="point.product_id"
          :legend-position="getPointLegendPosition(point)"
          @update="setPointPosition(point, $event)"
          :active-language-code="activeLanguageCode"
        ></interior-point>
      </div>

    </template>
  </div>
</template>