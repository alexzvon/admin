<template>
  <el-container>
    <el-header height>
      <div class="info-reply">
        <span class=h2>Город присутствия франшизы: {{ input_name }}</span>
        <div>
          <el-button size="small" type="primary" @click="saveUpdateCity">Сохранить</el-button>
          <el-button size="small" @click="turnBack">Назад</el-button>
        </div>
      </div>
    </el-header>
    <el-main style="width:50%;">

      <div class="info-reply">
        <span class=h2>Название</span>
        <el-input
          placeholder="Please input"
          v-model="input_name"
          clearable>
        </el-input>
      </div>

      <div class="info-reply">
        <span class=h2>Страна</span>
        <el-select v-model="input_country" placeholder="Select">
          <el-option
            v-for="item in country"
            :key="item.id"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
      </div>

      <div class="info-reply">
        <span class=h2>Часовой пояс</span>
        <el-select v-model="input_gmt" placeholder="Select">
          <el-option
            v-for="item in gmt"
            :key="item.id"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
      </div>

      <div class="info-reply">
        <span class=h2>Процент надбавки</span>
        <el-input-number v-model="input_percent" :min="0" :max="100"></el-input-number>
      </div>

      <div class="info-reply">
        <span class=h2>Статус</span>
        <el-switch v-model="input_status">
        </el-switch>
      </div>

    </el-main>
  </el-container>
</template>

<script>

import { getListCountry, getListGmt, getCity, updateCity } from '../../api/territory';

export default {
  name: "quickCreateFilter",
  data() {
    return {
      input_name: '',
      input_country: '',
      input_gmt: '',
      input_percent: 0,
      input_status: '',

      city_id: '',

      country: [],
      gmt: [],
    };
  },
  created() {
    this.city_id = this.$route.params.id;
    this.getCountry();
    this.getGmt();
    this.getCityEdit();
  },

  methods: {
    async getCountry() {
      try{
        const { data } = await getListCountry();
        this.country = data;
      }
      catch(error) {
        this.$message.error(error);
        console.log(error);
      }
    },
    async getGmt() {
      try {
        const { data } = await getListGmt();
        this.gmt = data;
      }
      catch (error) {
        this.$message.error(error);
        console.log(error);
      }
    },

    async getCityEdit() {
      const loading = this.$loading({
        lock: true,
        text: 'Loading',
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.1)'
      });
      try {
        const { data } = await getCity(this.city_id);
        this.input_name = data.input_name;
        this.input_country = data.input_country;
        this.input_gmt = data.input_gmt;
        this.input_percent = data.input_percent;
        this.input_status = data.input_status;
        loading.close();
      }
      catch(error) {
        loading.close();
        console.log(error);
      }
    },

    validation() {
      let result = true;

      if (!this.input_name) {
        this.$message.error('Отсутствует название города');
        result = false;
      }
      if (!this.input_country) {
        this.$message.error('Отсутствует страна');
        result = false;
      }
      if (!this.input_gmt) {
        this.$message.error('Отсутствует часовой пояс');
        result = false;
      }

      return result;
    },

    turnBack() {
      this.$router.push('/city/table');
    },

    async saveUpdateCity() {
      if (this.validation()) {
        const loading = this.$loading({
          lock: true,
          text: 'Loading',
          spinner: 'el-icon-loading',
          background: 'rgba(0, 0, 0, 0.1)'
        });

        try{
          const data = {
            name: this.input_name,
            country_id: this.input_country,
            gmt_id: this.input_gmt,
            percent: this.input_percent,
            status: this.input_status,
          };

          let update = await updateCity(this.city_id, data);

          if (!update) {
            this.$message.error('Ошибка сохранения');
          }

          loading.close();
        }
        catch(error) {
          this.$message.error(error);
          console.log(error);

          loading.close();
        }
      }
    },
  },
};
</script>

<style>
.el-upload__input {
  visibility: hidden;
}
.avatar-uploader .el-upload {
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}
.avatar-uploader .el-upload:hover {
  border-color: #409EFF;
}
.avatar-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 178px;
  height: 178px;
  line-height: 178px;
  text-align: center;
}
.avatar {
  width: 178px;
  height: 178px;
  display: block;
}
.info-reply {
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0px 1px 4px -2px rgb(0 0 0 / 55%);
  margin-bottom: 20px;
}
.h2 {
  display: inline-block;
  font-size: 15px;
  line-height: 1.4;
  margin: 0;
  padding: 10px 16px 7px;
  font-weight: 600;
}
.el-main {
  margin: 20px;
  width: 50%;
  /* border: 1px solid black; */
  box-shadow: 0px 1px 4px -2px rgb(0 0 0 / 55%);
}
.el-input-number .el-input {
  width: 180px;
}
</style>
