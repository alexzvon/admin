<template>
  <div>

    <div>

      <div>
        <el-input v-model="name">
          <template slot="prepend" class="el-input-template_prepend">Наименование</template>
        </el-input>
      </div>
      <div>
        <el-input v-model="name_vin">
          <template slot="prepend" class="el-input-template_prepend">Наименование (вин)</template>
        </el-input>
      </div>
      <div>
        <el-input v-model="cities">
          <template slot="prepend" class="el-input-template_prepend">Город присутствия</template>
        </el-input>
      </div>
      <div>
        <el-input v-model="address">
          <template slot="prepend" class="el-input-template_prepend">Адрес</template>
        </el-input>
      </div>
      <div>
        <el-input v-model="work_time">
          <template slot="prepend" class="el-input-template_prepend">Время работы</template>
        </el-input>
      </div>
      <div>
        <el-input v-model="email">
          <template slot="prepend" class="el-input-template_prepend">E-mail</template>
        </el-input>
      </div>
      <div>
        <el-input v-model="phone">
          <template slot="prepend" class="el-input-template_prepend">Телефон</template>
        </el-input>
      </div>
      <div>
        <el-input v-model="lat">
          <template slot="prepend" class="el-input-template_prepend">Lat</template>
        </el-input>
      </div>
      <div>
        <el-input v-model="lon">
          <template slot="prepend" class="el-input-template_prepend">Lon</template>
        </el-input>
      </div>

      <div id="map">
        <yandex-map :settings="settings"
          :controls="controls"
          :coords="center"
          :zoom="10"
          :options="{ suppressMapOpenBlock: true }"
          @click="onClick"
        >
          <ymap-marker v-if="0 < coords[0] && 0 < coords[1]"
            :coords="coords"
            marker-id="123"
            hint-content="some hint"
          />
        </yandex-map>
      </div>

      <div>
        <el-input v-model="media">
          <template slot="prepend" class="el-input-template_prepend">Изображение</template>
        </el-input>
      </div>

      <div class="block-section">
        <dropzone-gallery
          ref="gallery"
          :url="makePageApiUrl('image')"
          :images.sync="images"
          :errors="formErrors"/>
      </div>

      `
    </div>

  </div>
</template>

<script>
import { yandexMap, ymapMarker } from 'vue-yandex-maps';
import DropzoneGallery from "@/components/DropzoneGallery";
import EntityPage from "../../../mixins/EntityPage";
import Translatable from "../../../mixins/Translatable";

const settings = {
  apiKey: process.env.MIX_API_YANDEX_MAP,
  lang: 'ru_RU',
  coordorder: 'latlong',
  version: '2.1'
}

export default {
  name: "createShowRoom",
  mixins: [
    EntityPage,
//    Translatable,
  ],
  components: { yandexMap, ymapMarker, DropzoneGallery },
  data: () => ({
    center: [ 55.751574, 37.573856 ],
    coords: [ 0.0, 0.0 ],

    name: '',
    name_vin: '',
    cities: '',
    address: '',
    work_time: '',
    email: '',
    phone: '',
    lat: 0.0,
    lon: 0.0,
    media: '',
    video: '',

    images: [],

    controls: [ 'fullscreenControl' ],
    settings: settings,
  }),
  created() {
    console.log('makePageApiUrl(\'image\')');
    console.log(makePageApiUrl('image'));
  },
  methods: {
    onClick(e) {
      this.coords = e.get('coords');

      this.lat = this.coords[0];
      this.lon = this.coords[1];

      this.center = this.coords;

      console.log(this.coords[0]);
      console.log(this.coords[1]);
    },
  },
}
</script>

<style scoped lang="scss">
#map {
  width: 100%;
  height: 30vh;
}
.ymap-container {
  height: 100%;
}
.el-input {
  width: 100%;
}
</style>

<style lang="scss">
.el-input {
  .el-input-group__prepend {
    width: 30%;
  }
}
</style>
