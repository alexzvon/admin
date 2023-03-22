<template>
  <el-container>
    <el-header>
      <el-row type="flex" justify="start">
        <h3>Изменить комнату:</h3>
        <h3>"{{name}}"</h3>
      </el-row>
    </el-header>
    <el-main>
      <el-tabs type="border-card">
        <el-tab-pane label="Свойства">
<!--          <el-row>-->
<!--            <el-input v-model="cities">-->
<!--              <template slot="prepend" class="el-input-template_prepend">Город представления</template>-->
<!--            </el-input>-->
<!--          </el-row>-->
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
          <el-row type="flex" justify="start" align="middle">
            <div class="el-input-group__prepend">
              <div>Статус</div>
            </div>
            <el-switch v-model="status"></el-switch>
          </el-row>
        </el-tab-pane>
        <el-tab-pane label="Изображение">
          <el-card>
            <dropzone-gallery
              ref="gallery"
              :url="makeApiUrlUpload()"
              :images.sync="images"
              :errors="formErrors"/>
          </el-card>
        </el-tab-pane>
        <el-tab-pane label="Карта">
          <el-row>
            <el-col :span="12">
              <el-input v-model="lat">
                <template slot="prepend" class="el-input-template_prepend">Lat</template>
              </el-input>
            </el-col>
            <el-col :span="12">
              <el-input v-model="lon">
                <template slot="prepend" class="el-input-template_prepend">Lon</template>
              </el-input>
            </el-col>
          </el-row>
          <div id="map">
            <yandex-map v-if="yandexLoad"
                        :settings="settings"
                        :controls="controls"
                        :coords="center"
                        :zoom="10"
                        :options="{ suppressMapOpenBlock: true }"
                        @click="setCoordinate"
            >
              <ymap-marker v-if="0 < coords[0] && 0 < coords[1]"
                           :coords="coords"
                           marker-id="123"
                           :hint-content="address"
              />
            </yandex-map>
          </div>
        </el-tab-pane>
        <el-tab-pane label="Видео">
          <label>Добавить видео Vimeo</label>
          <input
            id="add-vimeo-video"
            name="add-vimeo-video"
            type="text"
            class="form-control"
            placeholder="Вставьте адрес ролика на vimeo и нажмите Enter"
            @keypress.enter="addVimeoVideo"
            v-model="newVimeoVideoUrl">
          <label>Добавить видео YouTube</label>
          <input
            id="add-youtube-video"
            name="add-youtube-video"
            type="text"
            class="form-control"
            placeholder="Вставьте адрес ролика на youtube и нажмите Enter"
            @keypress.enter="addYoutubeVideo"
            v-model="newYoutubeVideoUrl">
        </el-tab-pane>
      </el-tabs>
    </el-main>
    <el-footer>
      <el-row>
        <el-button @click="updateRoom" type="primary" round size="mini">Сохранить</el-button>
        <el-button @click="routeBack" type="info" round size="mini">Комнаты</el-button>
      </el-row>
    </el-footer>
  </el-container>
</template>

<script>
import { yandexMap, ymapMarker } from 'vue-yandex-maps';
import { getEditRoom, updateShowRoom, saveVideo } from '@/api/showroom';

import DropzoneGallery from "@/components/DropzoneGallery";

const settings = {
  apiKey: process.env.MIX_API_YANDEX_MAP,
  lang: 'ru_RU',
  coordorder: 'latlong',
  version: '2.1',
}

export default {
  name: "editShowRoom",
  components: { yandexMap, ymapMarker, DropzoneGallery },
  data: () => ({
    id: 0,
    group: '',
    group_id: 0,
    name: '',
    cities: '',
    address: '',
    work_time: '',
    phone: '',
    email: '',
    status: false,

    images: [],

    lat: 0.0,
    lon: 0.0,
    controls: [ 'fullscreenControl' ],
    settings: settings,
    center: [ 55.751574, 37.573856 ],
    coords: [ 0.0, 0.0 ],
    yandexLoad: false,

    newVimeoVideoUrl: '',
    newYoutubeVideoUrl: '',

  }),
  created() {
    this.id = this.$route.params.id;
    this.getRoom();
  },
  methods: {
    async addYoutubeVideo() {
      if (this.isYoutubeUrlValid() || '' === this.newYoutubeVideoUrl) {
        try {
          const { success } = await saveVideo(this.id, {
            url: this.newYoutubeVideoUrl,
            provider: 'youtube',
          });
        }
        catch (error) {
          console.log(error);
        }
      } else {
        this.$core.notify("Неверный формат адреса youtube", {type: "error"});
        this.newYoutubeVideoUrl = '';
      }
    },

    async addVimeoVideo() {
      if (this.isVimeoUrlValid() || '' === this.newVimeoVideoUrl) {
        try {
          const { success } = await saveVideo(this.id, {
            url: this.newVimeoVideoUrl,
            provider: 'vimeo',
          });
        }
        catch (error) {
          console.log(error);
        }
      } else {
        this.$core.notify("Неверный формат адреса Vimeo", {type: "error"});
        this.newVimeoVideoUrl = '';
      }
    },

    isYoutubeUrlValid() {
      let url = this.newYoutubeVideoUrl;
      let p = /^(?:https?:\/\/)?(?:m\.|www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
      if (url.match(p)){
        return url.match(p)[1];
      }
      return false;
    },
    isVimeoUrlValid() {
      let url = this.newVimeoVideoUrl;
      let p = /(http|https)?:\/\/(www\.|player\.)?vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|video\/|)(\d+)(?:|\/\?)/;
      if (url.match(p)){
        return url.match(p)[4];
      }
      return false;
    },

    makeApiUrlUpload() {
      return '/api/franchise/showrooms/room/' + this.id + '/images/upload/';
    },

    async updateRoom() {
      const loading = this.$loading({
        lock: true,
        text: 'Сохранить',
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.1)',
      });
      try {
        const { success } = await updateShowRoom(
          this.id,
          {
            name: this.name,
            address: this.address,
            work_time: this.work_time,
            phone: this.phone,
            email: this.email,
            status: this.status,
            images: this.images,
            lat: this.lat,
            lon: this.lon,
        });

        if (success) {
          this.getRoom();
        }
        loading.close();
      }
      catch (error) {
        loading.close();
        console.log(error);
      }
    },

    async getRoom() {
      try {
        const { data } = await getEditRoom(this.id);
        this.name = data.name;
        this.group_id = data.group_id;
        this.address = data.address;
        this.work_time = data.work_time;
        this.phone = data.phone;
        this.email = data.email;
        this.lat = data.lat;
        this.lon = data.lon;
        this.newYoutubeVideoUrl = data.video_youtube;
        this.newVimeoVideoUrl = data.video_vimeo;
        this.status = data.status;
        this.images = data.images;

        if (0 < this.lat && 0 < this.lon) {
          this.$set(this.coords, 0, parseFloat(this.lat));
          this.$set(this.coords, 1, parseFloat(this.lon));

          this.center = [parseFloat(this.lat), parseFloat(this.lon)];
        }

        this.yandexLoad = true;
      }
      catch (error) {
        console.log(error);
      }
    },

    routeBack() {
      this.$router.push('/showroom/table/' + this.group_id);
    },




    setCoordinate(e) {
      this.coords = e.get('coords');

      this.lat = this.coords[0];
      this.lon = this.coords[1];

      this.center = this.coords;
    },
    onClick() {
      alert('onClick');
    }
  }
}
</script>

<style scoped lang="scss">
.el-main {
  width: 96%;
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
#map {
  width: 100%;
  height: 50vh;
}
.ymap-container {
  height: 100%;
}
</style>

<style lang="scss">
  .el-input {
    .el-input-group__prepend {
      width: 20%;
      color: black;
    }
  }
  .edit-photo-card__deleted-icon {
    display: none;
  }
  .el-card__body {
    padding: 40px;
  }
</style>