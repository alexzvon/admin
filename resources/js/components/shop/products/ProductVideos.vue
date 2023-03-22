<template>
    <div class="block productVideos">

        <div class="block-title">
            <h2><i class="fa fa-youtube-play"></i> <strong>Видео</strong></h2>
        </div>

        <loading-spinner v-if="!loaded"></loading-spinner>

        <div v-if="loaded" class="block-section">

            <label>Добавить видео Vimeo</label>
            <input
                id="add-vimeo-video"
                name="add-vimeo-video"
              type="text"
              class="form-control"
              placeholder="Вставьте адрес ролика на vimeo и нажмите Enter"
              @keypress.enter="addVimeoVideo()"
              v-model="newVimeoVideoUrl">
            <label>Добавить видео YouTube</label>
            <input
                id="add-youtube-video"
                name="add-youtube-video"
              type="text"
              class="form-control"
              placeholder="Вставьте адрес ролика на youtube и нажмите Enter"
              @keypress.enter="addVideo()"
              v-model="newYoutubeVideoUrl">


            <div class="productVideos__empty" v-if="videos.length===0">
                Не добавлено ни одного ролика
            </div>

            <table class="table productVideos__list" v-if="videos.length > 0">
                <thead>
                    <tr>
                        <th width="270px">Превью</th>
                        <th>Заголовок</th>
                        <th class="text-center">Статус</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <ProductVideo
                      v-for="video in videos"
                      :video="video"
                      :key="video.id">
                    </ProductVideo>
                </tbody>
            </table>

        </div>

    </div>
</template>

<script>
  export default {
//    module.exports = {
        name: "ProductVideos",
        props: [
            "product_id"
        ],
        data() {
            return {
                loaded: false,
                newVimeoVideoUrl: '',
                newYoutubeVideoUrl: '',
                error: false,
            };
        },
        computed: {
            videos() {
                return this.$store.state.product.videos;
            }
        },
        methods: {
            addVideo() {
                if (this.isYoutubeUrlValid()) {
                    this.$store.dispatch("product/addProductVideo", {
                        product_id: this.product_id,
                        provider_id: 1,
                        url: this.newYoutubeVideoUrl,
                    }).then(() => {
                        this.newYoutubeVideoUrl = '';
                    })
                } else {
                    this.$core.notify("Неверный формат адреса youtube", {type: "error"});
                }

            },
            addVimeoVideo() {
                if (this.isVimeoUrlValid()) {
                    this.$store.dispatch("product/addProductVideo", {
                        product_id: this.product_id,
                        provider_id: 2,
                        url: this.newVimeoVideoUrl,
                    }).then(() => {
                        this.newVimeoVideoUrl = '';
                    })
                } else {
                    this.$core.notify("Неверный формат адреса Vimeo", {type: "error"});
                }
            },
            isYoutubeUrlValid() {
                let url = this.newYoutubeVideoUrl;
                let p = /^(?:https?:\/\/)?(?:m\.|www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
                if (url.match(p)){
                    return url.match(p)[1];
                }
                return false;
            },
            isVimeoUrlValid() {
                let url = this.newVimeoVideoUrl;
                let p = /(http|https)?:\/\/(www\.|player\.)?vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|video\/|)(\d+)(?:|\/\?)/;
                if (url.match(p)){
                    return url.match(p)[4];
                }
                return false;
            }
        },
        mounted() {
            this.$store.dispatch('product/getProductVideos', this.product_id).then(() => {
                this.loaded = true
            })
        },
        components: {
            ProductVideo: require('./ProductVideo').default
        }
    };
</script>

<style lang="scss">
    .productVideos{
        &__list{
            margin-top: 30px;
            & > thead{
                & > tr {
                    & > th{
                        padding: 0 0 5px;
                    }
                }
            }
            & > tbody {
                & > tr {
                    & > td{
                        padding: 10px 0 10px;
                    }
                }
            }
        }
        &__empty{
            margin-top: 15px;
        }
    }
</style>
