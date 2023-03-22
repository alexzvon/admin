<template>
<div>
  <el-row>
    <el-col :span="12">
      <el-card>
        <div class="info-reply">
          <span class="h2">Название товара</span>
          <el-input v-model="phone" placeholder="Please input" clearable/>
        </div>
        <div class="info-reply">
          <span class="h2">Категория</span>
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
          <span class="h2">Коллекция</span>
          <el-input v-model="phone" placeholder="Please input" clearable/>
        </div>
        <div class="info-reply">
          <span class="h2">Поставщики</span>
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
          <span class="h2">Бренд</span>
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
        <div class="info-reply">
          <span class="h2">ID Поставщика</span>
          <el-input v-model="phone" placeholder="Please input" clearable/>
        </div>
        <div class="info-reply">
          <span class="h2">GTIN</span>
          <el-input v-model="phone" placeholder="Please input" clearable/>
        </div>
        <div class="info-reply">
          <span class="h2">Артикл площадки</span>
          <el-input v-model="phone" placeholder="Please input" clearable/>
        </div>
        <div class="info-reply">
          <span class="h2">Slag</span>
          <el-input v-model="phone" placeholder="Please input" clearable/>
        </div>
        <div class="info-reply">
          <span class="h2">Артикл 1С</span>
          <el-input v-model="phone" placeholder="Please input" clearable/>
        </div>
      </el-card>
    </el-col>
  </el-row>
</div>
</template>

<script>
export default {
  name: "general",
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