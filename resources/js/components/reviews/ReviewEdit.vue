<script>
//  import bModal from 'bootstrap-vue/es/components/modal/modal'

import CKEditor from '../CKEditor';
import LanguagePicker from '../LanguagePicker';

import EntityPage from '../../mixins/EntityPage';

import ReviewsModel from '../../resources/ReviewsModel';
import DatePickerMixin from '../../mixins/DatePicker/DatePicker';

export default {
  name: 'ReviewEdit',

  components: {
    'ckeditor': CKEditor,
    LanguagePicker,
    // bModal
  },

  mixins: [
    EntityPage,
    DatePickerMixin,
  ],

  props: [
    'id',
  ],

  data() {
    return {
      entityName: 'review',
      review: null,

      usedMainData: [
        'languages',
      ],
    };
  },

  methods: {
    initEntity(data) {
      this.setEntityData(new ReviewsModel(data, this.languages));
    },
  },
};
</script>

<template>
  <div>
    <div class="block full">
      <div class="block-title clearfix">
        <h1>
          <strong>
            Редактирование отзыва #{{ this.id }}
          </strong>
        </h1>

        <div class="block-title-control">
          <a class="btn btn-sm btn-default btn-alt" @click="redirectToTable">
            <i class="fa fa-arrow-left" />
          </a>

          <span class="btn-separator-xs" />

          <a v-if="userCan('reviews.edit')" class="btn btn-sm btn-primary active" @click="save">
            <i class="fa fa-floppy-o" /> Сохранить
          </a>

          <a v-if="userCan('reviews.delete')" class="btn btn-sm btn-danger active" @click="remove">
            Удалить
          </a>
        </div>
      </div>

      <div v-if="review" class="form-horizontal form-bordered">
        <div :class="`form-group${formErrors.has('rate') ? ' has-error' : ''}`">
          <label class="col-md-3 control-label" for="rate">
            Оценка <span class="text-danger">*</span>
          </label>

          <div class="col-md-9">
            <select id="rate" v-model="review.rate" class="form-control" name="rate" style="width: 50px">
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
            </select>

            <span v-show="formErrors.has('rate')" class="help-block">
              {{ formErrors.first('rate') }}
            </span>
          </div>
        </div>

        <div :class="`form-group${formErrors.has('advantages') ? ' has-error' : ''}`">
          <label class="col-md-3 control-label" for="advantages">
            Преимущества
          </label>

          <div class="col-md-9">
            <ckeditor id="advantages" :content.sync="review.advantages" name="advantages" />

            <span v-show="formErrors.has('advantages')" class="help-block">
              {{ formErrors.first('advantages') }}
            </span>
          </div>
        </div>

        <div :class="`form-group${formErrors.has('disadvantages') ? ' has-error' : ''}`">
          <label class="col-md-3 control-label" for="disadvantages">
            Преимущества
          </label>

          <div class="col-md-9">
            <ckeditor id="disadvantages" :content.sync="review.disadvantages" name="disadvantages" />

            <span v-show="formErrors.has('disadvantages')" class="help-block">
              {{ formErrors.first('disadvantages') }}
            </span>
          </div>
        </div>

        <div :class="`form-group${formErrors.has('comment') ? ' has-error' : ''}`">
          <label class="col-md-3 control-label" for="comment">
            Комментарий
          </label>

          <div class="col-md-9">
            <ckeditor id="comment" :content.sync="review.comment" name="comment" />

            <span v-show="formErrors.has('comment')" class="help-block">
              {{ formErrors.first('comment') }}
            </span>
          </div>
        </div>

        <div :class="`form-group${formErrors.has('enabled') ? ' has-error' : ''}`">
          <label class="col-md-3 control-label">
            Подтвержден
          </label>

          <div class="col-md-9">
            <label class="switch switch-primary">
              <input v-model="review.confirmed" type="checkbox">
              <span />
            </label>

            <span v-show="formErrors.has('confirmed')" class="help-block">
              {{ formErrors.first('confirmed') }}
            </span>
          </div>
        </div>

        <div :class="`form-group${formErrors.has('enabled') ? ' has-error' : ''}`">
          <label class="col-md-3 control-label">
            Удалено пользователем
          </label>

          <div class="col-md-9">
            <label class="switch switch-primary">
              <input v-model="review.enabled" type="checkbox">
              <span />
            </label>

            <span v-show="formErrors.has('enabled')" class="help-block">
              {{ formErrors.first('enabled') }}
            </span>
          </div>
        </div>

        <div v-if="review.created_at" class="form-group">
          <label class="col-md-3 control-label">
            Дата создания
          </label>

          <div class="col-md-9">
            <date-picker
              id="created_at"
              v-model="review.created_at"
              name="created_at"
              :config="getBaseDatePickerConfig()"
              class="date-picker"
              @dp-show="datePickerShow('created_at')"
              @dp-change="dateChanged('created_at')"
            />

            <span v-show="formErrors.has('created_at')" class="help-block">
              {{ formErrors.first('created_at') }}
            </span>
          </div>
        </div>

        <div v-if="review.updated_at" class="form-group">
          <label class="col-md-3 control-label">
            Последнее изменение
          </label>

          <div class="col-md-9">
            <date-picker
              id="updated_at"
              v-model="review.updated_at"
              name="updated_at"
              :config="getBaseDatePickerConfig()"
              class="date-picker"
              @dp-show="datePickerShow('updated_at')"
              @dp-change="dateChanged('updated_at')"
            />

            <span v-show="formErrors.has('updated_at')" class="help-block">
              {{ formErrors.first('updated_at') }}
            </span>
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
      hide-header-close
    >

      Проверьте правильность заполнения формы!
    </b-modal>

    <b-modal
      id="removeModal"
      ref="removeModal"
      title="Удаление отзыва"
      title-tag="h3"
      centered
      ok-title="Удалить"
      cancel-title="Отмена"
      hide-header-close
      @ok="removeConfirm"
    >

      Вы действительно хотите удалить отзыв?
    </b-modal>
  </div>
</template>

