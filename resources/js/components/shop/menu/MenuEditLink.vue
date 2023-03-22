<template>
    <div :class="'lvl_'+level">

        <div class="menus__link">
            <div class="menus__link__content">
                <div class="menus__link__anchor">
                    {{ link.name }}
                    <a
                      target="_blank"
                      :href="link.url"
                      class="menus__link__url">
                        {{ link.url }}
                    </a>
                </div>
                <div class="menus__link__options">
                    <div class="menus__link__visibility">
                        <div
                          @click="$store.dispatch('menu/toggleStatus', link)"
                          v-if="link.enabled">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="20" viewBox="0 0 24 24"><path fill="#069f71" d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                        </div>
                        <div
                          @click="$store.dispatch('menu/toggleStatus', link)"
                          v-if="!link.enabled">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="20" viewBox="0 0 24 24"><path fill="#394263" d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/></svg>
                        </div>
                    </div>
                    <div class="menus__link__btnEditor">
                        <button @click="editorOpened=!editorOpened" class="btn menus__link__btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="20" viewBox="0 0 24 24"><path fill="#394263" d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="editorOpened" class="menusEditor">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-horizontal form-group switch-group">
                            <toggle
                              @change="$store.dispatch('menu/toggleStatus', link)"
                              :checked="link.enabled">
                            </toggle>
                            <label v-if="link.enabled">Отображается на сайте</label>
                            <label v-if="!link.enabled">Скрыт</label>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Анкор (текст ссылки)</label>
                            <input type="text" class="form-control" v-model="link.name">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Ссылка</label>
                            <input type="text" class="form-control" v-model="link.url">
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <selector
                          label="Родительский раздел"
                          :borderless=false
                          :value=link.parent_menu_id
                          placeholder='Не выбрано'
                          :multiple=false
                          @selected="updateParentLink"
                          @clear="updateParentLink(false)"
                          :options=links>
                        </selector>
                    </div>
                </div>
                <div class="mt-15">
                    <button
                      v-if="$core.user.can('shop.menus.edit')"
                      @click="$store.dispatch('menu/updateLink', link)"
                      class="btn btn-primary">
                        Сохранить
                    </button>
                    <button
                      v-if="$core.user.can('shop.menus.delete')"
                      @click="$store.dispatch('menu/delete', link)"
                      class="btn btn-danger">
                        Удалить
                    </button>
                </div>

            </div>
        </div>

        <drag v-model="childLinks" :options="{ group: 'parent' }">
            <MenuEditLink
              v-for="link in childLinks"
              :level="level+1"
              :link="link"
              :links="links"
              :key="link.id">
            </MenuEditLink>
        </drag>

    </div>

</template>

<script>
    import _ from "lodash";

    export default {
//    module.exports = {
        name: "MenuEditLink",
        props: [
            'link',
            'links',
            'level'
        ],
        data() {
            return {
                editorOpened: false
            };
        },
        computed: {
            childLinks: {
                get() {
                    return _.filter(this.links, { parent_menu_id: this.link.id });
                },
                set(links, oldLinks) {
                    this.$store.dispatch('menu/sorting', links);
                }
            }
        },
        methods: {
            updateParentLink(payload) {
                if (payload) {
                    this.link.parent_menu_id = payload.id;
                } else {
                    this.link.parent_menu_id = null;
                }
                this.save();
            }
        },
        components: {
            MenuEditLink: require('./MenuEditLink.vue').default
        }
    };
</script>

<style lang="scss">
    .menus{
        .lvl_2{
            margin-left: 30px;
        }
        &__link{
            background: #f9fafc;
            padding: 8px 15px;
            margin-bottom: 10px;
            &__content{
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
            &__anchor{
                font-weight: 500;
                font-size: 15px;
            }
            &__url{
                font-weight: 400;
                display: block;
                font-size: 13px;
                padding-top: 2px;
            }
            &__visibility{
                flex:none;
                width: 50px;
            }
            &__options{
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: center;
            }
            &__btnEditor{
                flex:none;
                width: 50px;
            }
            &__btn{
                background: transparent;
            }
        }
        &Editor{
            padding-top: 15px;
            margin-top: 15px;
            border-top: 1px solid rgb(234, 237, 241);
        }
    }
</style>
