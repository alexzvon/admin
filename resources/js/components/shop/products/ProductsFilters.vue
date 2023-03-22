<template>
    <div class="productsFilters">
        <div class="productsFilters__wrapper">

            <div class="productsFilters__bar">

                <!--<div class="card__title">Фильтры</div>-->

                <div class="card__content">
                    <div class="row">
                        <div class="productsFilters__filter col-sm-6">
                            <!--<div class="productsFilters__filter__title">Категории</div>-->
                            <selector
                                v-if="flatCategories.length > 0"
                                label="Категории"
                                :borderless="false"
                                :value="selectedCategories"
                                placeholder="Категории"
                                :multiple="true"
                                @selected="selectCategory"
                                @clear="resetCategories"
                                :options="flatCategories">
                            </selector>
                        </div>

                        <div class="productsFilters__filter col-sm-6">
                            <selector
                                v-if="suppliers.length > 0"
                                label="Поставщики"
                                :borderless="false"
                                :value="selectedSuppliers"
                                placeholder="Поставщики"
                                :multiple="true"
                                @selected="selectSupplier"
                                @clear="resetSuppliers"
                                :options="suppliers">
                            </selector>
                        </div>
                    </div>
                </div>

            </div>

            <div class="productsFilters__content">
                <slot></slot>
            </div>

        </div>
    </div>
</template>

<script>
    import _ from 'lodash';

    export default {
//    module.exports = {
        name: "ProductsFilters",
        props: [],
        data() {
            return {
                selectedCategories: [],
                selectedSuppliers: []
            };
        },
        computed: {
            flatCategories() {
                return this.$store.getters["categories/flatCategories"];
            },
            categories() {
                return this.$store.state.categories.all;
            },
            suppliers() {
                return this.$store.state.suppliers.all;
            }
        },
        methods: {
            selectCategory(categories) {
                this.$store.commit("products/setFilter", [
                    'categories',
                    _.map(categories, 'id')
                ]);
                this.filtersUpdated();
            },
            selectSupplier(suppliers) {
                this.$store.commit("products/setFilter", [
                    'suppliers',
                    _.map(suppliers, 'id')
                ]);
                this.filtersUpdated();
            },
            resetCategories() {
                this.$store.commit("products/setFilter", [ 'categories', [] ]);
                this.filtersUpdated();
            },
            resetSuppliers() {
                this.$store.commit("products/setFilter", [ 'suppliers', [] ]);
                this.filtersUpdated();
            },
            filtersUpdated() {
                this.$emit('updated');
            }
        },
        beforeMount() {
            this.$store.dispatch("categories/get");
            this.$store.dispatch("suppliers/get");
        }
    };
</script>

<style lang="scss">
    .productsFilters {
        margin: -20px -20px 20px;
        &__wrapper {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            align-items: flex-start;
            justify-content: flex-start;
        }
        &__bar {
            flex: none;
            width: 100%;
            background: #fafafa;
        }
        &__content {
            width: 100%;
        }
        &__filter {
            &__title {
                font-weight: 500;
                padding: 20px 0 10px;
            }
            &__selector {
            }
            &__list {
                max-height: 200px;
                overflow: hidden;
                overflow-y: scroll;
                &Item {

                }
            }
        }
    }
</style>
