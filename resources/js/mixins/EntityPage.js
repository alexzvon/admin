import Core from "../core";

import Validation from "./Validation";
import Page from "./Page";
import Queueable from "./Queueable";

import {asyncPackageDataCollector} from "../core/queueHandler";

import HandleableException from "../exceptions/HandleableException";

export default {
    mixins: [
        Validation,
        Page,
        Queueable
    ],

    props: [
        "type",
    ],

    data() {
        return {
            reloadDataOnSave: false
        };
    },

    methods: {
        /**
         * Загрузка данных.
         */
        loadData() {
            const a = new asyncPackageDataCollector();
            a.add(() => this.fetchMainData());

            if (this.type === "edit") {
                a.add(this.fetchEntity());
            }

            a.onDone(data => {
                this.initData(data);
            });

            this.$nextTick(() => {
                this.$emit("beforeDataLoad", a);

                a.start();
            });
        },

        /**
         * Загрузка данных модели.
         *
         * @returns apiResponse
         */
        fetchEntity() {
            return new Core.requestHandler("get", this.makePageApiUrl()).notFound(() => {
                Core.notify("Товар не найден.", {type: "warning"});
                this.redirectToTable();
            });
        },

        /**
         * Возвращает название сущности.
         *
         * @return string
         */
        getEntityName() {
            return this.entityName;
        },

        /**
         * Возвращает модель.
         *
         * @return {[type]} [description]
         */
        getEntityModel() {
            return this[Core.camelize(this.entityName)];
        },

        /**
         * Установка данных модели.
         *
         * @param data
         */
        setEntityData(data = {}) {
            if (this.entityName === 'category' && data.parent_id === 0) {
                data.parent_id = null;
            }

            this[Core.camelize(this.entityName)] = data;
        },

        /**
         * Возвращает подготовленные для сохранения данные.
         *
         * @returns {{}}
         */
        getToSaveData() {
            return {
                ... this.getEntityModel()
            };
        },

        /**
         * Инициализация данных.
         *
         * @param data
         */
        initData(data) {
            this.initMainData(data);
            this.initEntity(data[this.getEntityName()]);
        },

        /**
         * Инициализация модели сущности.
         *
         * @param data
         */
        initEntity(data = {}) {
            this.setEntityData(data);
        },

        getPathToTable() {
            switch (this.type) {
                case "create":
                    return this.$route.path.replace("/create", "");
                    break;
                case "edit":
                    return this.$route.path.replace("/" + this.id, "");
                    break;
            }
        },
        /**
         * Возврат к списку сущностей.
         */
        redirectToTable() {
            console.log("redirectToTable");
            let path = this.getPathToTable();

            if (window.history.state) {
                let previousPath = window.history.state.previousPath;

                if (previousPath && previousPath !== path && previousPath.indexOf(path) !== -1) {
                    this.$router.go(-1);
                    return;
                }
            }

            this.$router.push(path);
        },

        /**
         * Сохранение сущности.
         */
        save() {
            if (this.saveButtonDisabled) {
                Core.notify("Подождите, сохраняется.", {type: "warning"});
                return;
            }

            this.formErrors.clear();

            this.$validator.validateAll().then(result => {
                if (!result) {
                    if (this.formErrors.has('suppliers')) {
                        this.$refs.validationSuppliersModal.show();
                    } else {
                        this.$refs.validationModal.show();
                    }

                    return;
                }

                let data;

                try {
                    data = this.getToSaveData();
                }
                catch (e) {
                    if (e instanceof HandleableException) {
                        Core.notify(e.getNotifyParams());
                        return;
                    }
                    else {
                        throw e;
                    }
                }

                if (this.reloadDataOnSave !== false) {
                    data = {
                        ...data,
                        withData: this.usedMainData
                    };
                }

                let requestType;

                if (this.type === "create") {
                    requestType = "post";
                }

                if (this.type === "edit") {
                    requestType = "put";
                }

                this.disableSaveButton();

                const request = new Core.requestHandler(requestType, this.makePageApiUrl(), data)
                    .success(response => {
                        this.enableSaveButton();
                        if (this.type === "create") {
                            window.location.href = this.makePageUrl(response.data.id);
                        }

                        if (this.type === "edit") {
                            this.pullDataFromResponse(response);
                        }
                    })
                    .invalid(response => {
                        this.enableSaveButton();

                        let errors = response.data.errors;
                        let offset = 20;

                        for (var error in errors) {
                            if (errors.hasOwnProperty(error)) {
                                for (let i = 0; i < errors[error].length; i++) {
                                    this.$message(
                                        {
                                            showClose: true,
                                            message: errors[error][i],
                                            type: 'error',
                                            duration: 5000,
                                            offset: offset,
                                        }
                                    );

                                    offset += 20;
                                }
                            }
                        }
                    })
                    .fail(response => {
                        this.enableSaveButton();
                        let errors;

                        try {
                            errors = response.data.errors;
                        } catch (e) {
                        }

                        this.setValidationErrors(errors || []);

                        if (errors) {
                            this.$refs.validationModal.show();
                        }
                    })
                    .crash(() => {
                        this.enableSaveButton();
                    })
                    .notFound(() => {
                        this.enableSaveButton();
                        Core.notify("Товар был удален до внесения изменений.", {type: "warning"});
                        this.redirectToTable();
                    });

                this.addToQueue("save", request);
            });
        },

        /**
         * Клонирование сущности.
         */
        clone() {
            if (this.type === "create") {
                Core.notify("Нельзя клонировать не продукт, который ещё не был сохранён.", {type: "warning"});

                return;
            }
            this.formErrors.clear();

            this.$validator.validateAll().then(result => {
                if (!result) {
                    this.$refs.validationModal.show();
                    return;
                }

                let data;

                try {
                    data = this.getToSaveData();
                }
                catch (e) {
                    if (e instanceof HandleableException) {
                        Core.notify(e.getNotifyParams());
                        return;
                    }
                    else {
                        throw e;
                    }
                }

                if (this.reloadDataOnSave !== false) {
                    data = {
                        ...data,
                        withData: this.usedMainData
                    };
                }

                let requestType = "post";

                let url = this.makePageApiUrl() + '/clone';

                const request = new Core.requestHandler(requestType, url, []).success(response => {
                    if (this.type === 'create') {
                        Core.notify('Товар был скопирован успешно.', {type: 'success'});
                        setTimeout(function () {
                            window.location.href = window.location.href.replace(/\d+$/, '') + response.data.id;
                        }, 1500);

                    }

                    if (this.type === 'edit') {
                        Core.notify('Товар был скопирован успешно.', {type: 'success'});
                        setTimeout(function () {
                            window.location.href = window.location.href.replace(/\d+$/, '') + response.data.id;
                        }, 1500);
                    }
                }).fail(response => {
                    let errors;

                    try {
                        errors = response.data.errors;
                    }
                    catch (e) {
                    }

                    this.setValidationErrors(errors || []);

                    if (errors) {
                        this.$refs.validationModal.show();
                    }
                }).notFound(() => {
                    Core.notify('Товар был удален до внесения изменений.', {type: 'warning'});
                    this.redirectToTable();
                });

                this.addToQueue('clone', request);
            });
        },

        /**
         * Инициализация данных модели, полученных из ответ сервера.
         *
         * @param response
         */
        pullDataFromResponse(response) {
            if (this.reloadDataOnSave) {
                this.initData({
                    [this.getEntityName()]: response.data[this.getEntityName()],
                    ...response.data.withData
                });
            }

            this.initEntity(response.data[this.getEntityName()]);
        },

        /**
         * Показ модального окна перед удалением записи.
         */
        remove() {
            var _ = this;

            if (this.type !== "edit") return;

            this.$refs.removeModal.show();
        },

        /**
         * При подтвержении удаления записи.
         */
        removeConfirm() {
            new Core.requestHandler("delete", this.makePageApiUrl()).success(() => {
                this.redirectToTable();
            }).start();
        },

        /**
         * Сброс компонента.
         */
        reset() {
            this.clearQueues();
        },

        disableSaveButton()
        {
            this.saveButtonDisabled = true;
        },

        enableSaveButton()
        {
            this.saveButtonDisabled = false;
        }
    },

    created() {
        //this.redirectToTable();

        this.addQueue('save', 'break');
        this.addQueue('clone', 'break');
        this.loadData();
        this.extendSlugChecker();
    },
};