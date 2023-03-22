<template>
  <el-container>
    <el-header height>
      <div class="info-reply">
        <span class=h2>Создать город</span>
        <div>
          <el-button size="small" type="primary" @click="saveCreateCities">Сохранить</el-button>
          <el-button size="small" @click="turnBack">Назад</el-button>
        </div>
      </div>
    </el-header>
    <el-main style="width:50%;">

      <div class="info-reply">
        <span class=h2>Название</span>
        <el-autocomplete
          v-model="input_name"
          :fetch-suggestions="querySearchAsync"
          placeholder="Введите субъект"
          @select="handleSelect"
        ></el-autocomplete>
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

    </el-main>
  </el-container>
</template>

<script>
import { getListGmt, createCities, search } from '@/api/territory';

export default {
  name: "citiesCreate",
  data() {
    return {
      input_name: '',
      input_district: '',
      input_region: '',
      input_country: '',
      input_gmt: '',
      input_percent: 0,
      input_object: '',

      gmt: [],

      query: '',
    };
  },
  created() {
    this.getGmt();
  },
  methods: {
    async querySearchAsync(queryString, cb) {
      let result = [];

      if (2 < queryString.length && this.query !== queryString) {
        try {
          const { data } = await search({q: queryString});
          result = data;
        }
        catch(error) {
          console.log(error);
          result = [];
        }
      }
      cb(result);
    },
    handleSelect(item) {
      this.input_object = item;
      this.query = item.name;
      this.input_name = item.name;
      this.input_district = item.district_name;
      this.input_region = item.region_name;
      this.input_country = item.country_name;
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

    validation() {
      let result = true;

      if (!this.input_object) {
        this.$message.error('Не выбран субъект');
        result = false;
      }
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
      this.$router.push('/cities/table');
    },

    async saveCreateCities() {
      if (this.validation()) {
        const loading = this.$loading({
          lock: true,
          text: 'Loading',
          spinner: 'el-icon-loading',
          background: 'rgba(0, 0, 0, 0.1)'
        });

        try{
          const { id } = await createCities({
            'cities_id': this.input_object.id,
            'regions_id': 0 < this.input_object.region_id &&  0 < this.input_object.district_id ? this.input_object.region_id : this.input_object.district_id,
            'regions_parent_id': 0 < this.input_object.region_id &&  0 < this.input_object.district_id ? this.input_object.district_id : 0,
            'title': this.input_object.value,
            'country_code': this.input_object.country_code,
            'language_code': this.input_object.language_code,
            'gmt_id': this.input_gmt,
            'percent': this.input_percent,
          });
          loading.close();
          if (id > 0) {
            this.$router.push('/cities/edit/' + id);
          }
        }
        catch(error) {
          console.log(error);
          loading.close();
        }
      }
    },
  },
};
</script>

<style scope>
  .info-reply {
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 1px 4px -2px rgb(0 0 0 / 55%);
    margin-bottom: 10px;
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
