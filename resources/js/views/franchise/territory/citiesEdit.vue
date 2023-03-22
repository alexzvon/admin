<template>
  <el-container>
    <el-header height>
      <div class="info-reply">
        <span class=h2>Город присутствия франшизы: {{ input_cities }}</span>
        <div>
          <el-button size="small" type="primary" @click="saveUpdateCities">Сохранить</el-button>
          <el-button size="small" @click="turnBack">Назад</el-button>
        </div>
      </div>
    </el-header>
    <el-main style="width:50%;">

      <div class="info-reply">
        <span class=h2>Название</span>
        <el-input
          placeholder="Please input"
          v-model="input_cities"
          readonly
          clearable>
        </el-input>
      </div>

      <div class="info-reply">
        <span class=h2>Страна</span>
        <el-input
          placeholder="Please input"
          v-model="input_country"
          readonly
          clearable>
        </el-input>
      </div>

      <div class="info-reply">
        <span class=h2>Регион</span>
        <el-input
          placeholder="Please input"
          v-model="input_district"
          readonly
          clearable>
        </el-input>
      </div>

      <div class="info-reply">
        <span class=h2>Район</span>
        <el-input
          placeholder="Please input"
          v-model="input_region"
          readonly
          clearable>
        </el-input>
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

import { getListGmt, getCitiesEdit, updateCities } from '@/api/territory';

export default {
  name: "citiesEdit",
  data() {
    return {
      input_cities: '',
      input_country: '',
      input_district: '',
      input_region: '',
      input_gmt: '',
      input_percent: 0,
      input_status: '',

      cities_id: '',

      gmt: [],
    };
  },
  created() {
    this.cities_id = this.$route.params.id;
    this.getGmt();
    this.getCitiesEdit();
  },

  methods: {
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

    async getCitiesEdit() {
      const loading = this.$loading({
        lock: true,
        text: 'Loading',
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.1)'
      });
      try {
        const { data } = await getCitiesEdit(this.cities_id);

        this.input_cities = data.name,
        this.input_country = data.country_name;
        this.input_district = data.district_name,
        this.input_region = data.region_name,
        this.input_gmt = data.gmt_id;
        this.input_percent = data.percent;
        this.input_status = data.status;

        loading.close();
      }
      catch(error) {
        loading.close();
        console.log(error);
      }
    },

    validation() {
      let result = true;

      if (!this.input_gmt) {
        this.$message.error('Отсутствует часовой пояс');
        result = false;
      }

      return result;
    },

    turnBack() {
      this.$router.push('/cities/table');
    },

    async saveUpdateCities() {
      if (this.validation()) {
        const loading = this.$loading({
          lock: true,
          text: 'Loading',
          spinner: 'el-icon-loading',
          background: 'rgba(0, 0, 0, 0.1)'
        });

        try{
          const data = {
            gmt_id: this.input_gmt,
            percent: this.input_percent,
            status: this.input_status,
          };

          let update = await updateCities(this.cities_id, data);

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
