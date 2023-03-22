import Core from "../core";

import Queueable from "./Queueable";
import Base from "./Base";

export default {
    mixins: [
        Base,
        Queueable
    ],

    data() {
        return {
            ids$: false,
            sortEls: [],
            sortableParams: false
        };
    },

    methods: {
        getSortParams() {
            return this.sortableParams || this.getDefaultSortParams();
        },

        getDefaultSortParams() {
            return {
                items: ".js-sort-item",
                handle: ".js-sort-handler",
                opacity: 0.9,
                start: function(e, helper) {
                    let height = helper.item.height();
                    helper.placeholder.css({
                        height,
                        visibility: "visible"
                    });
                },
                stop: this.changePosition,
            };
        },

        selectSortJqueryEls() {
            return $(this.sortableParams.mainSelector || ".ui-sortable");
        },

        /**
         * Инициализация сортировки.
         */
        initSort() {
            this.$nextTick(() => {
                this.sortEls = this.selectSortJqueryEls().sortable(this.getSortParams());
            });

            this.setCurrentSortIds();
        },

        /**
         * Обновить компонент сортировки. (Если добавлена новая опция - чтобы ее можно было двигать).
         */
        refreshSort() {
            let els = this.getInitializedSortEls();

            if (els.length > 0) {
                els.sortable("refresh");
            }
            else {
                this.initSort();
            }
        },

        /**
         * Возвращает список jQuery элементов с инициализированной сортировкой.
         *
         * @returns {*|jQuery|boolean}
         */
        getInitializedSortEls() {
            return this.sortEls;
        },

        /**
         * Сбор id элементов в правильном порядке
         *
         * @returns {any[]}
         */
        collectSortIds() {
            return [].map.call(document.querySelectorAll(this.getSortParams()["idsInputSelector"] || "[name=\"ids\"]"), el => {
                return el.value.toString();
            });
        },

        /**
         * Сортировка набора данных на основе порядка id элементов.
         *
         * @param dataBundle
         * @param ids
         * @returns {*}
         */
        sortDataBundleByIdsPosition(dataBundle, ids) {
            return dataBundle.reduce((acc, item) => {
                let index = acc.indexOf(item.id.toString());

                acc[index] = item;

                return acc;
            }, ids);
        },

        /**
         * Установка позиций в наборе данных.
         *
         * @param dataBundle
         * @param ids
         * @returns {*}
         */
        setDataBundlePositionsByIds(dataBundle, ids) {
            return dataBundle.map(item => {
                return {
                    ...item,
                    position: ids.indexOf(item.id.toString())
                };
            });
        },

        setCurrentSortIds(ids = false) {
            if (!ids) {
                ids = JSON.stringify(this.collectSortIds());
            }
            else if (typeof ids === "object") {
                ids = JSON.stringify(ids);
            }

            this.ids$ = ids;
        },

        sortIdsIsChanged(ids) {
            let idsJson = JSON.stringify(ids);

            if (this.ids$ !== idsJson) {
                this.setCurrentSortIds(idsJson);
                return true;
            }

            return false;
        },

        /**
         * Запрос на изменение позиций
         */
        changePosition() {
            let ids = this.collectSortIds();

            if (!this.sortIdsIsChanged(ids)) return;

            this.addToQueue("sort", new Core.requestHandler("post", this.makePageApiUrl("sort"), {ids}));
        },
    },

    created() {
        this.addQueue("sort", "break");
    },

    mounted() {
        this.initSort();
    },

    updated() {
        this.initSort();
    },

    beforeDestroy() {
        if (this.sortEls.length) {
            this.sortEls.sortable("destroy");
        }
    },
};