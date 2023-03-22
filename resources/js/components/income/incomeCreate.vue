<template>
  <el-container>
    <el-header height>
      <div class="info-reply">
        <span class=h2>Создать источник дохода</span>
        <div>
          <el-button size="small" type="primary" @click="saveCreateIncome">Сохранить</el-button>
          <el-button size="small" @click="turnBack">Назад</el-button>
        </div>
      </div>
    </el-header>
    <el-main style="width:50%;">

      <div class="info-reply">
        <span class=h2>Тип</span>
        <el-select v-model="input_type" placeholder="Select">
          <el-option
            v-for="item in type_incom"
            :key="item.label"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
      </div>

      <div class="info-reply">
        <span class=h2>Название</span>
        <el-input
          placeholder="Please input"
          v-model="input_name"
          clearable>
        </el-input>
      </div>

      <div class="info-reply">
        <span class=h2>Процент</span>
        <el-input-number v-model="input_percent" :min="0" :max="100"></el-input-number>
      </div>

    </el-main>
  </el-container>

</template>

<script>

import { createIncome } from "../../api/income";

export default {
  name: "incomeCreate",
  data() {
    return {
      type_incom: [
        {
          value: 'FranchiseClientIndividually',
          label: 'Индивидуальный',
        },
        {
          value: 'FranchiseClientDesigner',
          label: 'Дизайнер',
        },
        {
          value: 'FranchiseClientEntity',
          label: 'Компания',
        },
      ],
      input_type: 'FranchiseClientIndividually',
      input_name: '',
      input_percent: 0,
    };
  },
  methods: {
    validation() {
      let result = true;

      if (!this.input_name) {
        this.$message.error('Отсутствует название источника дохода');
        result = false;
      }

      if (!this.input_type) {
        this.$message.error('Отсутствует тип источника дохода');
        result = false;
      }

      return result;
    },

    turnBack() {
      this.$router.push('/income/table');
    },

    async saveCreateIncome() {
      if (this.validation()) {
        const loading = this.$loading({
          lock: true,
          text: 'Loading',
          spinner: 'el-icon-loading',
          background: 'rgba(0, 0, 0, 0.1)'
        });

        try{
          const { id } = await createIncome({
            'name': this.input_name,
            'percent': this.input_percent,
            'type': this.input_type,
          });
          loading.close();
          if (id > 0) {
            this.$router.push('/income/edit/' + id);
          }
        }
        catch(error) {
          console.log(error);
          loading.close();
        }
      }
    },
  },
}
</script>

<style>
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
  width: 100%;
}
</style>