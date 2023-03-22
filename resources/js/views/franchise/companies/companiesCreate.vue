<template>
  <el-container>
    <el-header height>
      <div class="info-reply">
        <span class="h2">Создать партнера</span>
        <div>
          <el-button size="small" type="primary" @click="saveCreateCompanies">Сохранить</el-button>
          <el-button size="small" @click="turnBack">Назад</el-button>
        </div>
      </div>
    </el-header>
    <el-main style="width:50%;">

      <div class="info-reply">
        <span class="h2">Пользователь</span>
        <el-select
          v-model="input_user"
          filterable
          reserve-keyword
          placeholder="Please enter a keyword"
          :loading="loading"
        >
          <el-option
            v-for="item in list"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
      </div>

      <div class="info-reply">
        <span class="h2">Юр. название</span>
        <el-input
          v-model="input_fio"
          placeholder="Please input"
          clearable
        />
      </div>

      <div class="info-reply">
        <span class="h2">Отображаемое имя</span>
        <el-input
          v-model="input_name"
          placeholder="Please input"
          clearable
        />
      </div>

      <div class="info-reply">
        <span class="h2">Город</span>
        <el-select v-model="input_cities" placeholder="Select">
          <el-option
            v-for="item in options"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
      </div>

      <div class="info-reply">
        <span class="h2">Юр. адрес</span>
        <el-input
          v-model="input_address"
          placeholder="Please input"
          clearable
        />
      </div>

      <div class="info-reply">
        <span class="h2">Адрес доставки</span>
        <el-input
          v-model="input_delivery_address"
          placeholder="Please input"
          clearable
        />
      </div>

      <div class="info-reply">
        <span class="h2">E-mail</span>
        <el-input
          v-model="input_email"
          placeholder="Please input"
          clearable
        />
      </div>

      <div class="info-reply">
        <span class="h2">Телефон</span>
        <el-input
          v-model="input_phone"
          placeholder="Please input"
          clearable
        />
      </div>

      <div class="info-reply">
        <span class="h2">Банк</span>
        <el-input
          v-model="input_bank"
          placeholder="Please input"
          clearable
        />
      </div>

      <div class="info-reply">
        <span class="h2">Бик</span>
        <el-input
          v-model="input_bik"
          placeholder="Please input"
          clearable
        />
      </div>

      <div class="info-reply">
        <span class="h2">К/счет</span>
        <el-input
          v-model="input_kor_account"
          placeholder="Please input"
          clearable
        />
      </div>

      <div class="info-reply">
        <span class="h2">ИНН</span>
        <el-input
          v-model="input_tin"
          placeholder="Please input"
          clearable
        />
      </div>

      <div class="info-reply">
        <span class="h2">КПП</span>
        <el-input
          v-model="input_trrc"
          placeholder="Please input"
          clearable
        />
      </div>

      <div class="info-reply">
        <span class="h2">Р/счет</span>
        <el-input
          v-model="input_rach_account"
          placeholder="Please input"
          clearable
        />
      </div>

      <div class="info-reply">
        <span class="h2">Аватар</span>
        <el-upload
          class="avatar-uploader"
          action=""
          :show-file-list="false"
          :disabled="false"
          :before-upload="beforeAvatarUpload"
          :http-request="httpRequest"
        >
          <img v-if="input_image" class="avatar" :src="input_image">
          <i v-else class="el-icon-plus avatar-uploader-icon" />
        </el-upload>
      </div>
    </el-main>
  </el-container>
</template>

<script>

import { getListCity, createCompanies, getListUsers } from '../../../api/companies';

export default {
  name: 'CompaniesCreate',
  data(){
    return {
      input_user: '',
      input_fio: '',
      input_cities: '',
      input_address: '',
      input_email: '',
      input_phone: '',
      input_bank: '',
      input_bik: '',
      input_kor_account: '',
      input_tin: '',
      input_trrc: '',
      input_rach_account: '',
      input_image: '',
      input_name: '',
      input_delivery_address: '',

      options: [],
      list: [],
      input_file: '',
      loading: false,

      users: [],
    };
  },
  created() {
    this.getFranchiseListCity();
    this.getListUsersFilter();
  },
  methods: {
    async getListUsersFilter() {
      const loading = this.$loading({
        lock: true,
        text: 'Loading',
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.1)',
      });
      try {
        const { users } = await getListUsers();
        this.users = users;
        this.list = this.users.map(item => {
          return { value: item.id, label: item.first_name + ' ' + item.last_name + ' ' + item.email };
        });
        loading.close();
      } catch (error) {
        loading.close();
        this.$message.error(error);
        console.log(error);
      }
    },
    async getFranchiseListCity() {
      try {
        const { data } = await getListCity();
        this.options = data;
      } catch (error) {
        this.$message.error(error);
        console.log(error);
      }
    },
    validation() {
      let result = true;

      if (!this.input_user) {
        this.$message.error('Отсутствует Пользователь');
        result = false;
      }
      if (!this.input_fio) {
        this.$message.error('Отсутствует название организации');
        result = false;
      }
      if (!this.input_name) {
        this.$message.error('Отсутствует отображаемое имя');
        result = false;
      }
      if (!this.input_delivery_address) {
        this.$message.error('Отсутствует адрес доставки');
        result = false;
      }
      if (!this.input_cities) {
        this.$message.error('Отсутствует город');
        result = false;
      }
      if (!this.input_address) {
        this.$message.error('Отсутствует юридический адрес');
        result = false;
      }
      if (!this.input_email) {
        this.$message.error('Отсутствует E-mail');
        result = false;
      }
      if (!this.input_bank) {
        this.$message.error('Отсутствует банк');
        result = false;
      }
      if (!this.input_bik) {
        this.$message.error('Отсутствует бик');
        result = false;
      }
      if (!this.input_kor_account) {
        this.$message.error('Отсутствует Кор. счет');
        result = false;
      }
      if (!this.input_tin) {
        this.$message.error('Отсутствует ИНН');
        result = false;
      }
      if (!this.input_trrc) {
        this.$message.error('Отсутствует БИК');
        result = false;
      }
      if (!this.input_rach_account) {
        this.$message.error('Отсутствует Рас. счет');
        result = false;
      }
      if (!this.input_file) {
        this.$message.error('Отсутствует аватар');
        result = false;
      }

      return result;
    },
    async saveCreateCompanies() {
      if (this.validation()) {
        const loading = this.$loading({
          lock: true,
          text: 'Loading',
          spinner: 'el-icon-loading',
          background: 'rgba(0, 0, 0, 0.2)',
        });
        const formData = new FormData();
        formData.append('user', this.input_user);
        formData.append('fio', this.input_fio);
        formData.append('cities', this.input_cities);
        formData.append('address', this.input_address);
        formData.append('email', this.input_email);
        formData.append('phone', this.input_phone);
        formData.append('bank', this.input_bank);
        formData.append('bik', this.input_bik);
        formData.append('kor_account', this.input_kor_account);
        formData.append('tin', this.input_tin);
        formData.append('trrc', this.input_trrc);
        formData.append('rach_account', this.input_rach_account);
        formData.append('name', this.input_name);
        formData.append('delivery_address', this.input_delivery_address);
        formData.append('file', this.input_file);

        try {
          const { id } = await createCompanies(formData);
          loading.close();
          if (id > 0) {
            this.$router.push('/companies/edit/' + id);
          }
        } catch (error) {
          console.log(error);
          loading.close();
        }
      }
    },
    turnBack() {
      this.$router.push('/companies/table');
    },
    httpRequest(data) {
      this.input_image = URL.createObjectURL(data.file);
      this.input_file = data.file;
    },
    beforeAvatarUpload(file) {
      let isJPG = false;

      switch (file.type) {
        case 'image/jpeg':
        case 'image/jpg':
        case 'image/png':
          isJPG = true;
          break;
        default:
          isJPG = false;
          break;
      }

      const isLt2M = file.size / 1024 / 1024 < 2;

      if (!isJPG) {
        this.$message.error('Неверный формат картинки');
      }
      if (!isLt2M) {
        this.$message.error('Картинка не должна превышать 2MB!');
      }
      return isJPG && isLt2M;
    },
  },
};
</script>

<style scoped>
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
  line-height: 178px !important;
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
.el-select,.el-input {
  width: 292px !important;
}
</style>
