<template>
    <div>
        <h1>Выберите тип отображаемого блока баннеров</h1>

        <div class="btn btn-primary" v-on:click="setType">
            Сохранить
        </div>
        <hr/>

        <label for="one">
            <img :src="getImgUrl(1)" alt="Тип 1" class="banner-type_img">
        </label>
        <br>
        <input type="radio" id="one" value="1" v-model="typeId">
        <hr/>
        <label for="two">
            <img :src="getImgUrl(2)" alt="Тип 2" class="banner-type_img">
        </label>
        <br>
        <input type="radio" id="two" value="2" v-model="typeId">
        <hr/>
        <label for="three">
            <img :src="getImgUrl(3)" alt="Тип 3" class="banner-type_img">
        </label>
        <br>
        <input type="radio" id="three" value="3" v-model="typeId">
    </div>
</template>

<script>
    import Core from "../../../core";

    export default {
        name: "banners-types",

        data() {
            return {
                typeId: null,
            }
        },

        methods: {
            fetchType() {
                return new Promise(resolve => {
                    new Core.requestHandler("get", '/api/shop/banners-type')
                        .success(response => {
                            this.typeId = response.data.typeId;
                        })
                        .start();
                });
            },

            setType() {
                return new Promise(resolve => {
                    new Core.requestHandler("post", '/api/shop/banners-type', {
                        typeId: this.typeId,
                    })
                        .success(response => {
                            console.dir(response);
                            Core.notify("Изменения сохранены", {type: "success"});
                        })
                        .start();
                });
            },

            getImgUrl(id) {
                return `${process.env.MIX_APP_URL}/img/bannersType${id}.png`
            }
        },

        mounted() {
            this.fetchType();
        }
    }
</script>

<style scoped>
    .banner-type_img {
        width: 50%;
        display: inline-block;
    }
</style>