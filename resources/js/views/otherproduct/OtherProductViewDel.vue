<template>
    <div class="otherProductViewDel">
        <div class="otherProductViewDel__content">
            <div class="otherProductViewDel__values">
                {{ title }}
            </div>
        </div>
        <div class="otherProductViewDel__delete">
            <button class="btn" @click="deleteLink">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                </svg>
            </button>
        </div>
    </div>
</template>

<script>

export default {
    name: "OtherProductViewDel",
    props: {
        title: {
            type: String,
            default: '',
        },
        id: {
            required: true,
            type: Number,
            default: 0,
            validator: function (value) {
                return value > 0;
            }
        },
    },
    methods: {
        deleteLink() {
            this.$emit('delLinkProduct', this.id);
        },
    },
}
</script>

<style scoped  lang="scss">

.otherProductViewDel {
    width: 100%;
    position: relative;
    padding-bottom: 20px;
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    align-items: flex-start;
    justify-content: flex-start;
    z-index: 1;
    &.active {
        z-index: 3;
    }
    &__content {
        width: 100%;
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
    }
    &__values {
        width: 100%;
        position: relative;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        align-items: flex-start;
        justify-content: flex-start;
        min-height: 37px;
        padding: 5px 0 0 0;
        line-height: 36px;
        border: 1px solid #e4e4e4;
        position: relative;
        background: white;
        font-size: 14px;
        border-radius: 3px;
        z-index: 20;
        transition: all 0.12s linear;
        &__add {
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            height: 37px;
            right: 10px;
            top: 0;
            font-size: 20px;
            transition: all 0.12s linear;
            opacity: .4;
            cursor: pointer;
            &:hover {
                opacity: .9;
            }
            svg {
                fill: #394263;
            }
        }
    }
    &__delete {
        margin-left: 10px;
        button {
            height: 37px;
            width: 37px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ED8B83;
            svg {
                width: 20px;
                flex: none;
                fill: #fff;
            }
        }
    }
}

</style>