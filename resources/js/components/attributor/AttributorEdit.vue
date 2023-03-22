<template>
    <div class="attributorEdit" :class="{active:isActive}">
        <div class="attributorEdit__label">
            {{ attribute.i18n[0].title }}
        </div>
        <div class="attributorEdit__content">
            <div
                @click.self="open()"
                v-on-clickaway="close"
                class="attributorEdit__values">

                <AttributorEditOption
                  v-for="(selectedOption, index) in selectedOptions"
                  :option="selectedOption"
                  :editable="optionsEditable"
                  :key="'option_'+selectedOption.id"
                  :link="selectedLink(selectedOption.id)"
                >
                </AttributorEditOption>
                <div class="attributorEdit__search">
                    <input
                        v-if="activeState.id!==3"
                        v-show="expanded"
                        type="text"
                        placeholder="Искать.."
                        v-model="searchQuery">
                </div>

        <span
          v-if="activeState.id!==3"
          class="attributorEdit__values__add"
          @click="open()"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z" /></svg>
        </span>

        <div v-if="expanded" class="attributorEdit__expanded">
          <div v-if="activeState.id!==3" class="attributorEdit__options">
              <div
                  v-for="option in options"
                  @click="addOption(option.id)"
                  class="attributorEdit__option"
                  :key="'option_'+option.id">
                  {{ option.value }}
              </div>
          </div>
        </div>
      </div>

      <div
        v-if="!global"
        class="attributorEdit__state"
        :class="{active:statesExpanded}"
      >

        <button
          class="btn active"
          @click="statesExpanded=!statesExpanded"
          v-html="activeState.svg"
        />

        <div v-if="statesExpanded && id !== $data.$attributesEnum.DEFAULT_KIT && id !== $data.$attributesEnum.SAME_COLLECTION_KIT"
             class="attributorEdit__state__expanded">

                <template v-for="state in states">
                <button
                  v-if="(state.id !== activeState.id)"
                  class="btn"
                  @click="updateState(state)"
                  v-html="state.svg"
                />
                </template>
        </div>

      </div>

      <div class="attributorEdit__delete">
        <button class="btn" @click="deleteAttribute">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
          </svg>
        </button>
      </div>

    </div>

    <b-modal
      id="checkSyncModal"
      ref="checkSyncModal"
      title="Добавление товара в комплект по умолчанию"
      title-tag="h3"
      centered
      ok-title="Да"
      cancel-title="Нет"
      hide-header-close
      @ok="startAddition(true)"
      @cancel="startAddition(false)"
    >
      Хотите синхронизировать это тот товар?
    </b-modal>

  </div>
</template>

<script>
import _ from 'lodash';
import AttributesEnum from '../../mixins/AttributesIds';
import Base from '../../mixins/Base';

import Select2 from 'v-select2-component';

import { getAddOptionsAttributeProduct, addOptionToAttribute } from '../../api/attribute';
import Core from "../../core";

export default {
    name: 'AttributorEdit',
    components: {
        Select2,
        AttributorEditOption: require('./AttributorEditOption').default,
    },
    props: {
        id: {
            type: Number,
            default: 0,
        },
        global: {
            type: Boolean,
            default: false,
        },
        optionsEditable: {
            type: Boolean,
            default: false,
        },
        attr: {
            type: Object,
            default: ()=>{{}},
        },
        lastAttributeOptionId: {
            type: Number,
            default: 0,
        },
    },
    mixins: [
        Base,
    ],
    data() {
        return {
            expanded: false,
            searchQuery: '',
            statesExpanded: false,
            issetExpandedOptions: false,
            states: [
            {
              id: 2,
              name: 'Выпадающий список',
              svg: `<svg viewBox="0 0 14 8" xmlns="http://www.w3.org/2000/svg"><path d="M0 4.8H1.55556V3.2H0V4.8ZM0 8H1.55556V6.4H0V8ZM0 1.6H1.55556V0H0V1.6ZM3.11111 4.8H14V3.2H3.11111V4.8ZM3.11111 8H14V6.4H3.11111V8ZM3.11111 0V1.6H14V0H3.11111Z"></path></svg>`,
            },
            {
              id: 1,
              name: 'Чекбокс',
              svg: `<svg viewBox="0 0 14 11" xmlns="http://www.w3.org/2000/svg"><path d="M4.44912 8.6786L1.13019 5.25802L0 6.41462L4.44912 11L14 1.1566L12.8778 0L4.44912 8.6786Z"></path></svg>`,
            },
            {
              id: 3,
              name: 'Комплект по умолчанию',
              svg: `<svg viewBox="0 0 14 12" xmlns="http://www.w3.org/2000/svg"><path d="M10.3155 4.41307L7.52818 0.265543C7.40727 0.0885143 7.20364 0 7 0C6.79636 0 6.59273 0.0885143 6.47182 0.271865L3.68455 4.41307H0.636364C0.286364 4.41307 0 4.69758 0 5.04531C0 5.10221 0.00636361 5.15911 0.0254545 5.21602L1.64182 11.0769C1.78818 11.608 2.27818 12 2.86364 12H11.1364C11.7218 12 12.2118 11.608 12.3645 11.0769L13.9809 5.21602L14 5.04531C14 4.69758 13.7136 4.41307 13.3636 4.41307H10.3155ZM5.09091 4.41307L7 1.63119L8.90909 4.41307H5.09091ZM7 9.47102C6.3 9.47102 5.72727 8.902 5.72727 8.20653C5.72727 7.51106 6.3 6.94204 7 6.94204C7.7 6.94204 8.27273 7.51106 8.27273 8.20653C8.27273 8.902 7.7 9.47102 7 9.47102Z" fill="black"></path></svg>`,
            },
            ],
            productInQueue: {},
            $attributesEnum: AttributesEnum,
            addOptions: [],
        };
    },
    computed: {
        attribute: {
          get() {
              return this.attr.attr;
          },
          set(attribute, old) {},
        },

        options() {
            return this.addOptions.filter((el)=>(el.value.toLowerCase().indexOf(this.searchQuery.toLowerCase()) > -1));
        },
        attributeRelation: {
            get() {
                return this.link;
            },
            set(attributeRelation, old) {}
        },

        // optionsAvailableToSelect() {
        //   return _.filter(this.options, option => {
        //     let pass = !this.isOptionSelected(option);
        //
        //     if (pass && this.searchQuery.length > 1) {
        //       pass = option.i18n[0].value.toLowerCase().indexOf(this.searchQuery.toLowerCase()) > -1;
        //     }
        //     return pass;
        //   });
        // },

        selectedOptions() {
            return this.attr.opt;
        },
        isActive() {
          return this.expanded || this.statesExpanded || this.issetExpandedOptions;
        },


        activeState() {
            let activeStateId = 2;
            if (this.attr.link.selectable) {
                activeStateId = this.attr.link.is_products ? 3 : 2;
                if (this.attr.link.attribute_id === AttributesEnum.DEFAULT_KIT) {
                    activeStateId = 3;
                } else if (this.attr.link.attribute_id === AttributesEnum.SAME_COLLECTION_KIT) {
                    activeStateId = 3;
                }
            } else {
                activeStateId = 1;
            }
            return _.find(this.states, { id: activeStateId });
        },


    isSelectable() {
      return this.activeStateId !== 1;
    },
    canBeOptionAdded() {
      return this.searchQuery.length > 0 && this.id !== AttributesEnum.COLOR;
    },
  },
    methods: {
        // readCookie(name) {
        //     var nameEQ = name + "=";
        //     var ca = document.cookie.split( ';');
        //     for( var i=0;i < ca.length;i++)
        //     {
        //         var c = ca[i];
        //         while ( c.charAt( 0) === ' ') c = c.substring( 1,c.length);
        //         if ( c.indexOf( nameEQ) === 0) return c.substring( nameEQ.length,c.length);
        //     }
        //     return null;
        // },


        async getAddOptions() {
            const loading = this.$loading({
                lock: true,
                text: 'Loading',
                spinner: 'el-icon-loading',
                background: 'rgba(0, 0, 0, 0.1)'
            });

            if (this.addOptions.length === 0) {
                let payload = {
                    relation: this.attr.link.relation,
                    relation_id: this.attr.link.relation_id,
                    attribute_id: this.attr.link.attribute_id,
                };

                try {
                    const { data } = await getAddOptionsAttributeProduct(payload);
                    this.addOptions = data.addOptions;
                } catch (error) {
                    console.log(error);
                }
            }

            loading.close();
        },

        async addOption(option_id) {
            const loading = this.$loading({
                lock: true,
                text: 'Loading',
                spinner: 'el-icon-loading',
                background: 'rgba(0, 0, 0, 0.1)'
            });

            let payload = {
                attribute_relation_id: this.attr.link.id,
                attribute_id: this.attr.link.attribute_id,
                option_id: option_id,
            };

            try {
                const { data } = await addOptionToAttribute(payload);
                this.clearSearchQuery();
                this.$emit('reloadAttributeProduct');
            } catch (error) {
                console.log(error);
            }

            loading.close();

            this.addOptions = [];
            this.setFocusOnInput();
        },

        selectedLink(option_id) {
            for (var i=0; i < this.attr.link.linked_options.length; i++) {
                if (this.attr.link.linked_options[i].option_id === option_id) {
                    return this.attr.link.linked_options[i];
                }
            }
        },

        close() {
          this.expanded = false;
          this.issetExpandedOptions = false;
        },

        open() {
          if (!this.expanded) {
            this.expanded = true;
            setTimeout(this.setFocusOnInput, 500);
          }
        },

        toggle() {
          !this.expanded ? this.open() : this.close();
        },

        setFocusOnInput() {
            this.getAddOptions();
            this.$el.querySelector('input').focus();
        },

        isOptionSelected(option) {
          return _.findIndex(this.selectedOptions, { id: option.id }) > -1;
        },

        optionStateChanged(val) {
          this.issetExpandedOptions = val;
        },
        startAddition(value) {
          this.syncProduct = value;
          this.addProductOption(this.productInQueue);
        },

        onAddProductOption(product) {
          this.productInQueue = product;
          if (this.attr.link.attribute_id !== AttributesEnum.SAME_COLLECTION_KIT) {
            this.$refs.checkSyncModal.show();
          } else {
            // very important to send 'false' in this place.
            // Otherwise, process of synchronization of options will start
            // for wrong attribute
            this.startAddition(true);
          }
        },

        addProductOption(product) {
          this.$store.dispatch('attributes/createOption', {
            title: product.i18n[0].title,
            attribute_id: this.id,
          }).then(option => {
            this.$store.dispatch('attributes/createAttributeOptionRelation', {
              attribute_relation_id: this.attributeRelation.id,
              option_id: option.id,
              option_product_id: product.id,
              fix_price: true,
              sync: this.syncProduct,
            });
            this.clearSearchQuery();
          }).catch(() => {
            this.$core.notify('Не удалось создать товарное значение опции', { type: 'danger' });
          });
        },

        createOption() {
          this.$store.dispatch('attributes/createOption', {
            title: this.searchQuery,
            attribute_id: this.id,
            selectable: this.isSelectable,
          }).then(option => {
            this.$emit('optionCreated', option);
            this.clearSearchQuery();
          });
        },
        deleteAttribute() {
          this.$emit('delAttribute', this.attr.attrRelation);
        },

        updateState(state) {
            if (state.id === 1) {
                this.attr.link.selectable = false;
                this.attr.link.is_products = false;
            } else if (state.id === 2) {
                this.attr.link.selectable = true;
                this.attr.link.is_products = false;
            } else if (state.id === 3) {
                this.attr.link.selectable = true;
                this.attr.link.is_products = true;
            }
            this.statesExpanded = false;

            let payload = {
                id: this.attr.link.id,
                selectable: this.attr.link.selectable,
                is_products: this.attr.link.is_products,
            };

            this.$emit('updateState', payload);
        },
        clearSearchQuery() {
          this.searchQuery = '';
          this.setFocusOnInput();
        },
        deleteThisOption: function(index) {
          this.selectedOptions.splice(index, 1);
        },
    },
};
</script>

<style lang="scss">
    .attributorEdit {
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
        &__label {
            flex: none;
            width: 150px;
            padding-top: 5px;
            line-height: 130%;
            margin-right: 30px;
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
        &__search {
            height: 26px;
            line-height: 130%;
            padding-left: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            input {
                line-height: 20px;
                width: 100%;
                border: 0;
                padding: 0;
                &, &:active, &:focus {
                    outline: none;
                    box-shadow: none;
                }
            }
        }
        &__expanded {
            position: absolute;
            z-index: 30;
            top: 100%;
            background: white;
            border: 0;
            width: 100%;
            max-height: 400px;
            //overflow-y: hidden;
            overflow-x: hidden;
            transition: all 0.12s ease-in;
            padding: 0;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
        }

        &__options {
            display: block;
            width: 100%;
        }
        &__option {
            display: block;
            width: 100%;
            padding: 0 10px;
            line-height: 30px;
            transition: all 0.06s linear;
            font-size: 14px;
            &:hover {
                cursor: pointer;
                background: #f9fafc;
                color: #2b2b2c;
            }
            &-add {
                font-weight: 500;
                font-size: 14px;
            }
        }

        &__state {
            margin-left: 10px;
            width: 37px;
            height: 37px;
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 10;
            &.active {
                z-index: 20;
            }
            svg{
                width: 17px;
                height: auto;
                fill: #394263 !important;
                flex: none;
            }
            button.btn {
                height: 37px;
                width: 37px;
                display: flex;
                flex:none;
                align-items: center;
                justify-content: center;
                border: 1px solid #eaedf1;
                background: #fff;
                &.active {
                    background: #eaedf1;
                }
            }
            &__expanded {
                background: #fff;
                position: absolute;
                top: 100%;
                left: 0;
                z-index: 20;
                padding: 0;
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
                button {
                    margin: -2px 0 0;
                    border-radius: 0;
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
