<template>
    <div :key="keyComponent">
        <div class="block full">

            <div class="block-title clearfix" v-if="type === 'edit'">

                <h1>Редактирование товара #{{ this.id }}</h1>
                <span v-if="this.isSaleActive" class="label label-danger"> Акция </span>

                <div class="block-title-control">
                    <a class="btn btn-sm btn-default btn-alt" @click="redirectToTable">
                        <i class="fa fa-arrow-left"></i>
                    </a>

                    <span class="btn-separator-xs"></span>

                    <a
                        v-if="userCan('shop.products.edit')"
                        class="btn btn-sm btn-primary active"
                        @click="savePageProduct"
                        v-bind:disabled="saveButtonDisabled">
                        <i class="fa fa-floppy-o"></i> Сохранить
                    </a>

                    <a
                        v-if="userCan('shop.products.clone')"
                        class="btn btn-sm btn-prime btn-primary active"
                        @click="clone">
                        <i class="fa fa-clone"></i> Создать копию
                    </a>

                    <a
                        v-if="userCan('shop.products.delete')"
                        class="btn btn-sm btn-danger active"
                        @click="remove">
                        Удалить
                    </a>
                </div>
            </div>

            <div class="row" v-if="product">

                <div class="col-lg-6">

                    <!-- Переводы -->

                    <div :class="`block${langSwitchHovered ? ' block-illuminated' : ''}`">
                        <div class="block-title">
                            <h2>
                                <i class="fa fa-globe"></i> <strong>Языковая</strong> информация
                            </h2>
                        </div>

                        <template v-for="language in languages">
                            <div
                                :class="`form-horizontal form-bordered${activeLanguageCode === language.code ? '' : ' in-space'}`"
                                :key="language.code">

                                <div
                                    :class="`form-group${formErrors.has(`i18n.${language.code}.title`) ? ' has-error' : ''}`">
                                    <label class="col-md-3 control-label" :for="`title-${language.code}`">Название <span
                                        class="text-danger">*</span></label>

                                    <div class="col-md-9">
                                        <input
                                            type="text" class="form-control" :id="`title-${language.code}`"
                                            v-model="product.i18n[language.code].title"
                                            :name="`i18n.${language.code}.title`" v-validate="'required|max:255'">

                                        <span v-show="formErrors.has(`i18n.${language.code}.title`)" class="help-block">{{
                                            formErrors.first(`i18n.${language.code}.title`) }}</span>
                                    </div>
                                </div>

                                <div :class="`form-group${formErrors.has('slug') ? ' has-error' : ''}`">
                                    <label class="col-md-3 control-label" for="slug">
                                        Slug <span class="text-danger">*</span>
                                    </label>

                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                id="slug"
                                                class="form-control"
                                                v-model="product.slug"
                                                name="slug"
                                                v-validate="'required|min:3|max:255|slug_exist'"
                                                required>

                                            <a class="btn input-group-addon" @click="slugAutocomplete">
                                                <i class="fa fa-refresh"></i> Автозаполнение
                                            </a>
                                        </div>

                                        <span v-show="formErrors.has('slug')" class="help-block">
                                        {{ formErrors.first('slug') }}
                                    </span>
                                    </div>
                                </div>

                                <div
                                    :class="`form-group${formErrors.has(`i18n.${language.code}.description`) ? ' has-error' : ''}`">
                                    <label class="col-md-3 control-label"
                                           :for="`description-${language.code}`">Описание</label>

                                    <div class="col-md-9">
                                        <ckeditor
                                            :id="`description-${language.code}`"
                                            :content.sync="product.i18n[language.code].description"
                                            :name="`i18n.${language.code}.description`"/>

                                        <span v-show="formErrors.has(`i18n.${language.code}.description`)"
                                              class="help-block">{{
                                            formErrors.first(`i18n.${language.code}.description`) }}</span>
                                    </div>
                                </div>

                                <div
                                    :class="`form-group${formErrors.has(`i18n.${language.code}.meta_title`) ? ' has-error' : ''}`">
                                    <label class="col-md-3 control-label"
                                           :for="`title-${language.code}`">Мета-заголовок</label>

                                    <div class="col-md-9">
                                        <input
                                            type="text" class="form-control" :id="`title-${language.code}`"
                                            v-model="product.i18n[language.code].meta_title"
                                            :name="`i18n.${language.code}.meta_title`" v-validate="'max:255'">

                                        <span v-show="formErrors.has(`i18n.${language.code}.meta_title`)"
                                              class="help-block">{{
                                            formErrors.first(`i18n.${language.code}.meta_title`) }}</span>
                                    </div>
                                </div>

                                <div
                                    :class="`form-group${formErrors.has(`i18n.${language.code}.meta_description`) ? ' has-error' : ''}`">
                                    <label class="col-md-3 control-label"
                                           :for="`title-${language.code}`">Мета-описание</label>

                                    <div class="col-md-9">
                                        <textarea
                                            class="form-control" :id="`meta-description-${language.code}`"
                                            v-model="product.i18n[language.code].meta_description"
                                            :name="`i18n.${language.code}.meta_description`"
                                            v-validate="'max:65000'"></textarea>

                                        <span v-show="formErrors.has(`i18n.${language.code}.meta_description`)"
                                              class="help-block">{{
                                            formErrors.first(`i18n.${language.code}.meta_description`) }}</span>
                                    </div>
                                </div>
                            </div>
                        </template>

                    </div>

                    <!-- Основная информация -->

                    <div class="block">
                        <div class="block-title">
                            <h2>
                                <i class="fa fa-pencil"></i> <strong>Основная</strong> информация
                            </h2>
                        </div>

                        <div class="form-horizontal form-bordered">

                            <div :class="`form-group${formErrors.has('suppliers') ? ' has-error' : ''}`">

                                <label class="col-md-3 control-label">
                                    Поставщики <span class="text-danger">*</span>
                                </label>

                                <div class="col-md-8">
                                    <tree-select
                                        name="suppliers"
                                        v-validate="'required'"
                                        v-model="product.suppliers"
                                        :options="suppliersToSelect"
                                        :selected.sync="product.suppliers"
                                        :multiple="true"
                                        :params="{closeOnSelect: false}"
                                        placeholder="Выберите поставщиков"/>

                                    <span v-show="formErrors.has('suppliers')" class="help-block">
                                        {{ formErrors.first("suppliers") }}
                                    </span>
                                </div>
                            </div>

                            <div :class="`form-group${!!formPageErrors['id_from_supplier'] || formErrors.has('id_from_supplier') ? ' has-error' : ''}`">
                                <label class="col-md-3 control-label">
                                    ID у поставщика <span class="text-danger">*</span>
                                </label>

                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control"
                                            @focus="function(){ formPageErrors['id_from_supplier'] = false; }"
                                            id="id_from_supplier"
                                            v-model="product.id_from_supplier"
                                            name="id_from_supplier"
                                            v-validate="'required|max:200'">
                                    </div>
                                </div>
                            </div>
                            <div :class="`form-group${!!formPageErrors['gtin'] || formErrors.has('gtin') ? ' has-error' : ''}`">
                                <label class="col-md-3 control-label">
                                    GTIN <span class="text-danger">*</span>
                                </label>

                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control"
                                            @focus="function(){ formPageErrors['gtin'] = false; }"
                                            id="gtin"
                                            v-model="product.gtin"
                                            name="gtin"
                                            v-validate="'required|max:32'">
                                    </div>
                                </div>
                            </div>

                            <div :class="`form-group${formErrors.has('categories') ? ' has-error' : ''}`">
                                <label class="col-md-3 control-label">
                                    Категория
                                </label>

                                <div class="col-md-8">
                                    <tree-select-translatable
                                        :activeLanguageCode="activeLanguageCode"
                                        :defaultLanguageCode="defaultLanguageCode"
                                        :options="categoriesTree"
                                        :selected.sync="product.categories"
                                        :multiple="true"
                                        :params="{closeOnSelect: false}"
                                        :disable-parents="true"
                                        placeholder="Выберите категорию"/>

                                    <span v-show="formErrors.has('categories')" class="help-block">
                                        {{ formErrors.first("categories") }}
                                    </span>
                                </div>
                            </div>

                            <div :class="`form-group${formErrors.has('rooms') ? ' has-error' : ''}`">
                                <label class="col-md-3 control-label">
                                    Комнаты
                                </label>
                                <div class="col-md-8">
                                    <tree-select
                                        :options="roomsToSelect"
                                        :selected.sync="product.rooms"
                                        :multiple="true"
                                        placeholder="Выберите комнату"/>
                                    <span v-show="formErrors.has('rooms')" class="help-block">
                                        {{ formErrors.first("rooms") }}
                                    </span>
                                </div>
                            </div>

                            <div :class="`form-group${formErrors.has('styles') ? ' has-error' : ''}`">
                                <label class="col-md-3 control-label">
                                    Стили
                                </label>
                                <div class="col-md-8">
                                    <tree-select
                                        :options="stylesToSelect"
                                        :selected.sync="product.styles"
                                        :multiple="true"
                                        placeholder="Выберите стиль"/>
                                    <span v-show="formErrors.has('styles')" class="help-block">
                                        {{ formErrors.first("styles") }}
                                    </span>
                                </div>
                            </div>

                            <div :class="`form-group${formErrors.has(`delivery_time`) ? ' has-error' : ''}`">
                                <label class="col-md-3 control-label" :for="`delivery_time`">Срок доставки</label>

                                <div class="col-md-2">
                                    <input
                                        type="number" step="1" min="0" class="form-control" :id="`delivery_time`"
                                        v-model="product.delivery_time"
                                        :name="`delivery_time`" v-validate="'max:255'">

                                    <span v-show="formErrors.has('delivery_time')" class="help-block">{{
                                            formErrors.first('delivery_time') }}</span>
                                </div>
                            </div>

                            <div :class="`form-group${formErrors.has(`production_time`) ? ' has-error' : ''}`">
                                <label class="col-md-3 control-label" :for="`production_time`">Срок изготовления</label>

                                <div class="col-md-2">
                                    <input
                                        type="number" step="1" min="0" class="form-control" :id="`production_time`"
                                        v-model="product.production_time"
                                        :name="`production_time`" v-validate="'max:255'">

                                    <span v-show="formErrors.has('production_time')" class="help-block">{{
                                            formErrors.first('production_time') }}</span>
                                </div>
                            </div>

                            <div :class="`form-group${formErrors.has('available') ? ' has-error' : ''}`">
                                <label class="col-md-3 control-label">
                                    Наличие
                                </label>
                                <div class="col-md-9">
                                    <label class="switch switch-primary">
                                        <input type="checkbox" id="available" name="available"
                                               v-model="product.available">
                                        <span></span>
                                    </label>

                                    <span v-show="formErrors.has('available')" class="help-block">
                                        {{ formErrors.first('available') }}
                                    </span>
                                </div>
                            </div>

                            <div :class="`form-group${formErrors.has('enabled') ? ' has-error' : ''}`">
                                <label class="col-md-3 control-label">
                                    Опубликовано
                                </label>
                                <div class="col-md-9">
                                    <label class="switch switch-primary">
                                        <input type="checkbox" id="enabled" name="enabled" v-model="product.enabled">
                                        <span></span>
                                    </label>

                                    <span v-show="formErrors.has('enabled')" class="help-block">
                                        {{ formErrors.first("enabled") }}
                                    </span>
                                </div>
                            </div>

                            <div :class="`form-group${formErrors.has('is_payable') ? ' has-error' : ''}`">
                                <label class="col-md-3 control-label">Доступность оплаты</label>
                                <div class="col-md-9">
                                    <label class="switch switch-primary">
                                        <input type="checkbox" id="is-payable" name="is_payable"
                                               v-model="product.is_payable">
                                        <span></span>
                                    </label>
                                    <span v-show="formErrors.has('is_payable')" class="help-block">
                                        {{ formErrors.first("is_payable") }}
                                    </span>
                                </div>
                            </div>

                            <div :class="`form-group${formErrors.has('on_order') ? ' has-error' : ''}`">
                                <label class="col-md-3 control-label">Под заказ</label>
                                <div class="col-md-9">
                                    <label class="switch switch-primary">
                                        <input type="checkbox" id="on-order" name="on_order"
                                               v-model="product.on_order">
                                        <span></span>
                                    </label>
                                    <span v-show="formErrors.has('on_order')" class="help-block">
                                        {{ formErrors.first("on_order") }}
                                    </span>
                                </div>
                            </div>

                          <div :class="`form-group${formErrors.has('receipt-date') ? ' has-error' : ''}`">
                            <label class="col-md-3 control-label">
                              Дата поступления
                            </label>

                            <div class="col-md-8">
                              <div class="input-group">
                                <input
                                  disabled
                                  type="text"
                                  class="form-control"
                                  id="gtin"
                                  v-model="product.receipt_date"
                                  name="gtin"
                                  v-validate="'max:32'">
                              </div>

                              <span v-show="formErrors.has('receipt-date')" class="help-block">
                                        {{ formErrors.first("receipt-date") }}
                                    </span>
                            </div>
                          </div>

                          <div :class="`form-group${formErrors.has('quantity') ? ' has-error' : ''}`">
                            <label class="col-md-3 control-label">
                              Количество
                            </label>

                            <div class="col-md-8">
                              <div class="input-group">
                                <input
                                  disabled
                                  type="text"
                                  class="form-control"
                                  id="quantity"
                                  v-model="product.quantity"
                                  name="quantity"
                                  v-validate="'max:32'">
                              </div>

                              <span v-show="formErrors.has('quantity')" class="help-block">
                                        {{ formErrors.first("quantity") }}
                                    </span>
                            </div>
                          </div>

                        </div>
                    </div>

                    <div class="block" v-if="type === 'edit'">
                        <div class="block-title clearfix">
                            <h2>
                                <i class="fa fa-clone"></i> <strong>Из этой колекции</strong>
                            </h2>
                        </div>

                        <div class="block-section">
                            <div :class="`form-group${formErrors.has('related') ? ' has-error' : ''}`">
                                <div class="input-group">
                                    <ajax-multiselect
                                        :url="relatedSearchUrl()"
                                        :languages="languages"
                                        :activeLanguageCode="activeLanguageCode"
                                        :selected.sync="product.related"
                                        :options="product.relatedOptions"
                                        :link-url-maker="relatedLinkMaker"
                                        :multiple="true"
                                    ></ajax-multiselect>
                                </div>

                                <span v-show="formErrors.has('related')" class="help-block">
                                    {{ formErrors.first("related") }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="block">
                        <div class="block-title clearfix">
                            <h2>
                                <i class="fa fa-clone"></i> <strong>Бейджи</strong>
                            </h2>
                        </div>

                        <div class="block-section">
                            <badges-select
                                :languages="languages"
                                :activeLanguageCode="activeLanguageCode"
                                :selected.sync="product.badges"
                            ></badges-select>
                        </div>
                    </div>

                </div>

                <!-- Габариты -->

                <div class="col-lg-6">
                    <div class="block">
                        <div class="block-title">
                            <h2>
                                <i class="fa fa-truck"></i> Габариты
                            </h2>
                        </div>

                        <div class="form-horizontal form-bordered">
                            <label>В собраном виде</label>
                            <div :class="`form-group${formErrors.has('width') ? ' has-error' : ''}`">
                                <label class="col-md-3 control-label" for="width">
                                    Ширина <span class="text-danger">*</span>
                                </label>

                                <div class="col-md-9">
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="width"
                                            v-model="product.width"
                                            name="width"
                                            v-number
                                            v-validate="'integer|min_value:1|max_value:2147483647'">

                                        <size-converter :value="product.width"/>
                                    </div>

                                    <span v-show="formErrors.has('width')" class="help-block">
                                        {{ formErrors.first("width") }}
                                    </span>
                                </div>
                            </div>

                            <div :class="`form-group${formErrors.has('height') ? ' has-error' : ''}`">
                                <label class="col-md-3 control-label" for="height">
                                    Высота <span class="text-danger">*</span>
                                </label>

                                <div class="col-md-9">
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="height"
                                            v-model="product.height"
                                            name="height"
                                            v-number
                                            v-validate="'integer|min_value:1|max_value:2147483647'">

                                        <size-converter :value="product.height"/>
                                    </div>

                                    <span v-show="formErrors.has('height')" class="help-block">
                                        {{ formErrors.first("height") }}
                                    </span>
                                </div>
                            </div>

                            <div :class="`form-group${formErrors.has('length') ? ' has-error' : ''}`">
                                <label class="col-md-3 control-label" for="length">
                                    Длина <span class="text-danger">*</span>
                                </label>

                                <div class="col-md-9">
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="length"
                                            v-model="product.length"
                                            name="length"
                                            v-number
                                            v-validate="'integer|min_value:1|max_value:2147483647'">

                                        <size-converter :value="product.length"/>
                                    </div>

                                    <span v-show="formErrors.has('length')" class="help-block">
                                        {{ formErrors.first("length") }}
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label" for="weight">Вес</label>

                                <div class="col-md-9">
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="weight"
                                            v-model="product.weight"
                                            name="weight"
                                            v-number
                                            v-validate="'integer|min_value:1|max_value:2147483647'">

                                        <weight-converter :value="product.weight"/>
                                    </div>
                                </div>
                            </div>

                            <label>Для транспортировки</label>
                            <a
                                class="btn btn-sm btn-prime btn-primary active"
                                style="margin-left: 40px;"
                                @click="addCargoPlace">
                                Добавить транспортировочное место
                            </a>
                            <div v-for="(cargo_place, index) in product.cargo_places">
                                <div class="row mt-20">
                                    <label class="col-md-3"> Место #{{ index + 1 }}</label>
                                    <div  @click="deleteCargoPlace"
                                          class="attributorEdit__delete col-md-6"
                                          :data-cargo-place-id="index">
                                        <button class="btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div :class="`form-group${!!formPageErrors['delivery_width'+index] || formErrors.has('delivery_width'+index) ? ' has-error' : ''}`">
                                    <label class="col-md-3 control-label" :for="'delivery_width'+index">
                                        Ширина <span class="text-danger">*</span>
                                    </label>

                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                class="form-control"
                                                @focus="function(){ formPageErrors['delivery_width'+index] = false; }"
                                                :id="'delivery_width'+index"
                                                v-model="cargo_place.width"
                                                :name="'delivery_width'+index"
                                                v-number
                                                v-validate="'required|integer|min_value:1|max_value:2147483647'">

                                            <size-converter :value="cargo_place.width"/>
                                        </div>
                                    </div>
                                </div>

                                <div :class="`form-group${!!formPageErrors['delivery_height'+index] || formErrors.has('delivery_height'+index) ? ' has-error' : ''}`">
                                    <label class="col-md-3 control-label" :for="'height'+index">
                                        Высота <span class="text-danger">*</span>
                                    </label>

                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                class="form-control"
                                                @focus="function(){ formPageErrors['delivery_height'+index] = false; }"
                                                :id="'delivery_height'+index"
                                                v-model="cargo_place.height"
                                                :name="'delivery_height'+index"
                                                v-number
                                                v-validate="'required|integer|min_value:1|max_value:2147483647'">

                                            <size-converter :value="cargo_place.height"/>
                                        </div>
                                    </div>
                                </div>

                                <div :class="`form-group${!!formPageErrors['delivery_length'+index] || formErrors.has('delivery_length'+index) ? ' has-error' : ''}`">
                                    <label class="col-md-3 control-label" :for="'length'+index">
                                        Длина <span class="text-danger">*</span>
                                    </label>

                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                class="form-control"
                                                @focus="function(){ formPageErrors['delivery_length'+index] = false; }"
                                                :id="'delivery_length'+index"
                                                v-model="cargo_place.length"
                                                :name="'delivery_length'+index"
                                                v-number
                                                v-validate="'required|integer|min_value:1|max_value:2147483647'">

                                            <size-converter :value="cargo_place.length"/>
                                        </div>
                                    </div>
                                </div>

                                <div :class="`form-group${!!formPageErrors['delivery_weight'+index] || formErrors.has('delivery_weight'+index) ? ' has-error' : ''}`">
                                    <label class="col-md-3 control-label" :for="'weight'+index">
                                        Вес <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                class="form-control"
                                                @focus="function(){ formPageErrors['delivery_weight'+index] = false; }"
                                                :id="'delivery_weight'+index"
                                                v-model="cargo_place.weight"
                                                :name="'delivery_weight'+index"
                                                v-number
                                                v-validate="'required|integer|min_value:1|max_value:2147483647'">

                                            <weight-converter :value="cargo_place.weight"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="block">
                        <div class="block-title">
                            <h2><i class="fa fa-list"></i> Характеристики</h2>
                        </div>

                        <attributor-add
                            :attr.sync="addGlobalAttributes"
                            relation="product"
                            :relation_id="+id"
                            :onlyGlobal="false"
                            @createAttribute="createAttribute"
                        >
                        </attributor-add>

                        <attributor-edit
                            @delAttribute="delAttribute"
                            @delOption="delOption"
                            @updateState="updateState"
                            @reloadAttributeProduct="init"
                            v-for="attribute in selectedGlobalAttributes"
                            :id="attribute.attr.id"
                            :key="attribute.attr.id"
                            :optionsEditable="true"
                            :attr.sync="attribute"
                        >
                        </attributor-edit>
                    </div>

                    <!-- Изображения -->
                    <div class="block" v-if="type === 'edit'">
                        <div class="block-title">
                            <h2><i class="fa fa-image"></i> <strong>Изображения</strong></h2>
                        </div>

                        <div class="block-section">
                            <dropzone-gallery
                                ref="gallery"
                                v-if="type === 'edit'"
                                :url="makePageApiUrl('image')"
                                :images.sync="product.images"
                                :errors="formErrors"/>
                        </div>
                    </div>

                </div>
            </div>

            <ProductVideos
                v-if="product"
                :product_id="id">
            </ProductVideos>

            <div v-if="product" class="block">
                <div class="block-title">
                    <h2><i class="fa fa-exchange"></i> Другие варианты товара</h2>
                </div>
                <other-product :product_id="+id" />
            </div>

            <div v-if="product" class="block">
                <div class="block-title">
                    <h2><i class="fa fa-list"></i> Свойства товаров</h2>
                </div>
                <div class="row">
                    <div :class="{'col-md-8': product.supplier_id}">
                        <attributor-add
                            :attr.sync="addAttributes"
                            relation="product"
                            :relation_id="+id"
                            :onlyGlobal="false"
                            @createAttribute="createAttribute"
                        >
                        </attributor-add>
                    </div>
                    <div v-if="product.supplier_id" class="col-md-4">
                        <button
                            @click="addAttributesAndOptionsFromSupplier()"
                            class="btn btn-primary btn-block">
                            Подставить от поставщика
                        </button>
                    </div>
                </div>

                <attributor-edit
                    @delAttribute="delAttribute"
                    @delOption="delOption"
                    @updateState="updateState"
                    @reloadAttributeProduct="init"
                    v-for="attribute in selectedAttributes"
                    :id="attribute.attr.id"
                    :key="attribute.attr.id"
                    :optionsEditable="true"
                    :attr.sync="attribute"
                >
                </attributor-edit>

            </div>

            <div class="block" v-if="product && product.prices">
                <div class="block-title">
                    <h2><i class="fa fa-money"></i> Цены</h2>
                </div>
                <prices-table
                    :prices.sync="product.prices"
                    :activeLanguageCode="activeLanguageCode"
                    :errors="formErrors"
                ></prices-table>
            </div>

            <div class="block" v-if="product">
                <div class="block-title">
                    <h2>Статистика</h2>
                </div>
                <div class="form-horizontal form-bordered">
                    <div class="form-group" v-if="'showed' in product">
                        <label class="col-md-3 control-label">Показано раз</label>
                        <div class="col-md-9">
                            <p class="form-control-static">{{ product.showed }}</p>
                        </div>
                    </div>
                    <div class="form-group" v-if="'bought' in product">
                        <label class="col-md-3 control-label">Куплено раз</label>
                        <div class="col-md-9">
                            <p class="form-control-static">{{ product.bought }}</p>
                        </div>
                    </div>
                    <div class="form-group" v-if="product.created_at">
                        <label class="col-md-3 control-label">Дата создания</label>
                        <div class="col-md-9">
                            <p class="form-control-static">{{ product.created_at }}</p>
                        </div>
                    </div>
                    <div class="form-group" v-if="product.updated_at">
                        <label class="col-md-3 control-label">Последнее изменение</label>
                        <div class="col-md-9">
                            <p class="form-control-static">{{ product.updated_at }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <b-modal
            id="validationModal"
            ref="validationModal"
            title="Ошибка валидации"
            title-tag="h3"
            centered
            ok-title="Ок"
            ok-only
            hide-header-close>
            Проверьте правильность заполнения формы!
        </b-modal>

        <b-modal
            id="validationSuppliersModal"
            ref="validationSuppliersModal"
            title="Ошибка валидации"
            title-tag="h3"
            centered
            ok-title="Ок"
            ok-only
            hide-header-close>
            Проверьте правильность заполнения поля "Поставщики"!
        </b-modal>

        <b-modal
            id="removeModal"
            ref="removeModal"
            title="Удаление товара"
            title-tag="h3"
            centered
            ok-title="Удалить"
            cancel-title="Отмена"
            hide-header-close
            @ok="removeConfirm">
            Вы действительно хотите удалить этот товар?
        </b-modal>

    </div>
</template>

<script>
    // import _ from "lodash";
    import Core from "../../../core";

    import {
        getAddAttributes,
        createAttributeProduct,
        updateStateAttributeRealation,
        deleteOption,
        deleteAttribute,
        getAttributes,
        getGlobalAttributes,
        getAddGlobalAttributes,
    } from "../../../api/attribute";

    //    import bModal from "bootstrap-vue/es/components/modal/modal";

    import AjaxMultiselect from "../../AjaxMultiselect";

    import TreeSelect from "../../TreeSelect";
    import TreeSelectTranslatable from "../../TreeSelectTranslatable";
    import CKEditor from "../../CKEditor";
    import LanguagePicker from "../../LanguagePicker";

    import PricesTable from "../PricesTable";
    import DropzoneGallery from "../../DropzoneGallery";
    import EntityPage from "../../../mixins/EntityPage";
    import Translatable from "../../../mixins/Translatable";
    import BadgesSelect from "../badges/BadgesSelect";

    import number from "../../../directives/number";

    import WeightConverter from "../../converters/WeightConverter";
    import SizeConverter from "../../converters/SizeConverter";

    import {asyncPackageDataCollector} from "../../../core/queueHandler";

    import ProductModel from "../../../resources/shop/ProductModel";
    import ProductAttributesModel from "../../../resources/shop/ProductAttributesModel";

    import OtherProduct from "../../../views/otherproduct/OtherProduct";

    // todo: добавить поддержку ролей

    export default {
        name: "product-edit",

        mixins: [
            EntityPage,
            Translatable,
        ],

        components: {
            OtherProduct,
            TreeSelect,
            TreeSelectTranslatable,
            ckeditor        : CKEditor,
            LanguagePicker,
//            bModal,
            DropzoneGallery,
            PricesTable,
            WeightConverter,
            SizeConverter,
            AjaxMultiselect,
            BadgesSelect,
            ProductVideos   : require("./ProductVideos.vue").default,
            AttributesSelect: require("../AttributesSelect.vue").default,
        },

        directives: {
            ...number,
        },

        props: [
            'id',
        ],
        data() {
            return {
                rbacNamespace: "shop.products",
                entityName   : "product",
                product      : null,

                attributes: [],

                categoriesTree: [],
                currencies    : [],
                priceTypes    : [],
                suppliers     : [],
                rooms         : [],
                styles        : [],
                sale          : {},

                usedMainData      : [
                    "languages",
                    "price-types",
                    "currencies",
                    "categories-tree",
                    "suppliers",
                    "rooms",
                    "styles",
                    'sale',
                ],

                saveButtonDisabled: false,

                //New proccessing
                selectedGlobalAttributes: [],
                selectedAttributes: [],
                addGlobalAttributes: [],
                addAttributes: [],

                formPageErrors: {},
                keyComponent: 1,
            };
        },

        created() {
            this.init();
        },

        methods: {
            forceRerender() {
                let formErrors = this.formErrors;
                this.keyComponent++;
                this.$nextTick(() => { this.formErrors = formErrors; });
            },
            validationPageForm() {
                let result = true;
                let offset = 20;
                let duration = 10000;

                this.product.id_from_supplier = !!this.product.id_from_supplier ? this.product.id_from_supplier.trim() : '';
                this.product.gtin = !!this.product.gtin ? this.product.gtin.trim() : '';

                if (!this.product.id_from_supplier) {
                    this.$message(
                        {
                            showClose: true,
                            message: 'Поле "ID у поставщика" должно быть заполнено',
                            type: 'error',
                            duration: duration,
                            offset: offset,
                        }
                    );

                    this.formPageErrors['id_from_supplier'] = true;

                    offset += 20;
                    result = false;
                } else {
                    this.formPageErrors['id_from_supplier'] = false;
                }

                if (!this.product.gtin) {
                    this.$message(
                        {
                            showClose: true,
                            message: 'Поле "GTIN" должно быть заполнено',
                            type: 'error',
                            duration: duration,
                            offset: offset,
                        }
                    );

                    this.formPageErrors['gtin'] = true;

                    offset += 20;
                    result = false;
                } else {
                    this.formPageErrors['gtin'] = false;
                }

                if (0 === this.product.cargo_places.length) {
                    this.$message(
                        {
                            showClose: true,
                            message: 'Необходимы габариты для транспортировки',
                            type: 'error',
                            duration: duration,
                            offset: offset,
                        }
                    );

                    offset += 20;
                    result = false;
                }

                for (let i = 0; i < this.product.cargo_places.length; i++) {
                    let delivery_width = 'delivery_width' + i;
                    let delivery_height = 'delivery_height' + i;
                    let delivery_length = 'delivery_length' + i;
                    let delivery_weight = 'delivery_weight' + i;

                    if (!this.product.cargo_places[i].width && !parseInt(this.product.cargo_places[i].width)) {
                        this.$message(
                            {
                                showClose: true,
                                message: 'Неверное значение Ширины, Место ' + (i + 1),
                                type: 'error',
                                duration: duration,
                                offset: offset,
                            }
                        );

                        this.formPageErrors[delivery_width] = true;

                        offset += 20;
                        result = false;
                    } else {
                        this.formPageErrors[delivery_width] = false;
                    }

                    if (!this.product.cargo_places[i].height && !parseInt(this.product.cargo_places[i].height)) {
                        this.$message(
                            {
                                showClose: true,
                                message: 'Неверное значение Высоты, место ' + (i + 1),
                                type: 'error',
                                duration: duration,
                                offset: offset,
                            }
                        );

                        this.formPageErrors[delivery_height] = true;

                        offset += 20;
                        result = false;
                    } else {
                        this.formPageErrors[delivery_height] = false;
                    }

                    if (!this.product.cargo_places[i].length && !parseInt(this.product.cargo_places[i].length)) {
                        this.$message(
                            {
                                showClose: true,
                                message: 'Неверное значение Длинна, Место ' + (i + 1),
                                type: 'error',
                                duration: duration,
                                offset: offset,
                            }
                        );

                        this.formPageErrors[delivery_length] = true;

                        offset += 20;
                        result = false;
                    } else {
                        this.formPageErrors[delivery_length] = false;
                    }

                    if (!this.product.cargo_places[i].weight && !parseInt(this.product.cargo_places[i].weight)) {
                        this.$message(
                            {
                                showClose: true,
                                message: 'Неверное значение Вес, Место ' + (i + 1),
                                type: 'error',
                                duration: duration,
                                offset: offset,
                            }
                        );

                        this.formPageErrors[delivery_weight] = true;

                        offset += 20;
                        result = false;
                    } else {
                        this.formPageErrors[delivery_weight] = false;
                    }
                }

                return result;
            },
            async savePageProduct() {
                if (this.validationPageForm()) {
                    try {
                        await this.save();
                    } catch (error) {
                        console.log('error');
                        console.log(error);
                    }
                }

                this.forceRerender();
            },
            init(){
                this.loadAttributesOptions();
                this.loadAddAttributes();
                this.loadGlobalAttributesOptions();
                this.loadAddGlobalAttributes();
            },
            async updateState(payload) {
                try {
                    const { data } = await updateStateAttributeRealation(payload);
                }  catch (error) {
                    copnsole.log(error);
                }
            },

            async createAttribute(payload) {
                try {
                    const { data } = await createAttributeProduct(payload);
                    this.init();
                } catch (error) {
                    console.log(error);
                }
            },

            async delOption(optionId) {
                try {
                    const { data } = await deleteOption(optionId);
                    this.loadAttributesOptions();
                    this.loadGlobalAttributesOptions();
                } catch (error) {
                    console.log(error);
                }
            },

            async delAttribute(attrRelation) {
                 try {
                     const { data } = await deleteAttribute(attrRelation);
                     this.init();
                 } catch (exception) {
                     console.log(exception);
                 }
            },

            async loadAttributesOptions() {
                try {
                    const { data } = await getAttributes(this.id);
                    this.selectedAttributes = data;
                } catch (exception) {
                    console.log(exception)
                }
            },

            async loadGlobalAttributesOptions() {
                try {
                    const { data } = await getGlobalAttributes(this.id);
                    this.selectedGlobalAttributes = data;
                } catch (exception) {
                    console.log(exception)
                }
            },

            async loadAddAttributes() {
                try {
                    const { data } = await getAddAttributes(this.id);
                    this.addAttributes = data;
                } catch (error) {
                    console.log(error);
                }
            },

            async loadAddGlobalAttributes() {
                try {
                    const { data } = await getAddGlobalAttributes(this.id);
                    this.addGlobalAttributes = data;
                } catch (error) {
                    console.log(error);
                }
            },

            loadData() {
                const a = new asyncPackageDataCollector();
                a.add(() => this.fetchMainData());

                // a.add(new Core.requestHandler("get", "/api/shop/attributes"));

                if (this.type === "edit") {
                    a.add(this.fetchEntity());
                }

                a.onDone(data => {
                    this.initData(data);
                });

                a.start();
            },

            initData(data) {
                this.$store.dispatch("priceTypes/get");
                this.initMainData(data);
                this.initEntity(data[this.getEntityName()]);
                this.$store.commit("product/set", ["product", this.product]);
            },

            /**
             * Инициализация модели.
             *
             * @param data
             */
            initEntity(data) {
                this.setEntityData(new ProductModel(data, this.languages));
            },

            initAttributes(data = []) {
                this.attributes = this.getSortedData(this.getEnabledData(data)).map(item => new ProductAttributesModel(item, this.languages));
            },

            updateSupplier(payload) {
                this.product.supplier_id = payload.id;
            },

            getToSaveData() {
                return {
                    ...this.getEntityModel(),
                    suppliers : this.getToSaveSuppliers(),
                    images    : this.getToSaveImages(),
                    attributes: this.getToSaveAttributes(),
                };
            },

            getToSaveImages() {
                return this.getEntityModel().images.reduce((acc, item) => {
                    if (!item.deleted) {
                        acc.push({
                            id           : item.id,
                            modifications: item.modifications,
                        });
                    }

                    return acc;
                }, []);
            },

            getToSaveAttributes() {
                let attributesSelect = this.$refs.attributesSelect;
                if (!attributesSelect) return {};

                return attributesSelect.getAttributesSelectedOptions();
            },

            /**
             *
             * @returns {any | Array}
             */
            getToSaveSuppliers() {
                return this.getEntityModel().suppliers.reduce((acc, item) => {
                    acc.push(+item);

                    return acc;
                }, []);
            },

            relatedSearchUrl() {
                return "/api" + window.location.pathname + "/search";
            },

            relatedLinkMaker(option) {
                let linkEl = document.createElement("a");
                linkEl.setAttribute("href", "/shop/products/" + option.id);
                linkEl.setAttribute("target", "_blank");
                linkEl.innerHTML = option.text;

                return linkEl;
            },

            addAttributeOption(payload) {
                payload.relation    = "product";
                payload.relation_id = this.id;
                payload.enabled     = true;
                if (!this.$store.getters["attributes/isOptionIdUsed"](payload.option_id)) {
                    this.$store.dispatch("attributes/addAttributeOptionToProduct", payload).then(() => {
                        this.getAttributeRelations();
                    });
                }
            },

            deleteAttributeOption(payload) {
                this.$store.dispatch("attributes/deleteAttributeOptionFromProduct", {
                    relation    : "product",
                    relation_id : this.id,
                    attribute_id: payload.attribute_id,
                    option_id   : payload.option_id,
                });
            },

            getAttributeRelations() {
                return this.$store.dispatch("attributes/getAttributesAndOptionsByRelation", {
                    relation   : "product",
                    relation_id: +this.id,
                });
            },

            addAttributesAndOptionsFromSupplier() {
                this.$store.dispatch("attributes/syncRelationElements", {
                    relation_source   : "supplier",
                    relation_source_id: +this.product.supplier_id,
                    relation_target   : "product",
                    relation_target_id: +this.id,
                }).then(() => {
                    this.getAttributeRelations().then(() => {
                        this.$core.notify("Свойства поставщика добавлены", {type: "success"});
                    });
                });
            },

            /**
             *
             * @returns {boolean}
             */
            isSaleActual() {
                if (this.product) {
                    return this.product.sale.status === 'active';
                }
            },

            /**
             * Set status of Sale accordingly to it's status and
             */
            setSaleStatus() {
                if (this.product) {
                    let status;

                    if (this.product.sale.timeLeft > 0 && this.product.sale.enabled) {
                        if (this.product.sale.currentTime < this.product.sale.date_start) {
                            status = 'inactive'
                        } else {
                            status = 'active'
                        }
                    } else {
                        status = 'finished'
                    }

                    if (this.product.sale.status !== status) {
                        this.product.sale.status = status
                    }
                }
            },

            /**
             * Set currentTime and timeLeft to active sale of Product.
             */
            setSaleParams() {
                if (this.product) {
                    let current = Math.round(this.getCurrent());

                    if (this.product.sale.currentTime !== current) {
                        this.product.sale.currentTime = current;
                        this.product.sale.timeLeft    = Math.max(0, Math.round(this.product.sale.date_finish - this.product.sale.currentTime));

                        this.setSaleStatus()
                    }
                }
            },

            /**
             *
             * @param seconds
             *
             * @returns {number}
             */
            getCurrent(seconds = true) {
                let time = +new Date() / 1000;

                if (!seconds) {
                    time = time * 1000;
                }

                return time
            },

            /**
             * Автозаполнение slug из заголовка.
             */
            slugAutocomplete() {
                let model  = this.getEntityModel()
                model.slug = Core.makeUrl(model.i18n[this.activeLanguageCode].title)
            },

            addCargoPlace() {
                this.$nextTick(function(){
                    this.$set(this.product.cargo_places,
                        this.product.cargo_places.length,
                        {width: null, height: null, length: null, weight:null}
                    );

//                    this.product.cargo_places.push({width: null, height: null, length: null, weight:null});
                });

//                this.product.cargo_places.push({width: null, height: null, length: null, weight:null});
            },

            deleteCargoPlace(e) {
                let rawIndex = +e.currentTarget.dataset['cargoPlaceId'];
                this.product.cargo_places.splice(rawIndex, 1);
            },
        },

        computed: {
            isSaleActive() {
                if (this.product) {
                    this.setSaleParams();

                    return this.isSaleActual();
                }

                return false;
            },

            suppliersToSelect() {
                return this.suppliers.map(supplier => ({
                    id   : supplier.id,
                    title: supplier.name,
                }));
            },

            roomsToSelect() {
                return this.rooms.map(room => {
                    return {
                        id   : room.id,
                        title: room.i18n[this.activeLanguageCode].title,
                    };
                });
            },

            stylesToSelect() {
                return this.styles.map(style => ({
                    id   : style.id,
                    title: style.i18n[this.activeLanguageCode].title,
                }));
            },
        },
        // beforeMount() {
        //     this.getAttributeRelations().then(response => {
        //         let $usedAttributes = response.map(function (attribute) {
        //             return attribute.attribute_id;
        //         });
        //         let $usedOptionsRaw = response.map(function (attribute) {
        //             return attribute.linked_options.map(function (option) {
        //                 return option.id;
        //             });
        //         });
        //
        //         let firstElem = $usedOptionsRaw.shift();
        //
        //         let $usedOptions = $usedOptionsRaw.reduce(function (memo, item) {
        //             if (item.length > 0) {
        //                 memo.push(...item);
        //                 return memo;
        //             } else {
        //                 return memo;
        //             }
        //
        //         }, firstElem)
        //
        //         this.$store.dispatch("attributes/loadData", {usedAttributes: $usedAttributes, usedOptions: $usedOptions});
        //     });
        // },

    };
</script>
