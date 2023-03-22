<template>
    <div>
        <div class="gallery gallery-widget" ref="gallery">
            <div class="row ui-sortable">
                <div v-show="dropzoneIsVisible" class="col-xs-6 col-sm-3" style="min-width:155px;">
                    <vue-dropzone
                        ref="dropzone"
                        :id="id"
                        class="gallery-dropzone"
                        @vdropzone-success="success"
                        @vdropzone-error="error"
                        @vdropzone-max-files-reached="maxfilesreached"
                        @vdropzone-max-files-exceeded="maxfilesexceeded"
                        @vdropzone-file-added="fileAdded"
                        :options="getParams()"
                        :destroyDropzone="true"/>
                </div>

                <div v-for="(image, index) in images" :data-id="image.id" :key="image.id" class="col-xs-6 col-sm-3" style="min-width:155px;">
                    <input type="hidden" name="ids" :value="image.id">

                    <div :class="{'edit-photo-card': true, 'edit-photo-card--deleted': image.deleted, 'edit-photo-card--has-error': hasError(index)}">
                        <a data-fancybox="gallery" :href="getImageOriginal(image)" class="edit-photo-card__preview">
                            <div class="edit-photo-card__image" :style="`background-image:url(${getImagePreview(image)})`"></div>
                        </a>

                        <div class="edit-photo-card__deleted-icon">
                            <i class="fa fa-trash"></i>
                        </div>

                        <div class="edit-photo-card__controls">
                            <div class="pull-left">
                                <a class="btn btn-sm btn-primary" @click="edit(image)" v-if="!isDeleted(image)">
                                    <i class="fa fa-crop"></i>
                                </a>
                            </div>

                            <div class="pull-right">
                                <a class="btn btn-sm btn-danger" @click="remove(image)" v-if="!isDeleted(image)">
                                    <i class="fa fa-trash-o"></i>
                                </a>

                                <a class="btn btn-sm btn-success" @click="recover(image)" v-if="isDeleted(image)">
                                    <i class="fa fa-repeat"></i> Восстановить
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <b-modal
            ref="pictureEditModal"
            :no-close-on-backdrop="true"
            size="lg"
            title="Редактирование изображения"
            title-tag="h3"
            centered
            ok-title="Применить"
            cancel-title="Отмена"
            hide-header-close
            @hidden="clear"
            @ok="editorImageSave">

            <image-editor
                ref="imageEditor"
                v-if="!!editorImage"
                :image="editorImage.original"
                :modifications="getEditorImageModifications()">
            </image-editor>

        </b-modal>
    </div>
</template>

<script>
    import {ErrorBag} from "vee-validate";

//    import bModal from "bootstrap-vue/es/components/modal/modal";

    import Core from "../core";
    import VueDropzone from "vue2-dropzone";
    import Toggle from "./Toggle";
    import Sortable from "../mixins/Sortable";
    import ImageEditor from "./ImageEditor";

    export default {
        props: {
            id: {
                type: String,
                default: "dropzone"
            },
            url: {
                type: String,
                require: true,
            },

            images: {
                type: Array
            },

            params: {
                type: Object,
                default: function() {
                    return {};
                }
            },

            errors: {
                type: ErrorBag
            },

            safeDelete: {
                type: Boolean,
                default: false
            }
        },

        mixins: [
            Sortable
        ],

        components: {
            VueDropzone,
            Toggle,
//            bModal,
            ImageEditor
        },

        data() {
            return {
                params$: {
                    url: Core.addApiTokenToUrl(this.url),
                    thumbnailWidth: 150,
                    maxFilesize: 8,
                    addRemoveLinks: false,
                    autoProcessQueue: true,
                    ignoreHiddenFiles: true,
                    dictDefaultMessage: "<div>Добавить изображение<div><i class=\"fa fa-plus-circle\" style=\"vertical-align:bottom;font-size:40px;\"></i></div></div>",
                    dictFallbackMessage: "Ваш браузер не поддерживает загрузку файлов при помощи drag'n'drop.",
                    dictFallbackText: "Используйте форму ниже, чтобы загрузить файлы.",
                    dictFileTooBig: "Размер файла слишком велик ({{filesize}} Mb). Максимальный размер файла: {{maxFilesize}} Mb.",
                    dictInvalidFileType: "Вы не можете загружать файлы этого типа.",
                    dictResponseError: "Сервер вернул ошибку с кодом: {{statusCode}}.",
                    dictCancelUpload: "Отменить загрузку",
                    dictUploadCanceled: "Загрузка отменена.",
                    dictCancelUploadConfirmation: "Вы уверены, что хотите отменить загрузку?",
                    dictRemoveFile: "Удалить файл",
                    dictMaxFilesExceeded: "Достигнут лимит количества файлов.",
                    acceptedFiles: "image/jpeg, image/png",
                    previewTemplate: "<div class=\"dz-preview dz-processing dz-complete dz-image-preview\"><div class=\"dz-remove\" data-dz-remove></div><div class=\"dz-image\"><a href=\"javascript:void(0)\" class=\"dz-link\"><img data-dz-thumbnail /><div class=\"dz-details\"><div class=\"dz-size\"><span data-dz-size></span></div><div class=\"dz-filename\"><i class=\"dz-icon fa fa-search\"></i></div></div></a></div><div class=\"dz-progress\"><span class=\"dz-upload\" data-dz-uploadprogress></span></div><div class=\"dz-error-message\"><span data-dz-errormessage></span></div><div class=\"dz-success-mark\"><i class=\"dz-icon fa fa-check\"></i></div><div class=\"dz-error-mark\"><i class=\"dz-icon fa fa-warning\"></i></div>",
                },

                sortableParams: {
                    update: this.sort
                },

                editorImage: null,

                type$: null
            };
        },

        methods: {
            initSort() {
                let params = this.getParams();

                if (params.maxFiles !== 1) {
                    Sortable.methods.initSort.call(this);
                }
            },

            getParams() {
                return {
                    ...this.params$,
                    ...this.params
                };
            },

            getInstance() {
                return this.$refs.dropzone.dropzone;
            },

            fileAdded(file) {
                let params = this.getParams();
                if (params.maxFiles && this.images.length < params.maxFiles) {
                    file.noFilesLimit = true;
                }
            },

            success(file, response) {
                this.getInstance().removeFile(file);

                this.add(response.image);
            },

            error(file, errorMessage) {
                Core.notify(errorMessage, {type: "error"});

                this.getInstance().removeFile(file);

                this.remove(file);
            },

            maxfilesreached(files) {
                for (let i = 0; i < files.length; i++) {
                    if (!files[i].noFilesLimit) {
                        Core.notify(this.getParams().dictMaxFilesExceeded, {type: "warning"});
                        break;
                    }
                }
            },

            maxfilesexceeded(file) {
                let params = this.getParams();
                let fileSize = (file.size / 1024 / 1024).toFixed(1);
                let message = params.dictFileTooBig;

                message = message.replace("{{filesize}}", fileSize);
                message = message.replace("{{maxFilesize}}", params.maxFilesize);

                Core.notify(message, {type: "warning"});
            },

            sort() {
                this.$emit("update:images", this.sortDataBundleByIdsPosition(this.images, this.collectSortIds()));
            },

            add(image) {
                this.$emit("update:images", [
                    ...this.images,
                    image
                ]);
            },

            update(image) {
                this.$emit("update:images", this.images.map(item => item.id === image.id ? image : item));
            },

            edit(image) {
                this.editorImage = image;
                this.$refs.pictureEditModal.show();
            },

            remove(image) {
                if (this.safeDelete) {
                    image.deleted = true;
                    this.update(image);
                }
                else {
                    this.$emit("update:images", this.images.filter(item => item.id !== image.id));
                }
            },

            recover(image) {
                image.deleted = false;
                this.update(image);
            },

            isDeleted(image) {
                return image.deleted;
            },

            editorImageSave() {
                let image = this.editorImage;
                let modifications = this.$refs.imageEditor.getClearedModifications();

                if (Object.keys(modifications) === 0) {
                    if (image.modifications) {
                        delete image.modifications;
                    }

                    if (image.cropped) {
                        delete image.cropped;
                    }
                }
                else {
                    image.modifications = modifications;
                    image.cropped = this.$refs.imageEditor.getCroppedImage();
                }

                this.update(image);
                this.editorImage = null;
            },

            getImageOriginal(image) {
                return image.cropped
                    ? this.$helpers.getImagePath(image.cropped)
                    : this.$helpers.getImagePath(image.original);
            },

            getImagePreview(image) {
                if (image.cropped) {
                    return image.cropped;
                }

                if (image.small) {
                    return image.small.srcset
                        ? this.$helpers.getImagePath(image.small.srcset)
                        : this.$helpers.getImagePath(image.small.src);
                }

                return image.original || "";
            },

            getEditorImageModifications() {
                console.log(this.editorImage)
                return (this.editorImage && this.editorImage.modifications) ? this.editorImage.modifications : {};
            },

            clear() {
                this.editorImage = false;
            },

            hasError(imageIndex) {
                return this.errors.has(`images.${imageIndex}`);
            }
        },

        computed: {
            dropzoneIsVisible() {
                let params = this.getParams();

                if (params.maxFiles && params.maxFiles === this.images.length) {
                    return false;
                }

                return true;
            }
        },
    };
</script>

<style>
    .gallery.gallery-widget #dropzone.dropzone {
        min-height: 125px!important;
    }

    .gallery.gallery-widget #dropzone.dropzone .dz-message {
        margin: 0;
    }

    .gallery-dropzone .dz-message {
        position: relative;
        margin: 0;
        display: block !important;
    }

    .gallery-dropzone .dz-message::before {
        content: '';
        display: block;
        padding-top: 100%;
    }

    .gallery-dropzone .dz-message > span {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .gallery-dropzone .dz-preview {
        display: none;
    }
</style>
