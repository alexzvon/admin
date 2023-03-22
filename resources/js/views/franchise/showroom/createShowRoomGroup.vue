<template>
  <el-container>
    <el-header>
      <h3>Новая группа</h3>
    </el-header>
    <el-main>
      <el-row type="flex" justify="start" align="middle">
        <div class="el-input-group__prepend">
          <div>Регион</div>
        </div>
        <el-select v-model="region_id" placeholder="Поиск" filterable>
          <el-option
            v-for="item in options"
            :key="item.id"
            :label="item.name_full"
            :value="item.id">
          </el-option>
        </el-select>
      </el-row>
      <el-row>
        <el-input v-model="name">
          <template slot="prepend" class="el-input-template_prepend">Наименование</template>
        </el-input>
      </el-row>
      <el-row>
        <el-input v-model="name_vin">
          <template slot="prepend" class="el-input-template_prepend">Наименование (вин)</template>
        </el-input>
      </el-row>
    </el-main>
    <el-footer>
      <el-row>
        <el-button @click="createGroup" type="primary" round size="mini">Создать</el-button>
        <el-button @click="routeBack" type="info" round size="mini">Группы</el-button>
      </el-row>
    </el-footer>
  </el-container>
</template>

<script>
import { createShowRoomGroup, getListRegionsTop } from '@/api/showroom';

export default {
  name: 'createShowRoomGroup',
  data: () => ({
    name: '',
    name_vin: '',
    region_id: '',
    options: []
  }),
  created() {
    this.getListRegions();
  },
  methods: {
    async getListRegions() {
      try {
        const { data } = await getListRegionsTop();
        this.options = data;
      }
      catch(error) {
        console.log(error);
      }
    },

    validation() {
      let result = true;

      if (!this.region_id) {
        this.$message.error('Выберите Регион');
        result = false;
      }
      if (!this.name) {
        this.$message.error('Введите наименование');
        result = false;
      }
      if (!this.name_vin) {
        this.$message.error('Введите наименование в винительном падеже');
        result = false;
      }

      return result;
    },

    async createGroup() {
      if (this.validation()) {
        try {
          const { id } = await createShowRoomGroup({
            name: this.name,
            name_vin: this.name_vin,
            region_id: this.region_id,
          });

          if (0 < id) {
            this.$router.push('/showroomgroup/edit/' + id);
          }
        } catch (error) {
          console.log(error);
        }
      }
    },

    routeBack() {
      this.$router.push('/showroomgroup/table/');
    },
  }
}
</script>

<style scoped lang="scss">
.el-main {
  width: 96%;
}
.el-select {
  width: 80%;
}
.el-input {
  width: 100%;
}
.el-footer {
  .el-row {
    text-align: center;
  }
}
.el-input-group__prepend {
  width: 20%;
  color: black;
  height: 40px;
  display: flex;
  align-items: center;
}
</style>

<style lang="scss">
.el-select {
  .el-input--suffix {
    width: 100%;
  }
}
.el-input {
  .el-input-group__prepend {
    width: 20%;
    color: black;
  }
}
</style>