<template>
    <div class="block full">

        <loading-spinner v-if="!expandBanner"></loading-spinner>

        <template v-if="expandBanner">
            <div class="block-title clearfix">
                <h1>Баннер {{ expandBanner.name }}</h1>
                <div class="block-title-control">
                    <a
                      @click="$router.push('/shop/expand-banners')"
                      class="btn btn-sm btn-default btn-alt">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                    <span class="btn-separator-xs"></span>
                    <a
                      v-if="$core.user.can('shop.expand-banners.update')"
                      class="btn btn-sm btn-primary btn-alt active"
                      @click="save()">
                        Сохранить
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-5">
                    <uploadPicSingle
                      :remove="true"
                      name="Загрузить фон"
                      :path="'/api/shop/expand-banners/'+expandBanner.id+'/upload?api_token='+$core.getApiToken()"
                      :file="expandBanner.src">
                    </uploadPicSingle>
                </div>
                <div class="col-sm-7">

                    <div class="form-group">
                        <div class="form-horizontal">
                            <toggle
                              @change="statusChange()"
                              :checked="expandBanner.enabled">
                            </toggle>
                            <label>&nbsp;&nbsp;Статус баннера</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Название (для внутреннего использования)</label>
                        <input type="text" class="form-control" v-model="expandBanner.name">
                    </div>
                    <div class="form-group">
                        <label>Заголовок</label>
                        <input type="text" class="form-control" v-model="expandBanner.i18n.title">
                    </div>
                    <div class="form-group">
                        <label>Призыв к действию</label>
                        <textarea class="form-control" v-model="expandBanner.i18n.offer"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Текст на кнопке</label>
                        <input type="text" class="form-control" v-model="expandBanner.i18n.button_text">
                    </div>
                    <div class="form-group">
                        <label>Подсказка в поле ввода</label>
                        <input type="text" class="form-control" v-model="expandBanner.i18n.input_placeholder">
                    </div>
                </div>
            </div>
        </template>

    </div>
</template>

<script>
    module.exports = {
        name: "expandBannersEdit",
        props: [],
        data() {
            return {};
        },
        computed: {
            expandBanner() {
                return this.$store.state.expandBanner.expandBanner;
            }
        },
        methods: {
            save() {
                this.$store.dispatch("expandBanner/update", this.expandBanner).then(() => {
                    this.$core.notify('Успешно сохранено', {type:'success'});
                });
            },
            statusChange() {
                this.expandBanner.enabled = !this.expandBanner.enabled
            }
        },
        mounted() {
            this.$store.dispatch("expandBanner/getOne", {id: this.$route.params.id});
        }
    };
</script>
<style lang="scss">

</style>