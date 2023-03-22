<template>
  <el-container>
    <el-header>
      <h3>Изменить группу</h3>
    </el-header>
    <el-main>
      <el-row>
        <el-input v-model="id" readonly>
          <template slot="prepend" class="el-input-template_prepend">Id</template>
        </el-input>
      </el-row>
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
      <el-row type="flex" justify="start" align="middle">
        <div class="el-input-group__prepend">
          <div>Статус</div>
        </div>
        <el-switch v-model="status"></el-switch>
      </el-row>
    </el-main>
    <el-footer>
      <el-row>
        <el-button @click="updateGroup" type="primary" round size="mini">Изменить</el-button>
        <el-button @click="routeBack" type="info" round size="mini">Группы</el-button>
      </el-row>
    </el-footer>
  </el-container>
</template>

<script>
import { showShowRoomGroup, updateShowRoomGroup, getListRegionsTop } from '@/api/showroom';

export default {
  name: 'editShowRoomGroup',
  data: () => ({
    id: 0,
    name: '',
    name_vin: '',
    status: true,
    region_id: '',
    options: [],
  }),
  created() {
    this.id = this.$route.params.id;
    if (0 < this.id) {
      this.getListRegions();
      this.loadGroup();
    }
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

    async loadGroup() {
      try {
        const { data } = await showShowRoomGroup(this.id);
        this.region_id = data.region_id;
        this.name = data.name;
        this.name_vin = data.name_vin;
        this.status = data.status;
      }
      catch (error) {
        console.log(error);
      }
    },
    async updateGroup() {
      try {
        const { success } = await updateShowRoomGroup(
          this.id,
          {
            region_id: this.region_id,
            name: this.name,
            name_vin: this.name_vin,
            status: this.status
          });
        if (!success) {
          this.$message.error('Ошибка изменения записи');
        }
      }
      catch (error) {
        console.log(error);
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
.el-switch {
  margin-left: 20px;
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