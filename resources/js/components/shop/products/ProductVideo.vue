<template>
    <tr class="productVideo">
        <td>
            <img v-if="video.provider_id === 1" width="200px" :src="'https://img.youtube.com/vi/'+video.video_id+'/mqdefault.jpg'" alt="">
            <img v-if="video.provider_id === 2" width="200px" :src="vimeoThumbUrl" alt="">
        </td>
        <td>
            <input type="text" class="form-control" v-model="video.title">
            <button
              v-if="changed"
              @click="save()"
              class="btn btn-sm btn-default productVideo__btn-save">
                Сохранить
            </button>
        </td>
        <td class="text-center">
            <label class="switch switch-info">
                <input type="checkbox" name="visible" v-model="visible">
                <span></span>
            </label>
        </td>
        <td class="text-center">
            <button
              @click="$store.dispatch('product/deleteProductVideo', video)"
              class="btn btn-sm pull-right btn-danger"
              title="Удалить">
                <i class="fa fa-trash-o" aria-hidden="true"></i>
            </button>
        </td>
    </tr>
</template>

<script>
    import _ from "lodash";

    export default {
//    module.exports = {
        name: "ProductVideo",
        props: [
            "video"
        ],
        data() {
            return {
                initialTitle: '',
                vimeoThumbUrl: '',
            };
        },
        computed: {
            changed() {
                return (this.initialTitle != this.video.title);
            },
            title: {
                get() {
                    return this.initialTitle;
                },
                set(val, oldVal) {
                    this.initialTitle = val;
                }
            },
            visible: {
                get() {
                    return +this.video.visible === 1;
                },
                set(val, oldVal) {
                    let video = _.cloneDeep(this.video);
                    if (val) {
                        video.visible = 1;
                    } else {
                        video.visible = 0;
                    }
                    this.$store.dispatch("product/updateProductVideo", video);
                }
            },
        },
        methods: {
            save() {
                let video = _.cloneDeep(this.video);
                video.title = this.initialTitle

                this.$store.dispatch("product/updateProductVideo", this.video).then(() => {
                    this.$core.notify("Изменения сохранены", {type: "success"});
                    this.initialTitle = this.video.title
                });
            }
        },
        mounted() {
            this.initialTitle = this.video.title;
        },
        created() {
            if (this.video.provider_id === 2) {
                this.$store.dispatch("product/getVimeoThumb",
                    {'video_id': this.video.video_id})
                    .then((response) => {
                        this.vimeoThumbUrl = response.data.url;
                    });
            }
        }
    };
</script>

<style lang="scss">
    .productVideo {
        td {
            padding: 10px 15px;
        }
        &__btn-save{
            margin-top: 10px;
        }
    }

</style>
