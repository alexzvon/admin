<template>
  <el-container>
    <el-header>
      <el-row type="flex" justify="space-between" align="middle">
        <h3 class="h3">Список комнат группы "{{ name_group }}"</h3>
        <div>
          <el-button @click="createRoom" size="mini" type="primary" round>Создать комнату</el-button>
          <el-button @click="routeBack" size="mini" type="info" round>Группы</el-button>
        </div>
      </el-row>
    </el-header>
    <el-main>
      <el-table
        :data="tableData"
        height="75vh"
        style="width: 100%"
        @row-dblclick="rowDblClick"
      >
        <el-table-column
          prop="id"
          label="Id"
          width="60"
        >
        </el-table-column>
        <el-table-column
          prop="name"
          label="Наименование"
          width="180"
        >
        </el-table-column>
        <el-table-column
          prop="cities"
          label="Город"
        >
        </el-table-column>
        <el-table-column
          prop="address"
          label="Адрес"
        >
        </el-table-column>
        <el-table-column
          prop="status"
          label="Статус"
          width="120"
        >
        </el-table-column>
      </el-table>
    </el-main>
  </el-container>
</template>

<script>
import { showShowRoomGroup, getListRooms } from '@/api/showroom';

export default {
  name: "tableShowRoom",
  data: () => ({
    group_id: 0,
    name_group: '',
    id: 0,
    name: 'Неизвестный',
    tableData: [],
  }),
  created() {
    this.group_id = this.$route.params.id_group;
    if (0 < this.group_id) {
      this.getGroup();
      this.getRooms();
    }
  },
  methods: {
    rowDblClick(row, column, event) {
      event.stopPropagation();
      this.$router.push('/showroom/edit/' + row.id);
    },

    async getRooms() {
      try {
        const { data } = await getListRooms(this.group_id);
        this.tableData = data;
      }
      catch (error) {
        console.log(error);
      }
    },

    routeBack() {
      this.$router.push('/showroomgroup/table/');
    },

    async getGroup() {
      try {
        const { data } = await showShowRoomGroup(this.group_id);
        this.name_group = data.name;
      }
      catch (error) {
        console.log(error);
      }
    },

    createRoom() {
      this.$router.push('/showroom/create/' + this.group_id);
    },
    // createShowRoomGroup() {
    //   this.$router.push('/showroomgroup/create/');
    // },
    // editShowRoomGroup() {
    //   this.$router.push('/showroomgroup/edit/1');
    // },
    // tableShowRoomGroup() {
    //   this.$router.push('/showroomgroup/table/');
    // }
  }
}
</script>

<style scoped lang="scss">
.el-main {
  width: 100%;
}

</style>