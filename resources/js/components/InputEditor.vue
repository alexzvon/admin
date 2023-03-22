<template>
    <div class="text_editor" v-on-clickaway="revert">

        <label class="text_editor__label" v-if="label">{{ label }}</label>

        <textarea
            rows="1"
            spellcheck="false"
            class="form-control sm_editor"
            @click=activate
            @keypress.enter.prevent="save()"
            @keypress.esc="revert()"
            @mouseup=autosize
            @mouseout=autosize
            :placeholder="(placeholder ? placeholder : 'Empty..')"
            :readonly="!isEdit"
            v-model.trim="content"
            :class="textAreaStyle">
        </textarea>

        <div v-show="isEdit" class="text_editor__btns">
            <button class="btn btn-default sm_editor"
                    type="button"
                    @click="save()">Сохранить
            </button>
            <button class="btn btn-default sm_editor"
                    type="button"
                    @click="revert()">Отменить
            </button>
        </div>
    </div>
</template>

<script>
module.exports = {
    name: 'textEditor',
    props: [
        'text',
        'label',
        'addclass',
        'placeholder',
        'payload'
    ],
    data(){
        return {
            isEdit: false,
            content: ''
        }
    },
    computed: {
        textAreaStyle() {
            return this.isEdit ? 'active ' + this.addclass : this.addclass
        }
    },
    watch: {
        isEdit() {
            setTimeout(this.autosize, 100)
        }
    },
    beforeMount() {
        this.content = this.text
    },
    mounted() {
        this.autosize()
    },
    methods: {
        activate() {
            this.isEdit = true
            this.autosize()
        },
        save() {
            this.isEdit = false;
            this.$emit('save', {
                value: this.content,
                payload: this.payload
            });
        },
        revert() {
            this.isEdit = false;
            this.content = this.text;
        },
        autosize() {
            if (this.$el) {
                let textarea = this.$el.getElementsByTagName('textarea')[0]
                textarea.style.cssText = 'height:auto; padding:0'
                textarea.style.cssText = '-moz-box-sizing:content-box'
                textarea.style.cssText = 'height:' + textarea.scrollHeight + 'px'
            } else {
                setTimeout(this.autosize, 300)
            }
        }
    }
}
</script>


<style lang="scss">
    .text_editor {
        position: relative;
        &__label{
            display: block;
            font-size: 13px;
            color: rgba(59, 66, 101, 0.77);
            margin-bottom: -0px;
            font-weight: 400;
            overflow: hidden;
            white-space: nowrap;
        }
        textarea.sm_editor {
            min-height: 38px;
            border: 1px solid #e4e4e4;
            border-radius: 2px;
            &, &:focus, &:hover, &[readonly] {
                display: block;
                resize: none;
                padding: 8px 10px;
                margin: 0;
                font-size: 14px;
                background: transparent;
                transition: .2s all ease;
                color: #444;
                position: relative;
                z-index: 2;
            }
            &.active {
                padding: 8px 10px;
                background: white;
                box-shadow: none;
                position: relative;
                border:1px solid #8d8f98;
                z-index: 2;
            }
        }
        &__btns{
            margin: 0;
            top:100%;
            display:flex;
            flex-direction: row;
            position: absolute;
            z-index: 3;
            button.sm_editor {
                line-height: 120%;

            }
        }
    }
</style>
