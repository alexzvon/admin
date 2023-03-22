import Core from "../core";

export default {
    props: [
        "stageName"
    ],


    data() {
        return {
            stageValueName: false
        };
    },

    methods: {
        setStage() {
            if (this.stageName && this.stageValueName) {
                Core.stageHandler.set(this.stageName, this[this.stageValueName]);
            }
        },
    },

    created() {
        if (this.stageValueName) {
            this.$watch(this.stageValueName, this.setStage);
        }
    }
};