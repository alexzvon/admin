<script>
    import Core from "../core";
    import vuecropper from "vue-cropperjs";
    import Loading from "./Loading";

    export default {
        name: "image-editor",

        components: {
            vuecropper,
            Loading
        },

        props: [
            "image",
            "modifications"
        ],

        watch: {
            image: "reset"
        },

        data() {
            return {
                loading: true,
                cropperData: null,
                innerModifications: {},
                defaultCropParams: {
                    width: null,
                    height: null,
                    rotate: 0,
                    scaleX: 1,
                    scaleY: 1,
                    x: 0,
                    y: 0
                }
            };
        },

        methods: {
            getCropper() {
                return this.$refs.cropper;
            },
            getImagePath() {
                return this.$helpers.getImagePath(this.image);
            },

            invertX() {
                this.invert("scaleX");
            },

            invertY() {
                this.invert("scaleY");
            },

            invert(direction) {
                let cropper = this.getCropper();
                cropper[direction](cropper.getData()[direction] === -1 ? 1 : -1);
                this.updateModifications();
            },

            rotateLeft() {
                this.rotate(-1);
            },

            rotateRight() {
                this.rotate(1);
            },

            rotate(step) {
                let cropper = this.getCropper();
                cropper.rotate(step * 90);
                this.fitImageToBox();
                this.updateModifications();
            },

            cropend() {
                this.updateModifications();
            },

            updateModifications() {
                this.setModifications(this.getCropper().getData());
            },

            fitImageToBox() {
                let cropper = this.getCropper();

                let containerData = cropper.getContainerData();
                let canvasData = cropper.getCanvasData();

                if (canvasData.width / containerData.width > canvasData.height / containerData.height) {
                    cropper.setCanvasData({
                        width: containerData.width,
                    });
                }
                else {
                    cropper.setCanvasData({
                        height: containerData.height
                    });
                }

                canvasData = cropper.getCanvasData();

                cropper.cropper.options.viewMode = 0;

                cropper.setCanvasData({
                    left: (containerData.width - canvasData.width) / 2,
                    top: (containerData.height - canvasData.height) / 2
                });

                cropper.cropper.options.viewMode = 2;

                cropper.setCropBoxData({
                    left: 0,
                    top: 0,
                    width: canvasData.width,
                    height: canvasData.height,
                });
            },

            setCropperData(data) {
                if ("rotate" in data) {
                    this.getCropper().setData({
                        rotate: data.rotate
                    });
                    this.fitImageToBox();
                    delete data.rotate;
                }

                this.getCropper().setData(data);
            },

            getCroppedImage() {
                return this.getCropper().getCroppedCanvas().toDataURL();
            },

            getClearedModifications() {
                let result = {};

                let defaultParams = this.getPreparedDefaultParams();

                for (let i in defaultParams) {
                    if (this.innerModifications[i] !== defaultParams[i]) {
                        result[i] = this.innerModifications[i];
                    }
                }

                return result;
            },

            setClearedModifications(modifications = {}) {
                let result = {};

                let defaultParams = this.getPreparedDefaultParams();

                for (let i in defaultParams) {
                    if (i in modifications && modifications[i] !== defaultParams[i]) {
                        result[i] = modifications[i];
                    }
                    else {
                        result[i] = defaultParams[i];
                    }
                }

                this.setModifications(result);
            },

            getModifications() {
                return {
                    ...this.innerModifications
                };
            },

            setModifications(modifications) {
                let result = {};

                for (let i in modifications) {
                    let int = parseInt(modifications[i]);

                    result[i] = isNaN(int) ? 0 : int;
                }

                this.innerModifications = result;
            },

            reset() {
                this.getCropper().reset();
                this.updateModifications();
            },

            getPreparedDefaultParams() {
                let imageData = this.getCropper().getCanvasData();

                if (!imageData) {
                    return {
                        ...this.defaultCropParams
                    };
                }

                return this.defaultCropParams = {
                    ...this.defaultCropParams,
                    width: imageData.naturalWidth,
                    height: imageData.naturalHeight
                };
            },

            ready() {
                this.loading = false;
                this.setClearedModifications(this.modifications);
                this.setCropperData(this.getModifications());
            }
        },

        created() {
            this.loading = true;
        }
    };
</script>

<template>
    <loading :loading="loading">
        <div style="overflow:hidden;padding:5px;margin:-5px;">
            <vuecropper
              ref="cropper"
              :guides="true"
              :view-mode="2"
              drag-mode="crop"
              :auto-crop-area="1"
              :background="true"
              :rotatable="true"
              :zoomable="false"
              :src="getImagePath()"
              :ready="ready"
              :cropend="cropend"
              style="max-height:500px;"
              :img-style="{ 'width': '100%' }"></vuecropper>
        </div>

        <div class="edit-image-panel text-center" style="margin-top: 20px">
          <a href="javascript:void(0)" class="btn btn-sm btn-primary" @click="invertX()">
            <i class="fa fa-arrows-h"></i>
          </a>

          <a href="javascript:void(0)" class="btn btn-sm btn-primary" @click="rotateLeft()">
              <i class="fa fa-rotate-left"></i>
          </a>

          <a href="javascript:void(0)" class="btn btn-sm btn-primary" @click="rotateRight()">
              <i class="fa fa-rotate-right"></i>
          </a>



            <!--<a href="javascript:void(0)" class="btn btn-sm btn-primary" @click="invertY()">-->
            <!--<i class="fa fa-arrows-v"></i>-->
            <!--</a>-->

            <a href="javascript:void(0)" class="btn btn-sm btn-primary" @click="reset()">
                <i class="fa fa-refresh"></i> Сбросить
            </a>
        </div>
    </loading>
</template>