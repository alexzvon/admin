<template>
    <div class="contents">

        <!--<div class="h1">Управление контентом</div>-->

        <div class="row">

            <div class="col-sm-4 col-lg-3 pr-0 mb-20">
                <div class="card">
                    <div class="card__title">Контентные группы</div>
                    <div class="card__list">
                        <div
                          @click="activeScope=scope.scope"
                          v-for="scope in scopes"
                          :key="scope.scope"
                          class="card__list__item hoverable"
                          :class="{ active: activeScope===scope.scope }">
                            {{ scope.scopeHumanName }}
                        </div>
                    </div>
                </div>


            </div>

            <div class="col-sm-8 col-lg-9">

                <div v-if="activeScope" class="h1">{{ contentsFiltered[0].scopeHumanName }}</div>

                <div class="card mb-15" v-for="block in blocks" :key="block.block">
                    <div class="card__title">{{ block.blockHumanName }}</div>
                    <table class="table card__table">
                        <tbody>

                            <ContentEdit
                              v-for="content in getContentsByBlock(block.block)"
                              :content="content"
                              :key="content.id">
                            </ContentEdit>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</template>

<script>
    import _ from "lodash";

    export default {
//    module.exports = {
        name: "Contents",
        props: [],
        data() {
            return {
                activeScope: false,
            };
        },
        computed: {
            contents() {
                return this.$store.state.contents.all;
            },
            scopes() {
                return _.uniqBy(_.map(this.contents, content => {
                    return {
                        scope: content.scope,
                        scopeHumanName: content.scopeHumanName
                    };
                }), 'scope');
            },
            blocks() {
                return _.uniqBy(_.map(this.contentsFiltered, content => {
                    return {
                        block: content.block,
                        blockHumanName: content.blockHumanName
                    };
                }), 'block');
            },
            contentsFiltered() {
                return this.contents.filter(content => {
                    let pass = true;
                    if (this.activeScope) {
                        pass = content.scope === this.activeScope;
                    }
                    return pass;
                });
            }
        },
        methods: {
            getContentsByBlock(block) {
                return _.filter(this.contentsFiltered, { block: block });
            }
        },
        mounted() {
            this.$store.dispatch('contents/get');
        },
        components: {
            ContentEdit: require('./ContentEdit.vue').default
        }
    };
</script>

<style lang="scss">
    .contents{
        width: 100%;
        &__wrapper{
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            align-items: stretch;
            justify-content: flex-start;
            width: 100%;
        }
        &__sidebar{
            width: 250px;
            position: relative;
            flex:none;
            margin-right: 20px;
            &__item{
                padding: 5px 0;
                font-size: 15px;
                line-height: 130%;
                cursor: pointer;

            }
        }
    }

    .contentsEditor{
        width: 100%;
    }

</style>
