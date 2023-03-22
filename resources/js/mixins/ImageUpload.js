import DropzoneGallery from '../components/DropzoneGallery'

export default {
  components: {
    DropzoneGallery
  },

  methods: {
    getToSaveData() {
      return {
        ... this.getEntityModel(),
        images: this.getToSaveImage(),
      }
    },

    getToSaveImage() {
      let image = this.getEntityModel().image

      if (image) {
        return [{
          id: image.id,
          modifications: image.modifications
        }]
      }
      else {
        return []
      }
    },

    updateImage(images = []) {
      if (images.length) {
        this[this.getEntityName()].image = images[0]
      }
      else {
        this[this.getEntityName()].image = false
      }
    },
  },

  computed: {
    dropzoneImage() {
      let entity = this.getEntityModel()
      return entity.image ? [entity.image] : []
    }
  }
}