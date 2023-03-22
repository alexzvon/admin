<template>
    <div class="otherProductAdd" v-on-clickaway="close">
        <div class="otherProductAdd__search">
            <input
                @click.prevent.stop="init"
                type="text"
                placeholder="Добавить.."
                v-model="searchQuery">
        </div>

        <ul v-if="expanded && products.length" class="otherProductAdd__options" @scroll="onScroll" ref="ul">
            <li
                v-for="product in products"
                @click="add(product.id)"
                class="dropdown-item">
                {{ product.title }}
            </li>
        </ul>
    </div>
</template>

<script>
import { getAddSearchProducts } from '../../api/otherproductoptions';

export default {
    name: "OtherProductAdd",
    props: {
        product_id: {
            required: true,
            type: Number,
            default: 0,
            validator: function (value) {
                return value > 0;
            }
        },
    },
    data() {
        return {
            products: [],
            add_product_id: 0,

            searchQuery: "",
            expanded: false,

            delay: 1000,
            delayTimer: {},

            page: 0,
            limit: 50,

            element: "",
            scrollTop: 0,
        };
    },
    computed: {
        offset() {
            return this.page * this.limit;
        }
    },
    watch: {
        searchQuery(val, old) {
            this.products = [];
            this.page = 0;

            if (val.length > 3) {
                this.doSearch();
            }
        },
    },
    methods: {
        onScroll(event) {
            this.element = event.target;

            if (this.element.scrollHeight - this.element.scrollTop === this.element.clientHeight) {
                this.page++;
                this.scrollTop = this.element.scrollTop;

                this.loadAddProducts(true);
            }
        },

        init() {
            if (0 < this.products.length && 0 < this.searchQuery.length) {
                this.expanded = true;
            } else {
                this.products = [];
                this.searchQuery = '';
                this.page = 0;

                this.loadAddProducts(false);
            }
        },

        add(product_id) {
            this.$emit('addLinkProduct', product_id);

            this.add_product_id = product_id;
            this.expanded = false;

            let products = [];
            let j = 0;

            for (let i = 0; i < this.products.length; i++) {
                if (product_id !== this.products[i].id) {
                    products[j++] = this.products[i];
                }
            }

            this.products = products;
            this.expanded = true;
        },

        close() {
            this.expanded = false;
        },

        doSearch() {
            clearTimeout(this.delayTimer);
            this.delayTimer = setTimeout( () => (this.loadAddProducts(false)), this.delay);
        },

        async loadAddProducts(add) {
            this.expanded = false;

            const loading = this.$loading({
                lock: true,
                text: 'Loading',
                spinner: 'el-icon-loading',
                background: 'rgba(0, 0, 0, 0.1)'
            });

            let payload = {
                product_id: this.product_id,
                search: this.searchQuery.replace(' ','!!space!!'),
                page: this.page,
                limit: this.limit,
                offset: this.offset,
            };

            try {
                const { data } = await getAddSearchProducts(payload);

                loading.close();
                let lentgh = this.products.length;

                if (add) {
                    for (let i = 0; i < data.products.length; i++) {
                        this.products[lentgh++] = data.products[i];
                    }
                } else {
                    this.products = data.products;
                }

                this.expanded = true;
                this.page = data.page;

                if (add && 0 < data.products.length) {
                    setTimeout(this.setScrollTop, 1000);
                }
            } catch (error) {
                console.log(error);
                loading.close();
            }
        },

        getScrollTop() {
            this.scrollTop = this.$refs.ul.scrollTop;
        },

        setScrollTop() {
            console.log(this.$refs.ul);
            console.log(this.scrollTop);
            this.$refs.ul.scrollTop = this.scrollTop;
        },
    },
}
</script>

<style scoped lang="scss">
.otherProductAdd {
    width: 100%;
    display: block;
    margin-bottom: 20px;
    position: relative;
    &__search {
        height: 37px;
        padding: 0;
        //margin: 0 15px;
        line-height: 36px;
        border: 1px solid #e4e4e4;
        position: relative;
        background: white;
        font-size: 14px;
        border-radius: 3px;
        z-index: 20;
        transition: all 0.12s linear;
        overflow: hidden;
        input {
            width: 100%;
            height: 36px;
            border: 0;
            padding: 5px 10px;
            &, &:active, &:focus {
                outline: none;
                box-shadow: none;
            }
        }
    }
    &__options {
        position: absolute;
        top: 100%;
        background: white;
        border: 0;
        width: 100%;
        z-index: 25;
        transition: all 0.12s ease-in;
        padding: 0;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
        overflow-x: hidden;
        max-height: 400px;
        overflow-y: scroll;
        li {
            padding: 0 10px;
            line-height: 30px;
            transition: all 0.06s linear;
            font-size: 14px;
            &:hover {
                cursor: pointer;
                background: #f9fafc;
                color: #2b2b2c;
            }
        }
    }
    &__create{
        font-weight: 500;
    }
}
</style>