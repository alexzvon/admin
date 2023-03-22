<template>
  <div>
    <el-divider content-position="left">Редактирование товара #100194</el-divider>
    <el-tabs v-model="activeName" @tab-click="handleClick">
      <el-tab-pane label="Общая инфорамация" name="tab1"><general/></el-tab-pane>
      <el-tab-pane label="Осн. характеристики" name="tab2"><properties/></el-tab-pane>
      <el-tab-pane label="Доп. характеристики" name="tab3"><addition/></el-tab-pane>
      <el-tab-pane label="Фото и видео" name="tab4"><fotovideo/></el-tab-pane>
      <el-tab-pane label="Комплектность" name="tab5"><complect/></el-tab-pane>
      <el-tab-pane label="Информация о ценах" name="tab6"><price/></el-tab-pane>
      <el-tab-pane label="Все нужные поля для SEO" name="tab7"><seo/></el-tab-pane>
      <el-tab-pane label="Статистика" name="tab8"><stat/></el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import properties from "./properties";
import general from "./general";
import addition from "./addition";
import fotovideo from "./fotovideo";
import complect from "./complect";
import price from "./price";
import seo from "./seo";
import stat from "./static";

export default {
  name: "product",
  components: {
    properties,
    general,
    addition,
    fotovideo,
    complect,
    price,
    seo,
    stat
  },
  data() {
    return {
      activeName: 'tab1'
    };
  },
  methods: {
    handleClick(tab, event) {
      console.log(tab, event);
    }
  },
}
</script>

<style scoped>

</style>