<template>
  <el-row>
    <el-col :span="12">
      <el-card>
        <div class="info-reply">
          <span class="h2">Описание</span>
          <el-input
            type="textarea"
            :rows="2"
            placeholder="Please input"
            v-model="textarea">
          </el-input>
        </div>
        <div class="info-reply">
          <span class="h2">Страна</span>
          <el-select v-model="value1" multiple placeholder="Select">
            <el-option
              v-for="item in options"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </div>
        <div class="info-reply">
          <span class="h2">Гарантии</span>
          <el-select v-model="value1" multiple placeholder="Select">
            <el-option
              v-for="item in options"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </div>
        <div class="info-reply">
          <span class="h2">Стиль</span>
          <el-select v-model="value1" multiple placeholder="Select">
            <el-option
              v-for="item in options"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </div>
        <div class="info-reply">
          <span class="h2">Комната</span>
          <el-select v-model="value1" multiple placeholder="Select">
            <el-option
              v-for="item in options"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </div>
      </el-card>
    </el-col>
    <el-col :span="12">
      <el-card>
        <el-divider content-position="left">В собраном виде</el-divider>
        <div class="info-reply">
          <span class="h2">Ширина</span>
          <el-input v-model="phone" placeholder="Please input" clearable/>
        </div>
        <div class="info-reply">
          <span class="h2">Высота</span>
          <el-input v-model="phone" placeholder="Please input" clearable/>
        </div>
        <div class="info-reply">
          <span class="h2">Длинна</span>
          <el-input v-model="phone" placeholder="Please input" clearable/>
        </div>
        <div class="info-reply">
          <span class="h2">Вес</span>
          <el-input v-model="phone" placeholder="Please input" clearable/>
        </div>
        <el-button type="primary" :loading="loading">Loading</el-button>
      </el-card>
    </el-col>
  </el-row>
</template>

<script>
export default {
  name: "properties",
  data() {
    return {
      options: [{
        value: 'Option1',
        label: 'Option1'
      }, {
        value: 'Option2',
        label: 'Option2'
      }, {
        value: 'Option3',
        label: 'Option3'
      }, {
        value: 'Option4',
        label: 'Option4'
      }, {
        value: 'Option5',
        label: 'Option5'
      }],
      value1: '',
      phone: '',
      loading: true,
    };
  },
}
</script>

<style lang="scss">
.el-select {
  div.el-input.el-input--suffix {
    width: 320px;
  }
}
</style>

<style scoped lang="scss">
span {
  font-size: 25px;
}
.info-reply {
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 1px 4px -2px rgba(0, 0, 0, 0.55);
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
</style>