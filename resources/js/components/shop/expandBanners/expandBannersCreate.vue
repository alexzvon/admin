<template>
    <div class="block full">

        <div class="block-title clearfix">

            <h1>Новый всплывающий баннер</h1>

            <div class="block-title-control">

                <a
                  @click="$router.push('/shop/expand-banners')"
                  class="btn btn-sm btn-default btn-alt">
                    <i class="fa fa-arrow-left"></i>
                </a>

                <span class="btn-separator-xs"></span>

            </div>
        </div>


        <div>
            <div class="form-group">
                <label>Название (для внутреннего использования)</label>
                <input type="text" class="form-control" v-model="expandBanner.name" autofocus>
            </div>
            <a
              v-if="$core.user.can('shop.expand-banners.create')"
              class="btn btn-sm btn-primary btn-alt active"
              @click="create()">
                Сохранить и перейти к редактированию
            </a>
        </div>


    </div>
</template>

<script>
    module.exports = {
        name: "expandBannersCreate",
        props: [],
        data() {
            return {
                expandBanner: {
                    name: ''
                }
            };
        },
        computed: {},
        methods: {
            create() {
                this.$store.dispatch('expandBanner/create', this.expandBanner).then(expandBanner => {
                    this.$router.push('/shop/expand-banners/'+expandBanner.id)
                }).catch(response => {
                    console.log(response);
                })
            }
        },
        beforeMount() {
        }
    };
</script>

<style lang="scss">

</style>