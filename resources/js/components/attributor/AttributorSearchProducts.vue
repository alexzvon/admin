<template>
    <div class="attributorSearchProducts" v-on-clickaway="close">

        <input
            type="text"
            placeholder="Поиск товаров.."
            @click="open"
            v-model="query">

        <div v-if="results && expanded" class="attributorSearchProducts__expanded">
            <div class="card">
                <div class="card__list">
                    <div
                        v-for="result in results"
                        @click="select(result)"
                        class="card__list__item hoverable"
                        :key="'option_'+result.id">
                        {{ result.i18n[0].title }}
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    // module.exports = {
        name: "AttributorSearchProducts",
        props: [
            'exclude'
        ],
        data() {
            return {
                expanded: false,
                query: '',
                results: [],
            };
        },
        watch: {
            query(val, old) {
                if (val !== old) {
                    this.search();
                }
            }
        },
        computed: {},
        methods: {
            search() {
                this.$store.dispatch('product/searchProductByName', this.query).then(results => {
                    this.results = results;
                    this.open();
                })
            },
            open() {
                this.expanded = true;
                this.$emit('changed', true);
            },
            close() {
                this.expanded = false;
            },
            select(product) {
                this.$emit('selected', product);
                this.query = '';
            }
        },
        beforeMount() {
        }
    };
</script>

<style lang="scss">
    .attributorSearchProducts{
        width: 100%;
        flex: none;
        input{
            display: block;
            width: 100%;
        }
        &__expanded{
            color: #394263;
            position: absolute;
            z-index: 30;
            top: 100%;
            border: 0;
            width: 340px;
            max-height: 500px;
            overflow-y: scroll;
            overflow-x: hidden;
            transition: all 0.12s ease-in;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
            .card__list__item{
                font-size: 14px;
            }
        }
    }
</style>
