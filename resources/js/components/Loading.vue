<template>
    <div class="loading-wrap" v-bind:class="{'loaded': !loading}">
        <transition name="fade">
            <div v-if="loading" class="loading-wrap__spinner">
                <i class="fa fa-spinner fa-4x fa-spin"></i>
            </div>
        </transition>
        
        <transition name="fade">
            <div class="loading-wrap__content">
                <slot></slot>
            </div>
        </transition>
    </div>
</template>


<script>
    export default {
        name: "Loading",
        props: [
            "loading"
        ],
    };
</script>



<style lang="scss">
    .loading-wrap {
        position: relative;
        min-height: 200px;
        &__spinner {
            position: absolute;
            top: 50px;
            left: 0;
            right: 0;
            width: 52px;
            height: 52px;
            margin: auto;
            z-index: 991;
        }
        &__content{}
        &:not(.loaded) {
            pointer-events: none !important;
            &::before {
                content: '';
                position: absolute;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                background-color: rgba(255, 255, 255, .5);
                z-index: 99;
            }
        }
    }
</style>