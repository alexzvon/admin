<template>
    <div>
        <div class="block full">
            <div class="block-title clearfix">

                <h1>Всплывающий баннер</h1>

                <div class="block-title-control">
                    <router-link v-if="$core.user.can('shop.expand-banners.create')" to="/shop/expand-banners/create" class="btn btn-sm btn-success active">
                        <i class="fa fa-plus-circle"></i> Создать
                    </router-link>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-middle table-center table-condensed table-bordered table-hover table-sortable table-attributes">
                    <thead>
                        <tr>
                            <th>
                                <span class="table-column-id">ID</span>
                            </th>

                            <th style="width:100%">Название</th>

                            <th v-if="$core.user.can('shop.expand-banners.edit')">
                                <span class="table-column-enabled">
                                    Статус
                                </span>
                            </th>

                            <th v-if="$core.user.can('shop.expand-banners.delete')">
                                <span class="table-column-delete"></span>
                            </th>
                        </tr>
                    </thead>

                    <tbody v-if="expandBanners.length > 0" class="ui-sortable">
                        <tr v-for="expandBanner in expandBanners" class="">

                            <td>
                                <span class="table-column-id">
                                    <router-link :to="'/shop/expand-banners/'+expandBanner.id">
                                        {{ expandBanner.id }}
                                    </router-link>
                                </span>
                            </td>

                            <td style="width:100%">
                                <router-link :to="'/shop/expand-banners/'+expandBanner.id">
                                    {{ expandBanner.name }}
                                </router-link>
                            </td>

                            <td v-if="$core.user.can('shop.expand-banners.edit')">
                                <span class="table-column-enabled table-remove-restore__restore">
                                    <toggle
                                      @change="$store.dispatch('expandBanner/toggleStatus', expandBanner)"
                                      :checked="expandBanner.enabled">
                                    </toggle>
                                </span>
                            </td>

                            <td v-if="$core.user.can('shop.expand-banners.delete')">
                                <span class="table-column-delete">
                                    <a
                                      class="btn btn-sm btn-danger"
                                      @click="$store.dispatch('expandBanner/delete', expandBanner)">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </span>
                            </td>
                        </tr>

                        <tr v-if="!(expandBanners && expandBanners.length)">
                            <td class="text-center" colspan="5">Пока не добавлено ни одного баннера</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    module.exports = {
        name: "expandBannersTable",
        props: [],
        data() {
            return {};
        },
        computed: {
            expandBanners() {
                return this.$store.state.expandBanner.expandBanners
            }
        },
        methods: {},
        beforeMount() {
            this.$store.dispatch('expandBanner/getAll')
        }
    };
</script>

<style lang="scss">

</style>