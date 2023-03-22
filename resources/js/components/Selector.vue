<template>
    <div v-on-clickaway="close" class="relative width-100" :class="{open: show}">
        <label v-if="label" class="form-label">{{ label }}</label>
        <div
            class="form-select"
            :class="{ 'form-select-borderless': borderless }"
            @click="toggle">

            <div v-if="multiple">
                <span
                    class="multiple_item"
                    v-if="hasSelected"
                    v-for="(selectedItem, index) in selected">
                    {{ getOptionName(selectedItem) }}

                    <i class="delete_icon" v-html="deleteIcon" @click.prevent="removeFromMultiple(index)"></i>

                </span>
                <span class="form-select__placeholder" v-if="!hasSelected">{{ placeholder }}</span>
            </div>

            <div v-if="!multiple">
                <span v-if="hasSelected">{{ getOptionName(selected) }}</span>
                <span class="form-select__placeholder" v-if="!hasSelected">{{ placeholder }}</span>
            </div>

            <span class="dropdown-caret">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="36" viewBox="0 0 24 24">
                    <path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z"/>
                </svg>
            </span>

        </div>
        <ul class="form-select-dropdown">

            <div v-if="!nosearch" class="form-select-dropdown__search">
                <input
                    type="text"
                    v-model="searchQuery"
                    placeholder="Поиск..">
            </div>

            <li
                v-if="!noclear"
                @click="clear"
                class="form-select-dropdown__item">
                Очистить
            </li>

            <li
                v-for="option in optionsFiltered"
                @click="select(option)"
                class="form-select-dropdown__item">
                {{ getOptionName(option) }}
            </li>

        </ul>
    </div>
</template>

<script>
    import _ from "lodash";

    export default {
//    module.exports = {
        name: "Selector",
        props: [
            "label",
            "value",
            "placeholder",
            "options",
            "danger",
            "borderless",
            "multiple",
            "except",
            "noclear",
            "nosearch"
        ],
        data() {
            return {
                show: false,
                selected: false,
                searchQuery: "",
                deleteIcon: `<svg width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 0.805714L7.19429 0L4 3.19429L0.805714 0L0 0.805714L3.19429 4L0 7.19429L0.805714 8L4 4.80571L7.19429 8L8 7.19429L4.80571 4L8 0.805714Z" fill="black"/></svg>`,
            };
        },
        computed: {
            exceptedIds() {
                let exceptedIds = [];
                _.each(this.except, item => {
                    _.isArray(item)
                        ? _.each(item, subitem => {
                            exceptedIds.push(subitem);
                        })
                        : exceptedIds.push(item);
                });
                return exceptedIds;
            },
            optionsFiltered() {
                return _.filter(this.options, option => {
                    let passFilter = true;

                    if (option.name && this.searchQuery) {
                        passFilter = option.name.toLowerCase().indexOf(this.searchQuery.toLowerCase()) != -1;
                    }

                    if (passFilter && this.except) {
                        passFilter = this.exceptedIds.indexOf(option.id) < 0;
                    }

                    if (passFilter && this.selected) {
                        passFilter = _.findIndex(this.selected, {id: option.id}) == -1;
                    }

                    return passFilter;
                });
            },
            hasSelected() {
                if (!this.selected) return false;
                if (typeof this.selected === 'object') {
                    return this.selected.length > 0;
                }
            }
        },
        methods: {
            toggle() {
                if (this.show) {
                    this.show = false;
                } else {
                    this.open();
                    this.focusInput();
                }
            },
            open() {
                this.show = true;
                setTimeout(this.setScrollToTop, 100);
            },
            setScrollToTop() {
                let node = this.$el.querySelector('.form-select-dropdown');
                node.scrollTop = 0;
            },
            close() {
                this.show = false;
            },
            clear() {
                this.show = false;
                this.selected = false;
                this.$emit("clear");
            },
            select(option) {
                this.show = false;
                this.multiple ? this.selected.push(option) : this.selected = option;
                this.$emit("selected", this.selected);
                this.searchQuery = "";
            },
            getOptionById(id) {
                return this.options.find(option => {
                    return option.id === id;
                });
            },
            getOptionName(option) {
                if (typeof option.name === "string") {
                    return option.name;
                }
                if (typeof option.title === "string") {
                    return option.title;
                }
            },
            removeFromMultiple(index) {
                this.selected.splice(index, 1);
                this.show = false;
                this.$emit("selected", this.selected);
            },
            focusInput() {
                if (this.$el.querySelector("input") !== null) {
                    this.$el.querySelector("input").focus();
                }
            }
        },
        beforeMount() {
            if (this.value === 0 || this.value) {
                if (this.multiple) {
                    this.selected = [];
                    this.value.forEach(item => {
                        this.selected.push(this.getOptionById(item));
                    });
                } else {
                    this.selected = typeof this.value == "object"
                        ? this.getOptionById(this.value.id)
                        : this.getOptionById(this.value);
                }
            }
        }
    };
</script>

<style lang="scss" scoped>
    $formElementBorderColor: #e4e4e4;
    $color-text-muted: #999;
    $text-color: #2b2b2c;
    $dropdown-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    $formElementBgActive: #f9fafc;
    $brand-danger: #bf5329;

    .relative {
        position: relative;
    }

    .form-label {
        font-size: 13px;
        color: rgba(59, 66, 101, 0.77);
        margin-bottom: 2px;
        font-weight: 400;
    }

    .form-select {
        width: 100%;
        display: block;
        min-height: 37px;
        padding: 7px 8px 0;
        line-height: 29px;
        border: 1px solid #e4e4e4;
        position: relative;
        background: white;
        font-size: 14px;
        border-radius: 3px;
        z-index: 20;
        transition: all 0.12s linear;
        &__placeholder{
            display: block;
            margin-top: -3px;
        }
        .multiple_item {
            font-size: 13px;
            background: #fafafa;
            display: inline-block;
            vertical-align: top;
            line-height: 20px;
            padding: 2px 5px 2px 7px;
            border-radius: 3px;
            margin: 0 7px 7px 0;
            .delete_icon {
                margin: 1px 0 0 -1px;
                font-size: 13px;
                opacity: .5;
                &:hover {
                    color: #ff0000;
                    opacity: 1;
                }
            }
        }

        .dropdown-caret {
            height: 36px;
            position: absolute;
            right: 10px;
            top: 0;
            font-size: 20px;
            color: #394263;
            transition: all 0.12s linear;
        }
        &:hover {
            border: 1px solid $formElementBorderColor;
            cursor: pointer;
        }
    }

    .form-select-borderless {
        border-color: transparent;
        .dropdown-caret {
            color: transparent;
        }
        &:hover {
            .dropdown-caret {
                color: $text-color;
            }
        }
    }

    .form-select-dropdown {
        position: absolute;
        top: 100%;
        background: white;
        border: 0;
        width: 100%;
        overflow: scroll;
        z-index: 25;
        max-height: 0;
        transition: all 0.12s ease-in;
        padding: 0;
        box-shadow: $dropdown-shadow;
        &__search {
            input {
                width: 100%;
                border: 0;
                border-bottom: 1px solid #f4f4f4;
                padding: 5px 10px;
                &, &:active, &:focus {
                    outline: none;
                    box-shadow: none;
                }
            }
        }
        &__item {
            padding: 0 10px;
            line-height: 30px;
            transition: all 0.06s linear;
            font-size: 14px;
            &:hover {
                cursor: pointer;
                background: $formElementBgActive;
                color: $text-color;
            }
        }
    }

    .open {
        .form-select {
            border: 1px solid $formElementBorderColor;
            background: $formElementBgActive;
            .dropdown-caret {
                color: $text-color;
            }
        }
        .form-select-dropdown {
            max-height: 40vh;
            list-style: none;
        }
    }

    .form-error {
        .form-label {
            color: $brand-danger;
        }
        .form-input {
            border: 1px solid $brand-danger;
        }
    }

</style>
