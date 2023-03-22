<template>
  <div>
    <div>
        <el-upload
          class="upload-demo"
          action=""
          :before-upload="beforeAvatarUpload"
        >
        <el-button size="small" type="primary">Добавить загрузку</el-button>
        <div slot="tip" class="el-upload__tip">Файл должкн быть типа officedocument.spreadsheetml.sheet</div>
      </el-upload>
    </div>
    <br>

    <el-table
      :data="tableData"
      style="width: 100%">
      <el-table-column type="expand" show-summary="true">
        <template slot-scope="props">

          <el-row v-if="(props.row.status == 1 && props.row.operation == 0)">
            <el-tooltip content="Загрузка" placement="bottom" effect="light">
              <el-button type="primary" icon="el-icon-video-play" circle class="rule_loader"
                         @click="loaderStart(props.row.id)"></el-button>
            </el-tooltip>
            <el-tooltip content="Удалить" placement="bottom" effect="light">
              <el-button type="danger" icon="el-icon-delete" circle class="rule_loader"
                         @click="loaderRemote(props.row.id)"></el-button>
            </el-tooltip>
          </el-row>

          <el-row v-else-if="(props.row.status == 1 && props.row.operation == 1)">
            <div style="margin-bottom: 10px;">
              <el-tooltip content="Останов" placement="bottom" effect="light">
                <el-button type="warning" icon="el-icon-video-pause" circle class="rule_loader"
                           @click="loaderStop(props.row.id)"></el-button>
              </el-tooltip>
              <el-tag>Обработано: {{ props.row.last_row}}</el-tag>
            </div>
            <div>
              <el-progress :text-inside="true" :stroke-width="26" :percentage="props.row.processed"></el-progress>
            </div>
          </el-row>

<!--          <el-row v-if="(props.row.status == 1 && props.row.operation == 0)">-->
<!--            <el-tooltip content="Старт" placement="bottom" effect="light">-->
<!--              <el-button type="primary" icon="el-icon-video-play" circle class="rule_loader"-->
<!--                         @click="loaderStart(props.row.id)"></el-button>-->
<!--            </el-tooltip>-->
<!--            <el-tooltip content="Останов" placement="bottom" effect="light">-->
<!--              <el-button type="success" icon="el-icon-video-pause" circle class="rule_loader"-->
<!--                         @click="loaderPause(props.row.id)"></el-button>-->
<!--            </el-tooltip>-->
<!--            <el-tooltip content="Продолжить" placement="bottom" effect="light">-->
<!--              <el-button type="info" icon="el-icon-switch-button" circle class="rule_loader"-->
<!--                         @click="loaderProceed(props.row.id)"></el-button>-->
<!--            </el-tooltip>-->
<!--            <el-tooltip content="Откат" placement="bottom" effect="light">-->
<!--              <el-button type="warning" icon="el-icon-finished" circle class="rule_loader"-->
<!--                         @click="loaderStop(props.row.id)"></el-button>-->
<!--            </el-tooltip>-->
<!--            <el-tooltip content="Удалить" placement="bottom" effect="light">-->
<!--              <el-button type="danger" icon="el-icon-delete" circle class="rule_loader"-->
<!--                         @click="loaderRemote(props.row.id)"></el-button>-->
<!--            </el-tooltip>-->
<!--          </el-row>-->

          <el-row v-else-if="(props.row.status == 4 && props.row.operation == 0) || props.row.total_row == 0">
            <el-tooltip content="Перегрузить" placement="bottom" effect="light">
              <el-button type="primary" icon="el-icon-video-play" circle class="rule_loader"
                         @click="loaderReload(props.row.id)"></el-button>
            </el-tooltip>
            <el-tooltip content="Удалить" placement="bottom" effect="light">
              <el-button type="danger" icon="el-icon-delete" circle class="rule_loader"
                         @click="loaderRemote(props.row.id)"></el-button>
            </el-tooltip>
            <el-tag>Обработано: {{ props.row.last_row}}</el-tag>
          </el-row>

          <el-row v-else-if="(props.row.status == 3 && props.row.operation == 0)">
            <el-tooltip content="Откат" placement="bottom" effect="light">
              <el-button type="warning" icon="el-icon-finished" circle class="rule_loader"
                         @click="loaderBack(props.row.id)"></el-button>
            </el-tooltip>
            <el-tooltip content="Удалить" placement="bottom" effect="light">
              <el-button type="danger" icon="el-icon-delete" circle class="rule_loader"
                         @click="loaderRemote(props.row.id)"></el-button>
            </el-tooltip>
            <el-tag>Обработано: {{ props.row.last_row}}</el-tag>
          </el-row>

          <el-row v-else-if="(props.row.status == 1 && props.row.operation == 4)">
            <div style="margin-bottom: 10px;">
              <el-tooltip content="Продолжить" placement="bottom" effect="light">
                <el-button type="warning" icon="el-icon-switch-button" circle class="rule_loader"
                           @click="loaderStart(props.row.id)"></el-button>
              </el-tooltip>
              <el-tooltip content="Откат" placement="bottom" effect="light">
                <el-button type="warning" icon="el-icon-finished" circle class="rule_loader"
                           @click="loaderBack(props.row.id)"></el-button>
              </el-tooltip>
              <el-tooltip content="Удалить" placement="bottom" effect="light">
                <el-button type="danger" icon="el-icon-delete" circle class="rule_loader"
                           @click="loaderRemote(props.row.id)"></el-button>
              </el-tooltip>
              <el-tag>Обработано: {{ props.row.last_row}}</el-tag>
            </div>
            <div>
              <el-progress :text-inside="true" :stroke-width="26" :percentage="props.row.processed"></el-progress>
            </div>
          </el-row>

          <el-row v-else>
            <p>Идет предварительная обработка</p>
          </el-row>

        </template>
      </el-table-column>
      <el-table-column width="75px"
        label="ID"
        prop="id">
      </el-table-column>
      <el-table-column
        label="Наименование"
        prop="name">
      </el-table-column>
      <el-table-column
        label="Пользователь"
        prop="admin">
      </el-table-column>
      <el-table-column
        label="Размер"
        prop="size">
      </el-table-column>
      <el-table-column
        label="Всего строк"
        prop="total_row">
      </el-table-column>
      <el-table-column
        label="Файл"
        prop="status_mes">
      </el-table-column>
      <el-table-column
        label="Операция"
        prop="operation_mes">
      </el-table-column>
    </el-table>

<!--    <el-button type="danger" icon="el-icon-delete" circle class="rule_loader" @click="loadService"></el-button>-->

  </div>
</template>

<script>

//import { getList, load, start, stop, pause, proceed, remote, reload, back } from "../../api/loader_products";
//import { getList, load, start, stop, pause, proceed, remote, reload, back, test } from "../../api/service";
import { getList, load, start, stop, pause, proceed, remote, reload, back } from "../../api/loader_service";
import { objectLoader } from "./objectLoader";
import Core from "../../core";

//import { load_service } from "../../api/service";

export default {
  name: "products",
  data() {
    return {
      pusher: '',
      tableData: [],
      fileList: [],
    }
  },
  created () {
    this.pusher = this.$pusher.subscribe(process.env.MIX_IMPORT_EXCEL_PUSHER);
    this.loaderGetList();
  },
  methods: {

    async loadService() {
      try {
        let data = 'aaaaa';     //await test();
        console.log(data);
      }
      catch (error) {
        console.log(error);
      }

      console.log('UserId = ' + Core.user.getId());
    },

    async loaderGetList() {
      const loading = this.$loading({
        lock: true,
        text: 'Загрузка',
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.2)',
      });
      try {
        const { data } = await getList();

        this.tableData = [];

        for (let i = 0; i < data.length; i++) {

          let object = new objectLoader();

          object.id = data[i]['id'];
          object.admin = data[i]['admin'];
          object.name = data[i]['name'];
          object.total_row = parseInt(data[i]['total_row']);
          object.size = data[i]['size'];
          object.header = data[i]['header'];

          object.operation = data[i]['operation'];
          object.status = data[i]['status'];

          object.status_mes = '';
          object.operation_mes = '';

          object.last_row = parseInt(data[i]['last_row']);
          object.processed = 0;

          switch (object.status) {
            case 1:
              object.status_mes = 'Загружен на диск';
              break;
            case 2:
              object.status_mes = 'Изменен на диске';
              break;
            case 3:
              object.status_mes = 'Загружен в БД';
              break;
            case 4:
              object.status_mes = 'Ошибка';
              break;
            default:
              object.status_mes = '';
              break;
          }

          switch (object.operation) {
            case 5:
              object.operation_mes = 'Загрузка на диск';
              object.changeTotalRow(
                this.pusher,
                process.env.MIX_IMPORT_EXCEL_CHANNEL,
                () => { this.loaderGetList(); },
                () => { this.$message.error('Ошибка предварительной загрузки.'); this.loaderGetList(); }
              );
              break;
            case 1:
              object.operation_mes = 'Загрузка в БД';
              object.processed = 0 === object.last_row ? 0 : parseInt(object.last_row / object.total_row * 100);
              object.changeProcessed(
                this.pusher,
                process.env.MIX_IMPORT_EXCEL_CHANNEL,
                () => { this.loaderGetList(); },
                () => { this.$message.error('Ошибка при загрузки в БД'); }
              );
              break;
            case 4:
              object.operation_mes = 'Останов';
              object.processed = 0 === object.last_row ? 0 : parseInt(object.last_row / object.total_row * 100);
              break;
            case 2:
                object.operation_mes = '';
                break;
            case 3:
              object.operation_mes = '';
              break;
            default:
              object.operation_mes = '';
              break;
          }

          this.tableData.push(object);
        }

        loading.close();
      }
      catch (error) {
        loading.close();
        console.log(error);
      }
    },

    beforeAvatarUpload(file) {
      if (file.type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
        this.loaderLoad(file);
      }
      else {
        this.$message.error('Не верный формат файла');
      }

      return false;
    },

    async loaderLoad(file) {
      const loading = this.$loading({
        lock: true,
        text: 'Загрузка',
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.2)',
      });

      try {
        const formData = new FormData();
        formData.append('file', file);
        const { model } = await load(formData);
        if (0 < model.id) {
          this.loaderGetList();
        }
        loading.close();
      }
      catch (error) {
        this.$message.error(error);
        loading.close();
      }
    },

    async loaderStart(id) {
      try {
        const { success } = await start(id);
        if (success) {
          this.loaderGetList();
        }
      }
      catch (error) {
        console.log('start = false');
      }
    },
    async loaderStop(id) {
      try {
        const { success } = await stop(id);
        if (success) {
//          this.loaderGetList();
        }
      }
      catch (error) {
        console.log('stop = false');
      }
    },
    async loaderPause(id) {
      try {
        const { success } = await pause(id);
        console.log('pause = ' + success );
      }
      catch (error) {
        console.log('pause = false');
      }
    },
    async loaderProceed(id) {
      try {
        const { success } = await proceed(id);
        console.log('proceed = ' + success );
      }
      catch (error) {
        console.log('proceed = false');
      }
    },
    async loaderRemote(id) {
      try {
        const { success } = await remote(id);
        console.log('remote = ' + success );
        this.loaderGetList();
      }
      catch (error) {
        console.log('remote = false');
      }
    },
    async loaderReload(id) {
      try {
        const { model } = await reload(id);
        console.log('reload = ' + model);
        this.loaderGetList();
      }
      catch(error) {
        console.log('reload = false');
      }
    },
    async loaderBack(id) {
      try {
        const { success } = await back(id);
        if (success) {
          this.loaderGetList();
        }
        console.log('stop = ' + success );
      }
      catch (error) {
        console.log('stop = false');
      }
    },
  }
}
</script>

<style>
.el-upload {
  text-align: left;
}
</style>

<style scoped lang="scss">
  .rule_loader {
    font-size: 25px;
    padding: 1px;
  }
  .upload-demo {
    line-height: 0.0px;
  }
</style>