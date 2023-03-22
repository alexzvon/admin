<template>
  <el-container>
    <el-header>
      <el-row>
        <el-col :span="12">
          <div class="header-left">
          <h3 class="h3">Список групп</h3>
          </div>
        </el-col>
        <el-col :span="12">
          <div class="header-right">
            <el-button @click="createGroup" size="mini" type="primary" round>Создать группу</el-button>
          </div>
        </el-col>
      </el-row>
    </el-header>
    <el-main>
      <el-table
        :data="tableData"
        height="75vh"
        style="width: 100%"
      >
        <el-table-column
          prop="id"
          label="Id"
          width="60"
        >
        </el-table-column>
        <el-table-column
          prop="region"
          label="Регион"
        >
        </el-table-column>
        <el-table-column
          prop="name"
          label="Группа"
        >
        </el-table-column>
        <el-table-column
          prop="name_vin"
          label="Наим. (вин)"
        >
        </el-table-column>
        <el-table-column
          prop="status"
          label="Статус"
        >
        </el-table-column>
        <el-table-column
          label="Операции"
          width="280"
        >
          <template slot-scope="scope">
            <el-button
              size="mini"
              type="primary"
              plain
              @click="handleEditShowRoomGroup(scope.$index, scope.row)">Изменить</el-button>
            <el-button
              size="mini"
              type="primary"
              plain
              @click="handleTableShowRoom(scope.$index, scope.row)">Комнаты</el-button>
          </template>
        </el-table-column>
      </el-table>
    </el-main>
  </el-container>
</template>

<script>
import { getListShowRoomGroup } from '@/api/showroom';

export default {
  name: "tableShowRoomGroup",
  data: () => ({
    tableData: [],
  }),
  created() {
    this.getListGroup();
  },
  methods: {
    async getListGroup() {
      try {
        const { data } = await getListShowRoomGroup();
        this.tableData = data;
      }
      catch(error) {
        console.log(error);
      }
    },
    createGroup() {
      this.$router.push('/showroomgroup/create/');
    },
    handleEditShowRoomGroup(index, row) {
      this.$router.push('/showroomgroup/edit/' + row.id);
    },
    handleTableShowRoom(index, row) {
      this.$router.push('/showroom/table/' + row.id);
    }
  }
}
</script>

<style scoped lang="scss">
.h3 {
  margin: 0;
}
.header-left {
  text-align: left;
}
.header-right {
  text-align: right;
}
.el-main {
  width: 96%;
}
.el-input {
  width: 100%;
}
</style>

<style lang="scss">

</style>