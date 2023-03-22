<template>
  <el-container>
    <el-header>
      <h3>Создать комнату группы: {{ name_group }}</h3>
    </el-header>
    <el-main>
      <el-row type="flex" justify="start" align="middle">
        <div class="el-input-group__prepend">
          <div>Город присутствия</div>
        </div>
        <el-select v-model="cities_id" placeholder="Поиск" filterable>
          <el-option
            v-for="item in options"
            :key="item.id"
            :label="item.name"
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
        <el-input v-model="address">
          <template slot="prepend" class="el-input-template_prepend">Адрес</template>
        </el-input>
      </el-row>
      <el-row>
        <el-input v-model="work_time">
          <template slot="prepend" class="el-input-template_prepend">Время работы</template>
        </el-input>
      </el-row>
      <el-row>
        <el-input v-model="phone">
          <template slot="prepend" class="el-input-template_prepend">Телефон</template>
        </el-input>
      </el-row>
      <el-row>
        <el-input v-model="email">
          <template slot="prepend" class="el-input-template_prepend">Почта</template>
        </el-input>
      </el-row>
    </el-main>
    <el-footer>
      <el-row>
        <el-button @click="createRoom" type="primary" round size="mini">Создать</el-button>
        <el-button @click="routeBack" type="info" round size="mini">Комнаты</el-button>
      </el-row>
    </el-footer>
  </el-container>
</template>

<script>
import { showShowRoomGroup, getListCitiesTopRegion, createRoom } from '@/api/showroom';

export default {
  name: "createShowRoom",
  data: () => ({
    group_id: 0,
    name_group: '',
    region_id: 0,
    cities_id: '',
    options: [],
    name: '',
    address: '',
    work_time: '',
    phone: '',
    email: '',
  }),
  created() {
    this.group_id = this.$route.params.id_group;
    if (0 < this.group_id) {
      this.getGroup();
    }
  },
  methods: {
    async createRoom() {
      try {
        const { id } = await createRoom({
          group_id: this.group_id,
          cities_id: this.cities_id,
          name: this.name,
          address: this.address,
          work_time: this.work_time,
          phone: this.phone,
          email: this.email,
        });

        this.$router.push('/showroom/edit/' + id);
      }
      catch(error) {
        console.log(error);
      }
    },

    routeBack() {
      this.$router.push('/showroom/table/' + this.group_id);
    },

    async getCities() {
      try {
        const { data } = await getListCitiesTopRegion(this.region_id);
        this.options = data;
      }
      catch(error) {
        console.log(error);
      }
    },

    async getGroup() {
      try {
        const { data } = await showShowRoomGroup(this.group_id);
        this.name_group = data.name;
        this.region_id = data.region_id;

        this.getCities();
      }
      catch (error) {
        console.log(error);
      }
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
.el-input-group__prepend {
  width: 20%;
  color: black;
  height: 40px;
  display: flex;
  align-items: center;
}
.el-footer {
  .el-row {
    text-align: center;
  }
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