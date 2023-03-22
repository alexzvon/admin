<template>
    <div class="form-horizontal form-bordered">

        <div class="form-group" v-if="selectedAttributesIds.length !== innerAttributes.length">
            <label class="col-md-3 control-label">Добавить</label>
            <div class="col-md-9">
                <div class="input-group">
                    <tree-select-translatable
                      ref="attributesSelect"
                      :options="available"
                      v-on:update:selected="add"
                      placeholder="Выберите аттрибут"
                      :activeLanguageCode="activeLanguageCode"/>

                    <a class="input-group-addon btn btn-primary" @click="add">
                        <i class="fa fa-plus-circle"></i>
                    </a>
                </div>
            </div>
        </div>

        <div :class="`form-group${errors.has(`attributes.${attribute.id}`) ? ' has-error' : ''}`" v-for="attribute in selected" :key="attribute.id">
            <loading :loading="!! attribute.request" style="min-height: 36px;">
                <label class="col-md-3 control-label" :for="`attribute-${attribute.id}`">{{attribute.i18n[activeLanguageCode].title}}</label>
                <div class="col-md-9">
                    <div class="clearfix">
                        <div class="pull-left" style="width:calc(100% - 47px)">
                            <template v-if="!attribute.request">
                                <tree-select-translatable
                                  :id="`attribute-${attribute.id}`"
                                  :options="attribute.options"
                                  :selected.sync="attribute.selected"
                                  :multiple="true"
                                  placeholder="Выберите значения"
                                  :activeLanguageCode="activeLanguageCode"
                                  :params="{tags: true, closeOnSelect: false}"
                                  v-on:create:options="createOption($event, attribute)"
                                  v-on:update:selected="selectOptions($event, attribute)"
                                />
                            </template>

                            <template v-else>
                                <input type="text" class="form-control">
                            </template>
                        </div>

                        <a class="btn btn-danger pull-right" @click="remove(attribute.id)" style="margin:0;"><i class="fa fa-times"></i></a>
                    </div>

                    <span v-show="errors.has(`attributes.${attribute.id}`)" class="help-block">{{ errors.first(`attributes.${attribute.id}`) }}</span>
                </div>
            </loading>
        </div>

    </div>
</template>


<script>
    import { ErrorBag } from "vee-validate";


    import Core from "../../core";

    import TreeSelectTranslatable from "../TreeSelectTranslatable";
    import Loading from "../Loading";

    import DataHandler from "../../mixins/DataHandler";

    import OptionSelectModel from "../../resources/shop/OptionSelectModel";
    import HandleableException from "../../exceptions/HandleableException";

    export default {
//    module.exports = {
        name: "attributes-select",
        components: {
            TreeSelectTranslatable,
            Loading
        },
        props: {
            attributes: {
                type: Array,
                default: () => []
            },
            selectedAttributes: {
                type: Array,
                default: () => []
            },
            selectedOptions: {
                type: Array,
                default: () => []
            },
            languages: {
                type: Array,
                default: () => []
            },
            activeLanguageCode: {
                type: String
            },
            errors: {
                type: ErrorBag,
                default: () => new ErrorBag()
            },
        },
        mixins: [
            DataHandler
        ],
        watch: {
            selectedAttributes: "reset",
            selectedOptions: "reset",
            attributes: "initAttributes"
        },
        data() {
            return {
                selectedAttributesIds: [],
                innerAttributes: [],
            };
        },
        methods: {
            getAttributes() {
                return this.innerAttributes;
            },
            getAttributesSelectedOptions() {
                return this.innerAttributes.reduce((acc, attribute) => {
                    if (this.selectedAttributesIds.indexOf(attribute.id) === -1) {
                        return acc;
                    }
                    if (attribute.request) {
                        throw new HandleableException("Дождитесь окончания загрузки значений аттрибута.", "warning");
                    }
                    if (!(attribute.selected instanceof Array && attribute.selected.length > 0)) {
                        return acc;
                    }
                    acc[attribute.id] = attribute.selected.reduce((attributeAcc, selectedId) => {
                        let option = attribute.options.find(option => option.id == selectedId);

                        if (option.isNew) {
                            attributeAcc[option.id] = Object.keys(option.i18n).reduce((optionAcc, langCode) => {
                                optionAcc[langCode] = {
                                    value: option.i18n[langCode].title
                                };

                                return optionAcc;
                            }, {});
                        }
                        else {
                            attributeAcc[option.id] = option.i18n[this.activeLanguageCode].title;
                        }

                        return attributeAcc;
                    }, {});

                    return acc;
                }, {});
            },
            initAttributes() {
                this.innerAttributes = [
                    ...this.attributes
                ];
                this.selectedAttributes.forEach(id => {
                    this.toSelected(id);
                });
            },
            add() {
                this.toSelected(this.$refs.attributesSelect.getSelected());
            },
            toSelected(attributeId) {
                if (!attributeId) return;

                if (this.selectedAttributesIds.indexOf(attributeId) !== -1) return;

                let attribute = this.getAvailableAttributeById(attributeId);

                if (!attribute) return;

                this.fetchOptions(attribute);

                this.selectedAttributesIds = [
                    ...this.selectedAttributesIds,
                    attribute.id
                ];
            },
            getAvailableAttributeById(id) {
                return this.available.find(item => {
                    return item.id.toString() === id.toString();
                });
            },

            get(id) {
                return this.innerAttributes.find(item => item.id === id);
            },

            remove(id) {
                this.selectedAttributesIds = this.selectedAttributesIds.filter(selectedId => selectedId !== id);
            },

            fetchOptions(attribute) {
                if (attribute.options || attribute.request) return;

                let request = new Core.requestHandler("get", `/api/shop/attributes/${attribute.id}/options`);

                this.updateAttribute(attribute.id, {
                    request
                });

                request.any(() => {
                    if (["error", "crashed"].indexOf(request.status) !== -1) {
                        this.remove(attribute.id);
                    }

                    this.updateAttribute(attribute.id, {
                        request: false
                    });
                }).success(response => {
                    let selectedOptions = [];
                    let options = this.getSortedData(response.data.options).map(item => {
                        if (this.selectedOptions.indexOf(item.id) !== -1) {
                            selectedOptions.push(item.id);
                        }

                        return new OptionSelectModel(item, this.languages);
                    });

                    this.updateAttribute(attribute.id, {
                        options,
                        selected: selectedOptions
                    });
                }).start();
            },

            createOption(text, attribute) {
                let option = {
                    id: Core.uniqueId(),
                    isNew: true,
                    i18n: {}
                };

                this.languages.forEach(language => {
                    option.i18n[language.code] = {
                        title: text
                    };
                });

                this.updateAttribute(attribute.id, {
                    options: [
                        ...attribute.options,
                        option
                    ],
                    selected: [
                        ...attribute.selected,
                        option.id
                    ]
                });
            },

            selectOptions(selected = [], attribute) {
                this.updateAttribute(attribute.id, {
                    selected: selected,
                    options: attribute.options.filter(option => {
                        return !option.isNew || selected.indexOf(option.id) !== -1;
                    })
                });
            },

            updateAttribute(attributeId, data) {
                if (typeof attributeId === "object") {
                    attributeId = attributeId.id;
                }

                this.innerAttributes = this.innerAttributes.map(item => {
                    if (item.id === attributeId) {
                        return {
                            ...item,
                            ...data
                        };
                    }

                    return item;
                });
            },

            reset() {
                this.selectedAttributesIds = [];
                this.initAttributes();
            }
        },

        computed: {
            available() {
                return this.innerAttributes.filter(item => !this.selectedAttributesIds.find(id => id === item.id));
            },

            selected() {
                return this.innerAttributes.filter(item => this.selectedAttributesIds.find(id => id === item.id));
            }
        },

        created() {
            this.initAttributes();
        }
    };
</script>

