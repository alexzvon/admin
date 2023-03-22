<template>
    <div style="display: inline-block">
        <div class="btn btn-sm btn-prime"
             @click="showImportModal">
            Импорт из Excel
        </div>
        <b-modal
                id="importModal"
                ref="importModal"
                title="Импорт из Excel"
                title-tag="h3"
                centered
                ok-title="Импортировать"
                :ok-disabled="!importFile"
                cancel-title="Отмена"
                hide-header-close
                @ok="importConfirm">

            <form id="import-form" ref="importForm" enctype="multipart/form-data" method="POST">
                <input type="hidden" name="MAX_FILE_SIZE" value="30000000000"/>
                <input
                        type="file"
                        accept=".xls,.xlsx"
                        name="import-excel"
                        id="import-excel"
                        @change="processFile($event)"
                />
            </form>
        </b-modal>

        <b-modal
                id="schemaUnequal"
                ref="schemaUnequal"
                title="Схема не совпадает"
                title-tag="h3"
                centered
                ok-title="Продолжить"
                cancel-title="Отмена"
                hide-header-close
                @ok="startImport">

            Схема вашего файла и базы данных не совпадает.
            Продолжить в любом случае?
        </b-modal>

        <b-modal
                id="successImportModal"
                ref="successImportModal"
                title="Импорт прошел успешно"
                title-tag="h3"
                centered
                ok-title="OK"
                ok-only
                hide-header-close>
            <template slot="default">
                <div v-if="imported.created.length || imported.updated.length">
                    <p>Импрот прошел успешено:</p>
                    <p>&nbsp;&nbsp;- добавлено: {{ imported.created.length }} продукт(ов)</p>
                    <p>&nbsp;&nbsp;- обновлено: {{ imported.updated.length }} продукт(ов)</p>
                    <br />
                </div>
                <div v-if="imported.failed.length">
                    <h3>Были ошибки при импорте продуктов со следующими id:</h3>
                    <ul>
                        <li v-for="object in imported.failed">
                             {{ object.id }}
                        </li>
                    </ul>
                </div>
            </template>
        </b-modal>

        <b-modal
                id="errorImportModal"
                ref="errorImportModal"
                title="Произошла ошибка"
                title-tag="h3"
                centered
                ok-title="OK"
                ok-only
                hide-header-close>
            В процессе импорта данных из файла: <b>{{ importFile.name }}</b>
            произошла ошибка.
        </b-modal>

        <b-modal
                id="loadingModal"
                ref="loadingModal"
                title="Происходит импорт"
                title-tag="h3"
                centered
                ok-title="OK"
                ok-only
                hide-header-close>
            <h2>
                Ожидайте
            </h2>
            <p v-if="process.total">
                Обработано {{ process.processed }} из {{ process.total }} строк
            </p>
            <p v-if="process.total">
                Успешно: {{ process.success }} <br />
                С ошибкой: {{ process.failed }}
            </p>
            <div class="loading-wrap__spinner">
                <i class="fa fa-spinner fa-4x fa-spin"></i>
            </div>
        </b-modal>
    </div>
</template>

<script>
    import Core from '../../../../core';
//    import bModal from "bootstrap-vue/es/components/modal/modal";

//    module.exports = {

export default {
        name: "ImportBtn",
        props: [],
        components: {
//            bModal,
        },
        created () {
            let channel = this.$pusher.subscribe('my-app.import');

            channel.bind('product-import-failed', log => {
                this.process.processed++;
                this.process.failed++;
            });

            channel.bind('product-import-success', log => {
                this.process.processed++;
                this.process.success++;
            });
        },
        data() {
            return {
                importFile: '',
                imported: {
                    created: [],
                    updated: [],
                    failed: [],
                },
                process: {
                    total: 0,
                    processed: 0,
                    failed: 0,
                    success: 0,
                },
            };
        },
        computed: {},
        methods: {
            showImportModal() {
                this.$refs.importModal.show();
            },
            importConfirm() {
                let _this = this;

                let formData = new FormData();
                formData.append('importFile', this.importFile);

                axios.post('/api/import/check-schema?api_token=' + this.$core.getApiToken(),
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        }
                    }
                ).then(function (data) {
                    if (!data.data.data.is_equal) {
                        _this.$refs.schemaUnequal.show();
                    } else {
                        alert('start import');
                        _this.startImport();
                    }

                    _this.process.total = data.data.data.total;
                })
            },
            startImport() {
                let _this = this;

                let formData = new FormData();
                formData.append('importFile', this.importFile);
                _this.$refs.loadingModal.show();

                axios.post('/api/import?api_token=' + this.$core.getApiToken(),
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        }
                    }
                ).then(function (data) {
                    try {
                        if (data.status === 200) {
                            _this.imported = data.data.data.imported;
                            _this.imported.created = data.data.data.imported.created || [];
                            _this.imported.updated = data.data.data.imported.updated || [];
                            _this.imported.failed = data.data.data.imported.failed || [];
                            _this.showSuccessModal();
                        } else {
                            _this.showErrorModal();
                        }
                    } catch (e) {
                        Core.notify('Техническая ошибка. Обратитесь к разработчикам.', {type: 'error', timeout: false});
                        _this.showErrorModal();
                    }
                }).catch(() => {
                    Core.notify('Техническая ошибка. Обратитесь к разработчикам.', {type: 'error', timeout: false});
                    _this.showErrorModal();
                }).finally(() => {
                    this.process =  {
                        total: 0,
                            processed: 0,
                            failed: 0,
                            success: 0,
                    };
                });
            },
            processFile(event) {
                this.importFile = event.target.files[0];
            },

            showErrorModal() {
                this.$refs.errorImportModal.show();
            },

            showSuccessModal() {
                this.$refs.successImportModal.show();
            }
        }
    };
</script>

<style lang="scss">

</style>
