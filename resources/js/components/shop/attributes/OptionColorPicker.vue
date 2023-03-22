<template>
  <div :ref="'option-' + option.id">
    <div
      v-if="option.rgba"
      class="option--color"
      :style="{backgroundColor: option.rgba}"
      @click="checkoutColor"
    />
    <div
      v-if="!option.rgba"
      class="option--color"
      :style="{backgroundColor: option.rgba, borderStyle: 'dotted', borderColor: 'black', borderWidth: '1px'}"
      @click="checkoutColor"
    />

    <b-modal
      id="colorpickerModal"
      ref="colorpickerModal"
      title="Выбор цвета"
      title-tag="h3"
      centered
      ok-title="Ок"
      ok-only
      hide-header-close
    >
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6">
            <label for="sketchPicker">Выберите цвет</label>
            <sketch-picker
              id="sketchPicker"
              ref="sketchPicker"
              v-model="colors"
              @input="updateColor($event)"
            />
          </div>
          <div class="col-md-5">
            <label for="attributeName">Название</label>
            <input
              id="attributeName"
              v-model="colorName"
              type="text"
              name="attributeName"
              disabled="disabled"
            >
          </div>
        </div>
      </div>
    </b-modal>
  </div>
</template>
<script>
import slider from 'vue-color/src/components/Slider.vue';
import sketch from 'vue-color/src/components/Sketch.vue';
import chrome from 'vue-color/src/components/Chrome.vue';
import photoshop from 'vue-color/src/components/Photoshop.vue';
//    import bModal from 'bootstrap-vue/es/components/modal/modal';

export default {
  name: 'OptionColorPicker',

  components: {
    //            bModal,
    'slider-picker': slider,
    'sketch-picker': sketch,
    'chrome-picker': chrome,
    'photoshop-picker': photoshop,
  },

  props: [
    'option',
    'name',
  ],

  data() {
    return {
      colors: {
        hex: '#194d33',
        hsl: { h: 150, s: 0.5, l: 0.2, a: 1 },
        hsv: { h: 150, s: 0.66, v: 0.30, a: 1 },
        rgba: { r: 25, g: 77, b: 51, a: 1 },
        a: 1,
      },
      colorName: this.name,
      colorValueToSave: {
        hex: '#194d33',
        hsl: { h: 150, s: 0.5, l: 0.2, a: 1 },
        hsv: { h: 150, s: 0.66, v: 0.30, a: 1 },
        rgba: { r: 25, g: 77, b: 51, a: 1 },
        a: 1,
      },
      colorOptionToChange: null,
    };
  },

  mounted() {
    if (this.option.colors !== undefined) {
      this.colors = this.option.colors;
    }
    this.colorValueToSave = this.option.color;

    this.$root.$on('bv::modal::hide', (bvEvent) => {
      if (this.colorValueToSave === undefined || !bvEvent.vueTarget.$parent.$el.isSameNode(this.$el)) {
        return;
      }

      this.option.value.color = {};
      this.option.value.color.value = {};
      this.option.value.color.value = JSON.stringify(this.colorValueToSave);
      this.option.colors = this.colorValueToSave;
      this.colors = this.colorValueToSave;
      this.option.rgba = `rgba(${this.option.colors.rgba.r}, ${this.option.colors.rgba.g}, ${this.option.colors.rgba.b}, ${this.option.colors.rgba.a})`;

      this.$forceUpdate();
    });
  },

  methods: {
    checkoutColor() {
      this.$refs.colorpickerModal.show();
    },

    updateColor(e) {
      this.colorValueToSave = e;
    },
  },
};
</script>

<style>
  .option--color {
    content: '';
    width: 16px;
    height: 16px;
    left: 0;
    top: 50%;
    margin-top: 0;
    border-radius: 50%;
    overflow: hidden;
  }
</style>
