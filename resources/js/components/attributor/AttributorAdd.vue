<template>
  <div class="attributorAdd" v-on-clickaway="close">

    <div class="attributorAdd__search">
      <input
        @focus="expanded=true"
        type="text"
        placeholder="Добавить.."
        v-model="searchQuery">
    </div>

    <ul v-if="expanded" class="attributorAdd__options">

      <div v-if="attributes.length">
        <li
          v-for="attribute in attributes"
          @click="add(attribute.id)"
          class="dropdown-item">
          {{ getTitleFromAttribute(attribute) }}
        </li>
      </div>
    </ul>

  </div>
</template>

<script>
import Base from '../../mixins/Base';

export default {
// module.exports = {
  name: "AttributorAdd",
  mixins: [
    Base,
  ],
    props: {
        attr: {
            type: Array,
            default: ()=>([]),
        },
    onlyGlobal: {
      required: true,
      type: Boolean,
      default: false,
    },
    relation: {
      required: true,
      type: String,
      default: '',
      validator: function (value) {
        return value.length > 3;
      }
    },
    relation_id: {
      required: true,
      type: Number,
      default: 0,
      validator: function (value) {
        return value > 0;
      }
    },
  },
  data() {
    return {
      searchQuery: "",
      expanded: false
    };
  },
  computed: {
    attributes() {
        return this.attr.filter((el)=>(el.i18n[0].title.toLowerCase().indexOf(this.searchQuery.toLowerCase()) > -1));
    },
    // isCreateAttributeAvailable() {
    //   return this.attributes.length === 0 && this.searchQuery.length > 2;
    // }
  },
  watch: {
    searchQuery(val, old) {
      if (val.length > 0) {
        this.expanded = true;
      }
    }
  },
  methods: {
    close() {
      this.expanded = false;
    },
    add(attribute_id) {
        this.$emit('createAttribute', {
            relation: this.relation,
            relation_id: this.relation_id,
            attribute_id: attribute_id
        });

        this.close();
        this.clearSearchQuery();
    },
    // create() {
    //   this.$store.dispatch('attributes/createAttribute', {name: this.searchQuery}).then(attribute => {
    //     this.clearSearchQuery();
    //     this.add(attribute.id);
    //   });
    // },
    getTitleFromAttribute(attribute) {
      if (attribute.i18n[0]) {
        return attribute.i18n[0].title
      }

      return 'None';
    },

    clearSearchQuery() {
      this.searchQuery = '';
    },
  }
};
</script>

<style lang="scss">
.attributorAdd {
  width: 100%;
  display: block;
  margin-bottom: 20px;
  position: relative;
  &__search {
    height: 37px;
    padding: 0;
    margin: 0 15px;
    line-height: 36px;
    border: 1px solid #e4e4e4;
    position: relative;
    background: white;
    font-size: 14px;
    border-radius: 3px;
    z-index: 20;
    transition: all 0.12s linear;
    overflow: hidden;
    input {
      width: 100%;
      height: 36px;
      border: 0;
      padding: 5px 10px;
      &, &:active, &:focus {
        outline: none;
        box-shadow: none;
      }
    }
  }
  &__options {
    position: absolute;
    top: 100%;
    background: white;
    border: 0;
    width: 100%;
    z-index: 25;
    transition: all 0.12s ease-in;
    padding: 0;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    overflow-x: hidden;
    max-height: 400px;
    overflow-y: scroll;
    li {
      padding: 0 10px;
      line-height: 30px;
      transition: all 0.06s linear;
      font-size: 14px;
      &:hover {
        cursor: pointer;
        background: #f9fafc;
        color: #2b2b2c;
      }
    }
  }
  &__create{
    font-weight: 500;
  }
}
</style>
