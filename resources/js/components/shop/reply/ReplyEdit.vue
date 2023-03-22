<template>
  <div>
    <div class="block mb-0">
      <div class="block-title mb-0 clearfix">
        <h1>Заявка на возврат {{ return_id }}</h1>
        <div class="block-title-control">
          <b-button variant="outline-primary" @click="saveEditReply">Сохранить</b-button>
          <b-button variant="success" @click="turnBack">Назад</b-button>
        </div>
      </div>
    </div>

    <loading :loading="isBusy">
      <div class="block mb-0">
        <div class="block-title mb-0 clearfix">
          <b-form-group>
            <b-form-radio-group
              v-model="selected"
              :options="options"
              name="radioInline"
              buttons
              class="ml-25"
            />
          </b-form-group>
        </div>
      </div>
      <b-card-group deck class="card-group clearfix">
        <b-card class="col-sm-6 clearfix">
          <div class="clearfix">
            <b-card-header class="ma-20">
              <div class="info-reply ma-20"><div>Заказ</div><div>{{ order }}</div></div>
              <div class="info-reply ma-20"><div>Название товара</div><div>{{ title }}</div></div>
              <div class="info-reply ma-20"><div>Количество единиц</div><div>{{ quantity }}</div></div>
              <div class="info-reply ma-20"><div>Стоимость единицы</div><div>{{ price }}</div></div>

              <b-form-textarea
                id="textarea1"
                v-model="desc"
                placeholder="Описание причин возврата"
                rows="3"
                max-rows="10"
              />
              <b-form-textarea
                id="textarea2"
                v-model="comm"
                placeholder="Комментарий администратора / причина отказа в возврате"
                rows="3"
                max-rows="10"
              />
            </b-card-header>
          </div>
        </b-card>

        <b-card class="col-sm-6">
          <div class="clearfix">
            <b-card-header class="ma-20">Прикреплённые изображения</b-card-header>
            <template v-for="(item, idx) in media">
              <b-card-img
                :key="idx"
                :src="item"
                alt="Image"
                class="rounded-0"
              />
            </template>
          </div>
        </b-card>
      </b-card-group>
    </loading>
  </div>
</template>

<script>

// import bFormGroup from "bootstrap-vue/es/components/form-group/form-group";
// import bFormRadio from "bootstrap-vue/es/components/form-radio/form-radio";
// import bFormRadioGroup from "bootstrap-vue/es/components/form-radio/form-radio-group";

// import bButton from 'bootstrap-vue/es/components/button/button';

// import bCard from 'bootstrap-vue/es/components/card/card';
// import bCardGroup from 'bootstrap-vue/es/components/card/card-group';
// import bCardHeader from 'bootstrap-vue/es/components/card/card-header';
// import bCardBody from 'bootstrap-vue/es/components/card/card-body';
//
// import bFormTextarea from 'bootstrap-vue/es/components/form-textarea/form-textarea';
//
// import bCardImg from 'bootstrap-vue/es/components/card/card-img';

import { getEdit, saveEdit } from '../../../api/reply';
import Loading from '../../Loading';

export default {
  name: 'ReplyTdit',
  components: {
    // bButton,
    // bFormGroup,
    // bFormRadio,
    // bFormRadioGroup,
    // bCard,
    // bCardGroup,
    // bCardHeader,
    // bCardBody,
    // bFormTextarea,
    // bCardImg,
    Loading,
  },
  data() {
    return {
      desc: '',
      comm: '',
      isBusy: false,
      return_id: 0,
      product_id: 0,
      selected: 1,
      order: 0,
      title: 0,
      quantity: 0,
      price: 0,
      options: [
        { text: 'На рассмотрении', value: 1 },
        { text: 'Возвращено', value: 2 },
        { text: 'Отказано', value: 3 },
      ],
      media: '',
    };
  },
  created() {
    this.return_id = this.$route.params.return_id;
    this.product_id = this.$route.params.product_id;

    this.getEditReply();
  },
  methods: {
    turnBack() {
      this.$router.push('/shop/reply/table');
    },

    async saveEditReply() {
      this.isBusy = true;

      try {
        await saveEdit({
          comment: this.comm,
          status_id: this.selected,
          return_id: this.return_id,
        });
        this.isBusy = false;
      } catch (error) {
        this.isBusy = false;
        console.log(error);
      }
    },

    async getEditReply() {
      this.isBusy = true;

      try {
        const res = await getEdit(this.return_id, this.product_id);
        this.order = res.data.order_id;
        this.title = res.data.title;
        this.quantity = res.data.quantity;
        this.price = res.data.price;
        this.desc = res.data.description;
        this.comm = res.data.comment;
        this.selected = res.data.status_id;
        this.media = res.data.media;

        //        console.log(res.data);

        this.isBusy = false;
      } catch (error) {
        this.isBusy = false;
        console.log(error);
      }
    },
  },
};
</script>

<style scoped lang="scss">
  .form-group {
    margin-bottom: 0;
  }
  .card-body {
    margin: 20px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.55);
  }
  .card-group {
    background: #fff;
  }
  .mb-0 {
    margin-bottom: 0 !important;
  }
  .ma-20 {
    margin: 20px !important;
  }
  .ml-25 {
    margin-left: 25px !important;
  }
  .info-reply {
    display: flex;
    justify-content: space-between;
    box-shadow: 0 1px 4px -2px rgba(0, 0, 0, 0.55);
  }
</style>
