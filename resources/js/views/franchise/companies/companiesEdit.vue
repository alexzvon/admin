<template>
  <el-container>
    <el-header height>
      <div class="info-reply">
        <span class="h2">Редактирование Учётной записи Партнёра № {{ companies_id }}</span>
        <div>
          <el-button size="small" type="primary" @click="saveUpdateCompanies">Сохранить</el-button>
          <el-button size="small" @click="turnBack">Назад</el-button>
        </div>
      </div>
    </el-header>
    <el-header height>
      <el-radio v-model="input_status" class="header_radio" label="true">Вкл.</el-radio>
      <el-radio v-model="input_status" class="header_radio" label="false">Выкл.</el-radio>
    </el-header>
    <el-row>
      <el-col :span="12">
        <el-main style="width: auto; padding: 0;">
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
            <el-select v-model="input_cities" placeholder="Select" @change="changeCity">
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
          <span class="h2">RetailCRM ID*</span>
            <el-input
              v-model="input_retail_crm_id"
              placeholder="Please input"
              clearable
            />
          </div>
          <div class="info-reply">
            <span class="h2">Аватар</span>
            <img class="avatar" :src="input_image">
          </div>
        </el-main>
      </el-col>
      <el-col :span="12" class="right-col">
        <el-main style="width: auto;">
          <div class="info-reply">
            <span class="h2">Источники дохода</span>
          </div>

          <div v-for="(item, idx) in input_income" :key="item.value" class="info-reply__right">
            <span class="h2">{{ item.label }}</span>
            <el-input-number
              v-model="input_income[idx].percent"
              class="el-input__number"
              controls-position="right"
              :min="1"
              :max="100"
            />
            <i class="fa fa-percent icon-icome__prcent" />
            <el-button size="small" class="button-income__delete" @click="deleteInputIncome(idx)">Удалить</el-button>
          </div>

          <div class="info-reply">
            <el-select v-model="input_select_income" placeholder="Select" @change="changeAddPercent">
              <el-option
                v-for="item in select_income"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>

            <el-input-number
              v-model="input_add_percent"
              class="el-input__number"
              controls-position="right"
              :min="0"
              :max="100"
            />
            <i class="fa fa-percent info__i" />
            <el-button size="small" type="primary" @click="addIncome">Добавить</el-button>
          </div>

          <el-divider />

          <div class="info-reply__left">
            <span class="h2">Надбавка в вашем городе</span>
            <el-input
              v-model="input_percent"
              class="el_input_percent"
              placeholder="Please input"
              :disabled="true"
            />
            <i class="fa fa-percent" />
          </div>

        </el-main>
      </el-col>
    </el-row>
  </el-container>
</template>

<script>

import {
    getListCity,
    getCompany,
//    getListUsers,
    getListIncome,
    updateCompanies,
//    getListFranchiseUsers,
} from '../../../api/companies';

import { getListFranchiseUsersNotCompanies } from '../../../api/income';

export default {
  name: 'CompaniesEdit',
  data() {
    return {
      loading: false,

      input_image: '',
      input_rach_account: '',
      input_trrc: '',
      input_tin: '',
      input_kor_account: '',
      input_bik: '',
      input_bank: '',
      input_phone: '',
      input_email: '',
      input_address: '',
      input_cities: '',
      input_fio: '',
      input_user: '',
      input_name: '',
      input_delivery_address: '',
      input_status: '',
      input_percent: '',
      input_add_percent: '',
      input_retail_crm_id: '',

      input_income: [],
      select_income: [],
      input_select_income: '',
      template_income: [],

      companies_id: 0,

      options: [],
      list: [],
      users: [],
    };
  },
  created() {
    this.companies_id = this.$route.params.id;
    this.getListIncomeToAdd();
    this.getCompanyEdit();
    this.getFranchiseListCity();
//    this.getListUsersFilter();
  },
  methods: {
    deleteInputIncome(idx) {
      this.input_income.splice(idx, 1);
      this.decodeTemplateSelectIncome();
    },
    decodeTemplateSelectIncome() {
      let is = false;
      this.select_income = [];

      if (this.template_income.length > 0) {
        for (let i = 0; i < this.template_income.length; i++) {
          is = false;
          for (let j = 0; j < this.input_income.length; j++) {
            if (this.template_income[i].value == this.input_income[j].value) {
              is = true;
              break;
            }
          }
          if (!is) {
            this.select_income.push(this.template_income[i]);
          }
        }
      }
    },
    addIncome(){
      if (!this.input_select_income) {
        this.$message.error('Не выбран источник дохода');
      }

      for (let i = 0; i < this.select_income.length; i++) {
        if (this.select_income[i].value == this.input_select_income) {
          this.select_income[i].percent = this.input_add_percent;
          this.input_income.push(this.select_income[i]);
          this.decodeTemplateSelectIncome();
          this.input_select_income = '';
          this.input_add_percent = 0;
        }
      }
    },
    async getListIncomeToAdd() {
      try {
        const { data } = await getListIncome();
        this.template_income = data;
      } catch (error) {
        console.log(error);
      }
    },
    changeAddPercent(item){
      for (let i = 0; i < this.select_income.length; i++) {
        if (this.select_income[i].value == item){
          this.input_add_percent = this.select_income[i].percent;
          break;
        }
      }
    },
    changeCity(item) {
      for (let i = 0; i < this.options.length; i++) {
        if (this.options[i].value == item) {
          this.input_percent = this.options[i].percent;
          break;
        }
      }
    },
    async getCompanyEdit() {
      const loading = this.$loading({
        lock: true,
        text: 'Loading',
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.1)',
      });
      try {
        const { data } = await getCompany(this.companies_id);

        this.input_image = data.input_image;
        this.input_rach_account = data.input_rach_account;
        this.input_trrc = data.input_trrc;
        this.input_tin = data.input_tin;
        this.input_kor_account = data.input_kor_account;
        this.input_bik = data.input_bik;
        this.input_bank = data.input_bank;
        this.input_phone = data.input_phone;
        this.input_email = data.input_email;
        this.input_address = data.input_address;
        this.input_percent = data.input_percent;
        this.input_cities = data.input_cities;
        this.input_fio = data.input_fio;
        this.input_user = data.input_user;
        this.input_status = data.input_status.toString();
        this.input_income = data.input_income;
        this.input_name = data.input_name;
        this.input_delivery_address = data.input_delivery_address;
        this.input_retail_crm_id = data.input_retail_crm_id;

        this.decodeTemplateSelectIncome();

        this.getListUsersFilter();

        loading.close();
      } catch (error) {
        loading.close();
        this.$message.error(error);
        console.log(error);
      }
    },
    async getListUsersFilter() {
        const loading = this.$loading({
            lock: true,
            text: 'Loading',
            spinner: 'el-icon-loading',
            background: 'rgba(0, 0, 0, 0.1)',
        });

        try {
            const { data } = await getListFranchiseUsersNotCompanies(this.input_user);    // getListUsers();
            this.users = data;
            this.list = this.users.map(item => {
              return { value: item.id, label: item.first_name + ' ' + item.last_name + ' ' + item.email };
            });
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

    saveValidation() {
      let result = false;

      if (!this.input_retail_crm_id || 0 === this.input_retail_crm_id.length) {
        this.$message.error('RetailCRM ID - обязательное поле и не может быть пустым');
        result = true;
      }

      return result;

    },

    async saveUpdateCompanies() {
        if (this.saveValidation()) {
            return;
        }

      const loading = this.$loading({
        lock: true,
        text: 'Loading',
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.1)',
      });

      const input_income = [];

      for (let i = 0; i < this.input_income.length; i++) {
        input_income[i] = {
          id: this.input_income[i].value,
          percent: this.input_income[i].percent,
          type: this.input_income[i].type,
        };
      }

      const data = {
        input_rach_account: this.input_rach_account,
        input_trrc: this.input_trrc,
        input_tin: this.input_tin,
        input_kor_account: this.input_kor_account,
        input_bik: this.input_bik,
        input_bank: this.input_bank,
        input_phone: this.input_phone,
        input_email: this.input_email,
        input_address: this.input_address,
        input_percent: this.input_percent,
        input_cities: this.input_cities,
        input_fio: this.input_fio,
        input_user: this.input_user,
        input_status: this.input_status,
        input_income: input_income,
        input_name: this.input_name,
        input_delivery_address: this.input_delivery_address,
        input_retail_crm_id: this.input_retail_crm_id,
      };

      try {
        const success = await updateCompanies(this.companies_id, data);
        loading.close();
      } catch (error) {
        loading.close();
        console.log(error);
      }
    },
    turnBack() {
      this.$router.push('/companies/table');
    },
  },
};
</script>

<style lang="scss">
.info-reply {
  .el-select {
    .el-input, .el-input--suffix {
      width: 292px;
    }
  }
}
.right-col {
  .info-reply {
    .el-select {
      .el-input, .el-input--suffix {
        width: 232px;
      }
    }
  }
  .el-input-number,.is-controls-right {
    .el-input__inner {
      padding-left: 0;
      padding-right: 40px;
    }
  }
}
</style>

<style scoped lang="scss">
.info-reply {
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 1px 4px -2px rgba(0, 0, 0, 0.55);
  margin-bottom: 10px;
  .info__i {
    margin: 0 10px;
  }
  .el-input,.el-input--suffix {
    width: 292px;
  }
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
  box-shadow: 0 1px 4px -2px rgba(0, 0, 0, 0.55);
}
//.el-input,.el-input--suffix {
//  width: 292px !important;
//}
.info-reply__right {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  box-shadow: 0 1px 4px -2px rgba(0, 0, 0, 0.55);
  margin-bottom: 10px;

  .button-income__delete {
    margin-left: 18px;
  }

  .icon-icome__prcent {
    margin-left: 11px;
  }

  .el-input__number {
    width: 83px;
    margin-left: 5px;
  }

  .h2 {
    width: 225px;
  }
}
.info-reply__left {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  box-shadow: 0 1px 4px -2px rgba(0, 0, 0, 0.55);
  margin-bottom: 10px;

  .h2 {
    width: 265px;
  }

  i {
    margin: 0 10px;
  }
}
.el_input_percent {
  width: 50px !important;
}
.el-input__number {
  width: 105px;

}

</style>
