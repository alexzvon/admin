<script>
  import Dropdown from './Dropdown'
  import LanguageIdentif from './LanguageIdentif'
  import StageSaver from '../mixins/StageSaver'

  export default {
    name: 'language-picker',

    mixins: [
      StageSaver
    ],

    props: [
      'languages',
      'activeLanguageCode'
    ],

    components: {
      Dropdown,
      LanguageIdentif
    },

    data() {
      return {
        hovered: false,
        stageValueName: 'activeLanguageCode'
      }
    },

    methods: {
      changeLanguage(code) {
        this.$emit('update:activeLanguageCode', code)
      },

      activeLanguage() {
        return this.languages.find(item => item.code === this.activeLanguageCode)
      },

      mouseOver() {
        this.hovered = true
      },

      mouseOut() {
        this.hovered = false
      }
    },
  }
</script>

<template>
  <dropdown className="btn-group btn-group-sm language-picker" position="right" :options="languages" v-if="languages.length > 1">
    <template slot="button">
      <span class="btn btn-xs btn-default btn-alt dropdown-toggle language-picker__btn" v-on:mouseover="mouseOver" v-on:mouseout="mouseOut">
        <language-identif :language="activeLanguage()"></language-identif>
        <i class="fa fa-chevron-down"></i>
      </span>
    </template>

    <template slot="option" slot-scope="language">
      <li :class="{active: language.code === activeLanguageCode}" :key="language.code" style="text-align: right;">
        <a @click="changeLanguage(language.code)" href="javascript:void(0)">
          {{ language.name }}
          <strong class="pull-left">
            <language-identif :language="language" :hideCode="true"></language-identif>
          </strong>
        </a>
      </li>
    </template>
  </dropdown>
</template>