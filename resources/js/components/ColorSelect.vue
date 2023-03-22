<script>
import ColorPicker from 'v-color';
// import bDropdown from 'bootstrap-vue/es/components/dropdown/dropdown'
// import bDropdownItem from 'bootstrap-vue/es/components/dropdown/dropdown-item'

export default {
  name: 'ColorSelect',

  components: {
    ColorPicker,
    // bDropdown,
    // bDropdownItem
  },

  props: [
    'color',
  ],

  data() {
    return {
      active: false,
    };
  },

  mounted() {
    window.addEventListener('click', this.handleClick, { passive: true });
    window.addEventListener('touchend', this.handleClick, { passive: true });
    window.addEventListener('keydown', this.handleKeydown, { passive: true });
  },

  beforeDestroy() {
    window.removeEventListener('click', this.handleClick);
    window.removeEventListener('touchend', this.handleClick);
    window.removeEventListener('keydown', this.handleKeydown);
  },

  methods: {
    setColor(color) {
      this.$emit('update:color', color);
    },

    colorPickerChange(colorData) {
      const ref = this.$refs.colorPicker;
      if (!ref) {
        return;
      }

      let color;

      switch (ref.currentMode) {
        case 'hex':
          color = colorData[ref.currentMode];
          break;

        case 'hsla':
          color = this.removeUnnecessaryAlpha('hsl', colorData[ref.currentMode]);
          break;

        case 'rgba':
          color = this.removeUnnecessaryAlpha('rgb', colorData[ref.currentMode]);
          break;
      }

      this.setColor(color);
    },

    inputChange(e) {
      this.setColor(e.target.value);
    },

    removeUnnecessaryAlpha(label, colorArray) {
      if (colorArray[3] === 1) {
        return label + '(' + colorArray.slice(0, 3).join(', ') + ')';
      }

      return label + 'a(' + colorArray.join(', ') + ')';
    },

    handleKeydown(e) {
      const code = e.keyCode || e.which;

      if (this.active && code === 27) {
        this.deactivate();
      }
    },

    handleClick(e) {
      if (this.$el.contains(e.target)) {
        if (!this.active) {
          this.activate();
        }
      } else {
        if (this.active) {
          this.deactivate();
        }
      }
    },

    activate() {
      this.active = true;
    },

    deactivate() {
      this.active = false;
    },
  },
};
</script>

<style>
  .input-group-addon {
    padding: 0;
    overflow: hidden;
  }

  .color-preview {
    width: 100%;
    height: 32px;
  }

  .color-select {
    position: relative;
  }

  .color-select__picker {
    position: absolute;
    z-index: 10;
  }
</style>

<template>
  <div class="color-select">
    <div class="input-group">
      <input
        type="text"
        :value="color"
        class="form-control"
        @input="inputChange"
      >

      <span class="input-group-addon">
        <div class="color-preview" :style="{backgroundColor: color}" />
      </span>
    </div>

    <div v-show="active" class="color-select__picker">
      <color-picker
        ref="colorPicker"
        :color="color || undefined"
        @change="colorPickerChange"
      />
    </div>
  </div>
</template>
