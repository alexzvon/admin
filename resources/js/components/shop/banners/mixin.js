export default {
  computed: {
    bannerPlacesSelectOptions() {
      return this.bannerPlaces.reduce((acc, item) => {
        if (item.type === this.bannerType$) {
          acc.push({
            id: item.id,
            title: item.name
          })
        }

        return acc
      }, [])
    }
  }
}