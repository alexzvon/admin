<template>
<!--    <div-->
<!--        :class="{active:expanded}"-->
<!--        class="attributorEditValue"-->
<!--        @click.self="toggle()"-->
<!--        v-on-clickaway="close">-->

    <div
        :class="{active:expanded}"
        class="attributorEditValue"
        >

        <div
            @click="deleteOption"
            class="attributorEditValue__delete"
            v-html="deleteIcon">
        </div>

        {{ title }}

        <div v-if="expanded && editable" class="attributorEditValue__expanded">
            <div class="card">
                <div class="card__title font-s15 pb-0">{{ title }}</div>
                <div class="card__content">

                    <template v-if="attributeOptionRelation">
                        <label class="text_editor__label">Как значение влияет на цену?</label>
                        <div class="btn-group btn-block">
                            <button
                                class="btn"
                                @click="setFixedPrice(true)"
                                :class="[attributeOptionRelation.fix_price ? 'btn-success' : 'btn-default']">
                                Фикс. наценка
                            </button>
                            <button
                                class="btn"
                                @click="setFixedPrice(false)"
                                :class="[!attributeOptionRelation.fix_price ? 'btn-success' : 'btn-default']">
                                Процент
                            </button>
                        </div>
                    </template>

                    <loading-spinner v-if="!prices"></loading-spinner>

                    <div v-if="prices" class="row pl-10 pr-10">
                        <div class="mt-5 col-sm-6 p-5" v-for="price in prices">
                            <input-editor
                                :label="$store.getters['priceTypes/getById'](price.price_type_id).title"
                                addclass=""
                                :payload="price"
                                :text="(price.value/100)"
                                @save="savePrice"
                                :placeholder="false">
                            </input-editor>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</template>

<script>
    import _ from "lodash";

    export default {
   // module.exports = {
        name: "AttributorEditOption",
        props: {
            option: {
                type: Object,
                default: ()=>({}),
            },
            link: {
                type: Object,
                default: ()=>({}),
            },
            editable: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                expanded: false,
                prices: false,
                deleteIcon: `<svg width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 0.805714L7.19429 0L4 3.19429L0.805714 0L0 0.805714L3.19429 4L0 7.19429L0.805714 8L4 4.80571L7.19429 8L8 7.19429L4.80571 4L8 0.805714Z" fill="black"/></svg>`,
            };
        },
        computed: {
            attributeOptionRelation: {
                get() {
                    return {};
                    // return _.find(this.parentAttributeRelationOptions, {option_id:this.option.id});
                },
                set(val) {
                    // this.$store.dispatch('attributes/updateAttributeOptionRelation', this.attributeOptionRelation);
                },
            },
            parentAttributeRelationOptions() {

                console.log(this.option.attribute_id);

                return this.$store.getters['attributes/attributeRelationOptionsByAttributeId'](this.option.attribute_id);


            },
            title() {
                return this.option.i18n[0].value;
            },
            priceTypes() {
                return this.$store.state.priceTypes.all;
            },
        },
        methods: {
            deleteOption() {
                this.$parent.$emit('delOption', this.link.id);
            },
            toggle() {
                if (!this.expanded && this.editable) {
                    this.open();
                } else {
                    this.close();
                }
            },
            open() {
                if (!this.prices) {
                    this.getPrices();
                }
                this.expanded = true;
                this.$emit('changed', true);
            },
            close() {
                if (this.expanded) { this.expanded = false; }
            },
            removeOption() {
                this.close();
                if (this.attributeOptionRelation) {
                    this.$store.dispatch('attributes/deleteAttributeOptionRelation', this.attributeOptionRelation);
                } else {
                    console.log('Error during we try to delete option: ' + this.option.id + ' of attribute: ' + this.option.attribute_id);
                }

            },
            setFixedPrice(val) {
                this.attributeOptionRelation.fix_price = val;
                this.attributeOptionRelation = this.attributeOptionRelation;
            },
            getPrices() {
                if (this.attributeOptionRelation) {
                    this.$store.dispatch('attributes/getAttributeOptionRelationPrices', this.attributeOptionRelation).then(prices => {
                        this.prices = prices;
                    })
                }
            },
            setPrice(price) {
                this.prices[_.findIndex(this.prices, { price_type_id: price.price_type_id })] = price;
            },
            savePrice(data) {
                let price = data.payload;
                price.value = _.isEmpty(data.value) ? 0 : parseInt(data.value);
                this.setPrice(price);

                (typeof price.id === 'undefined') ? this.createPrice(price) : this.updatePrice(price);
            },
            createPrice(price) {
                this.$store.dispatch('attributes/createRelationElementPrice', price).then(createdPrice => {
                    this.setPrice(createdPrice);
                })
            },
            updatePrice(price) {
                this.$store.dispatch('attributes/updateRelationElementPrice', price);
            },
        },

        mounted() {
            if (!this.attributeOptionRelation) {
                this.$emit('delete-option-without-relation');
            }
        },
    };
</script>

<style lang="scss">
    .attributorEditValue{
        font-size: 14px;
        background: #fafafa;
        display: inline-flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        position: relative;
        vertical-align: top;
        line-height: 20px;
        padding: 2px 5px 2px 5px;
        border-radius: 3px;
        margin: 0 0 5px 5px;
        border: 1px solid #e4e4e4;
        cursor: pointer;
        z-index: 1;
        &__delete {
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: .5;
            margin-right: 3px;
            svg{
                height: 8px;
                width: 8px;
                fill: #394263;
            }
            &:hover {
                color: #ff0000;
                opacity: 1;
            }
        }
        &.active{
            background: #444;
            border: 1px solid #444;
            color: #fff;
            z-index: 3;
            svg{
                fill: #fff;
                opacity: .8;
            }
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
        }
    }
</style>
