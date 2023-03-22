<template>
  <el-table
    :data="tableData"
    style="width: 100%"
  >
    <el-table-column
      prop="date"
      label="Date"
      width="180"
    />
    <el-table-column
      prop="name"
      label="Name"
      width="180"
    />
    <el-table-column
      prop="address"
      label="Address"
    />
  </el-table>
</template>

<script>
export default {
  name: 'ProbaTable',
  data() {
    return {
      tableData: [{
        date: '2016-05-03',
        name: 'Tom',
        address: 'No. 189, Grove St, Los Angeles',
      }, {
        date: '2016-05-02',
        name: 'Tom',
        address: 'No. 189, Grove St, Los Angeles',
      }, {
        date: '2016-05-04',
        name: 'Tom',
        address: 'No. 189, Grove St, Los Angeles',
      }, {
        date: '2016-05-01',
        name: 'Tom',
        address: 'No. 189, Grove St, Los Angeles',
      }],
    };
  },
  created() {
    console.log(process.env.MIX_AWS_URL);
    console.log(process.env.MIX_LARAVUE_USE_ESLINT);
    console.log(process.env.MIX_BASE_API);

    const path = 'media/product/17587/responsive-images/5afd93ddaa917127895932___thumb_80_80.jpg';

    console.log(this.$helpers.getImagePath(path));

    // src="https://candellabra-product-photos.s3.eu-central-1.amazonaws.com/media/product/17577/responsive-images/5afd91fa06b5b282006383___thumb_80_80.jpg"
  },
};
</script>
