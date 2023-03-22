<template>
  <el-container>
    <el-header height>
      <div class="info-reply">
        <span class=h2>Изменить быстрый фильтр</span>
        <div>
          <el-button size="small" type="primary" @click="saveUpdateQuickFilter">Сохранить</el-button>
          <el-button size="small" @click="turnBack">Назад</el-button>
        </div>
      </div>
    </el-header>
    <el-main style="width:50%;">

      <div class="info-reply">
        <span class=h2>Категория</span>
        <el-select v-model="input_category" placeholder="Select">
          <el-option
            v-for="item in options"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>
      </div>

      <div class="info-reply">
        <span class=h2>Наименование</span>
        <el-input
          placeholder="Please input"
          v-model="input_name"
          clearable>
        </el-input>
      </div>

      <div class="info-reply">
        <span class=h2>Отображаемое наименование</span>
        <el-input
          placeholder="Please input"
          v-model="input_displayed_name"
          clearable>
        </el-input>
      </div>

      <div class="info-reply">
        <span class=h2>Slug</span>
        <el-input
          placeholder="Please input"
          v-model="input_slug"
          @change="handleSlugChange"
          clearable>
        </el-input>
      </div>

      <div class="info-reply">
        <span class=h2>Адрес</span>
        <el-input
          placeholder="Please input"
          v-model="input_address"
          clearable>
        </el-input>
      </div>

      <div class="info-reply">
        <span class=h2>Картинка</span>
        <el-upload
          class="avatar-uploader"
          action=""
          :show-file-list="false"
          :disabled="false"
          :before-upload="beforeAvatarUpload"
          :http-request="httpRequest"
        >
          <img class="avatar" v-if="input_image" :src="input_image">
          <i v-else class="el-icon-plus avatar-uploader-icon"></i>
        </el-upload>
      </div>
      <div class="info-reply">
        <span class=h2>Статус</span>
        <el-switch v-model="input_status">
        </el-switch>
      </div>

    </el-main>
  </el-container>
</template>

<script>
import { getOptionsTree, getQuickFilter, updateQuickFilter } from '../../../api/quick_filter';

export default {
  name: 'QuickEditFilter',
  data() {
    return {
      input_name: '',
      input_displayed_name: '',
      input_slug: '',
      input_category: '',
      input_address: '',
      input_image: '',
      input_status: '',

      input_file: '',

      quick_filter_id: '',

      options: [],
    };
  },
  created() {
    this.quick_filter_id = this.$route.params.id;
    this.getOptionsTreeCategories();
    this.getUpdateQuickFilter();
  },

  methods: {
    async getUpdateQuickFilter() {
      const loading = this.$loading({
        lock: true,
        text: 'Loading',
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.2)',
      });
      try {
        const { data } = await getQuickFilter(this.quick_filter_id);

        this.input_name = data.name;
        this.input_displayed_name = data.displayed_name;
        this.input_slug = data.slug;
        this.input_category = data.category_id;
        this.input_address = data.address;
        this.input_status = data.status;
        this.input_image = data.file;

        loading.close();
      }
      catch (error) {
        this.$message.error(error);
        console.log(error);
        loading.close();
      }
    },

    validation() {
      let result = true;

      if (!this.input_category) {
        this.$message.error('Отсутствует категория');
        result = false;
      }
      if (!this.input_name) {
        this.$message.error('Отсутствует наименование');
        result = false;
      }
      if (!this.input_address) {
        this.$message.error('Отсутствует адрес');
        result = false;
      }
      // if (!this.input_file) {
      //   this.$message.error('Отсутствует картинка');
      //   result = false;
      // }

      return result;
    },

    turnBack() {
      this.$router.push('/shop/quick-filter/table');
    },

    async saveUpdateQuickFilter() {
      if (this.validation()) {
        const loading = this.$loading({
          lock: true,
          text: 'Loading',
          spinner: 'el-icon-loading',
          background: 'rgba(0, 0, 0, 0.7)',
        });
        let formData = new FormData();

        formData.append('category', this.input_category);
        formData.append('name', this.input_name);
        formData.append('displayed_name', this.input_displayed_name);
        formData.append('slug', this.input_slug);
        formData.append('address', this.input_address);
        formData.append('status', this.input_status);

        if(this.input_file) {
          formData.append('file', this.input_file);
        }

        try{
          await updateQuickFilter(this.quick_filter_id, formData);
          loading.close();
        }
        catch(error) {
          this.$message.error(error);
          console.log(error);
          loading.close();
        }
      }
    },

    async getOptionsTreeCategories() {
      try {
        const { options } = await getOptionsTree();
        this.options = options;
      }
      catch (error) {
        this.$message.error(error);
        console.log(error);
      }
    },

    httpRequest(data) {
      this.input_image = URL.createObjectURL(data.file);
      this.input_file = data.file;
    },

    beforeAvatarUpload(file) {
      let isJPG = false;

      switch(file.type) {
        case 'image/jpeg':
        case 'image/jpg':
        case 'image/png':
          isJPG = true;
          break;
        default:
          isJPG = false;
          break;
      }

      const isLt2M = file.size / 1024 / 1024 < 2;

      if (!isJPG) {
        this.$message.error('Неверный формат картинки');
      }
      if (!isLt2M) {
        this.$message.error('Картинка не должна превышать 2MB!');
      }
      return isJPG && isLt2M;
    },

    handleSlugChange() {
      this.input_slug = this.input_slug.replace(/[^0-9a-zA-Z\-]+/g, "-");
    }
  },
};
</script>

<style>
.el-upload__input {
  visibility: hidden;
}
.avatar-uploader .el-upload {
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}
.avatar-uploader .el-upload:hover {
  border-color: #409EFF;
}
.avatar-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 178px;
  height: 178px;
  line-height: 178px;
  text-align: center;
}
.avatar {
  width: 178px;
  height: 178px;
  display: block;
}
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
.el-input {
  width: 320px;
}
</style>
