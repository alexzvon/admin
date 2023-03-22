<template>
    <div class="block full">

        <div class="block-title clearfix">

            <h1>Создание товара</h1>

            <div class="block-title-control">

                <a
                  @click="$router.push('/shop/products')"
                  class="btn btn-sm btn-default btn-alt">
                    <i class="fa fa-arrow-left"></i>
                </a>

                <span class="btn-separator-xs"></span>

            </div>
        </div>


        <div>
            <div class="form-group">
                <label>Название товара</label>
                <input type="text" class="form-control" v-model="product.title" autofocus>
            </div>
            <a
              v-if="$core.user.can('shop.products.create')"
              class="btn btn-sm btn-primary btn-alt active"
              @click="createProduct()">
                Сохранить и перейти к редактированию
            </a>
        </div>


    </div>
</template>

<script>
    module.exports = {
        name: "ProductCreate",
        props: [],
        data() {
            return {
                product: {
                    title: ''
                }
            };
        },
        computed: {},
        methods: {
            createProduct() {
                this.$store.dispatch('product/createProduct', this.product).then(product => {
                    this.$router.push('/shop/products/'+product.id)
                }).catch(response => {
                    console.log(response);
                })
            }
        }
    };
</script>

<style lang="scss">

</style>