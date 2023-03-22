<template>
    <tr>
        <td width="150">
            {{ content.element }}
        </td>
        <td>
            <div v-if="content.type==='html'">
                <wysiwyg
                  ref="wysiwyg"
                  placeholder="Контент"
                  @change="changeFromWysiwyg"
                  :html="contentEditable">
                </wysiwyg>
                <div v-if="isWysiwygUpdated">
                    <button @click="saveFromWysiwyg()" type="button" class="btn btn-default sm_editor">Сохранить</button>
                    <!--<button @click="revertFromWysiwyg()" type="button" class="btn btn-default sm_editor">Отменить</button>-->
                </div>
            </div>

            <input-editor
              v-if="content.type==='string'"
              :label="false"
              addclass=""
              :text="contentEditable"
              @save="updateFromInput"
              placeholder="Контент">
            </input-editor>

        </td>
    </tr>
</template>

<script>

  export default {
//    module.exports = {
        name: "ContentEdit",
        props: [
            'content'
        ],
        data() {
            return {
                isWysiwygUpdated: false
            };
        },
        computed: {
            contentEditable: {
                get() {
                    return this.content.content;
                },
                set(val, oldVal) {
                    this.contentToUpdate = val;
                    this.$store.dispatch('contents/update', { ...this.content, content: val });
                },
            }
        },
        methods: {
            updateFromInput(payload) {
                this.contentEditable = payload.value;
            },
            changeFromWysiwyg() {
                this.isWysiwygUpdated = true;
            },
            saveFromWysiwyg() {
                this.contentEditable = this.$refs.wysiwyg.$refs.content.innerHTML;
                this.isWysiwygUpdated = false;
            },
//            revertFromWysiwyg() {
//                this.contentToUpdate = this.contentEditable;
//            }
        },
        beforeMount() {
        }
    };
</script>

<style lang="scss">

</style>
