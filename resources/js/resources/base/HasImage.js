function getDefaultImage() {
  return {
    src: '/img/no-photo.jpg',
    srcset: '/img/no-photo.jpg',
  }
}

export default function (imageType) {
  return function (data) {
    try {
      return {
        src: data.image[imageType].src,
        srcset: data.image[imageType].srcset,
      }
    }
    catch(e) {
      return getDefaultImage()
    }
  }
}