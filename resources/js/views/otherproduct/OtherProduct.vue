<template>
    <div>
        <other-product-add
            @addLinkProduct="addLinkProduct"
            :product_id="product_id"
        />
        <other-product-view-del
            v-for="product in products"
            :id="product.id"
            :key="product.id"
            :title="product.title"
            @delLinkProduct="delLinkProduct"
        />
    </div>
</template>

<script>

import OtherProductViewDel from "./OtherProductViewDel";
import OtherProductAdd from "./OtherProductAdd";
import { getOtherProductOption,
    delOtherProductOption,
    addOtherProductOption } from "../../api/otherproductoptions";

export default {
    name: "OtherProduct",
    components: {
        OtherProductViewDel,
        OtherProductAdd,
    },
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
    data: () => ({
        products: [],
    }),
    created() {
        this.loadOtherProductOption();
    },
    methods: {
        async addLinkProduct(add_product_id) {
            let payload = {
                product_id: this.product_id,
                add_product_id: add_product_id,
            };

            try {
                const { data } = await addOtherProductOption(payload);
                this.loadOtherProductOption();
            } catch (error) {
                console.log(error);
            }
        },

        async delLinkProduct(add_product_id) {
            let payload = {
                product_id: this.product_id,
                add_product_id: add_product_id,
            };

            try {
                const { data } = await delOtherProductOption(payload);
                this.loadOtherProductOption();
            } catch (error) {
                console.log(error);
            }
        },

        async loadOtherProductOption() {
            try {
                const { data } = await getOtherProductOption(this.product_id);
                this.products = data.products;
            } catch (error) {
                console.log(error);
            }
        }
    },
}
</script>

<style scoped>

</style>