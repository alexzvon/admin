<template>
    <div>

        <div class="h1">Управление меню</div>

        <div class="row">
            <div class="col-sm-8">
                <div class="card">
                    <div class="card__title">Структура меню</div>
                    <div class="card__content">
                    <div class="menus">
                        <drag v-model="childLinks" :options="{ group: 'parent' }">
                            <MenuEditLink
                              v-for="link in childLinks"
                              :level="1"
                              :link="link"
                              :links="links"
                              :key="link.id">
                            </MenuEditLink>
                        </drag>
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div v-if="$core.user.can('shop.menus.create')" class="card">
                    <div class="card__title">Добавить пункт меню</div>
                    <div class="card__content">
                        <div class="form-group">
                            <label>Анкор (текст ссылки)</label>
                            <input type="text" class="form-control" v-model="newLink.name">
                        </div>
                        <div class="form-group">
                            <label>Ссылка</label>
                            <input type="text" class="form-control" v-model="newLink.url">
                        </div>
                        <button
                          @click="create()"
                          class="btn btn-primary">
                            Добавить
                        </button>
                    </div>
                </div>
            </div>
        </div>



    </div>
</template>

<script>
    import _ from "lodash";

    export default {
        name: "menuEdit",
        data() {
            return {
                showNewLinkForm: false,
                newLink: {
                    name: '',
                    url: '',
                }
            };
        },
        computed: {
            links() {
                return _.sortBy(this.$store.state.menu.menus, 'position');
            },
            childLinks: {
                get() {
                    return _.filter(this.links, { parent_menu_id: null });
                },
                set(links, oldLinks) {
                    this.$store.dispatch('menu/sorting', links);
                }
            }
        },
        methods: {
            create() {
                this.$store.dispatch('menu/create', this.newLink).then(() => {
                    this.newLink.name = '';
                    this.newLink.url = '';
                }).catch(() => {
                    //
                })
            },
            redirectToTable() {
                let path = this.getPathToTable();
                if (window.history.state) {
                    let previousPath = window.history.state.previousPath;

                    if (previousPath && previousPath !== path && previousPath.indexOf(path) !== -1) {
                        this.$router.go(-1);
                        return;
                    }
                }
                this.$router.push(path);
            },
        },
        mounted() {
            this.$store.dispatch('menu/get')
        },
        components: {
            MenuEditLink: require('./MenuEditLink').default
        }
    };
</script>

