<script>
  import Core from '../../core'
  import Dropdown from '../Dropdown'

  export default {
    name: "base-converter",

    props: [
      'parameters',
      'value'
    ],

    components: {
      Dropdown
    },

    data() {
      return {
        defaultParameter: null,
        currentParameter: null,
      }
    },

    created() {
      this.defaultParameter = this.parameters.find(item => !!item.default)

      if (! this.defaultParameter) {
        this.defaultParameter = this.parameters[0]
      }

      this.setCurrentParameter(this.defaultParameter)
    },

    methods: {
      setCurrentParameter(parameter) {
        if (this.currentParameter) {
          this.currentParameter.isCurrent = false
        }

        this.currentParameter = this.parameters.find(item => parameter.name === item.name)
        this.currentParameter.isCurrent = true
      },

      convertValue(parameter) {
        if (parameter.multiplier) {
          return this.formatFunc(parameter)
        }

        if (parameter.formatFunc) {
          return parameter.formatFunc(this.value)
        }

        return false
      },

      formatFunc(parameter) {
        let formatted = Core.formatNumber(this.value * parameter.multiplier, parameter.precision || 2)

        return parameter.name ? `${formatted} ${parameter.name}` : formatted
      }
    },

    computed: {
      convertedValue() {
        return this.convertValue(this.currentParameter)
      },

      switcherAvailable() {
        return this.switcher.length > 1
      },

      switcher() {
        return this.parameters.reduce((acc, parametr) => {
          if (parametr.name) {
            acc.push({
              ... parametr,
              title: this.convertValue(parametr)
            })
          }

          return acc
        }, []).sort((a, b) => parseInt(a.default) - parseInt(b.default))
      }
    }
  }
</script>

<template>
  <span class="input-group-addon input-group-addon-gray converterAddon" v-if="convertedValue !== false">
    <span v-if="!switcherAvailable">{{convertedValue}}</span>

    <dropdown className="btn-group btn-group-sm" position="right" :options="switcher" v-if="switcherAvailable">
      <template slot="button">
        <span class="pointer">
          {{convertedValue}} <span class="caret"></span>
        </span>
      </template>

      <template slot="option" slot-scope="parameter">
        <li :class="{active: parameter.isCurrent}" :key="parameter.name">
          <a @click="setCurrentParameter(parameter)" href="javascript:void(0)">
            {{parameter.title}}
          </a>
        </li>
      </template>
    </dropdown>
  </span>
</template>

<style lang="scss">
    .converterAddon{
        padding-left: 10px !important;
        padding-right: 10px !important;
        font-size: 13px !important;
        z-index: 1;
        overflow: visible !important;
        .dropdown-menu{
            z-index: 3;
        }
    }
</style>
