<template>
    <div>
        <div class="block full">
            <div class="block-title clearfix">
                <h1><strong>Список Пользователей</strong></h1>
                <div class="block-title-control">
                    <b-button variant="success" @click="goCreateUser">Добавить пользователя</b-button>
                </div>
                <hr>
            </div>
            <div>
                <b-table
                    class="table table-vcenter table-condensed table-hover table-bordered dataTable no-footer"
                    striped
                    hover
                    show-empty
                    stacked="md"
                    :sort-by.sync="sortBy"
                    :sort-desc.sync="sortDesc"
                    :busy="isBusy"
                    :items="items"
                    :fields="fields"
                    :current-page="0"
                    :per-page="0"
                    empty-text="Список пользователей пуст."
                    empty-filtered-text="Пользователи с такими параметрами не найдены."
                    @sort-changed="sortingChanged"
                    @row-clicked="onRowClick"
                >

                    <template #cell(delete)="users">
                        <span class="table-column-image">
                          <div class="product-preview-image">
                            <b-button
                                variant="danger"
                                @click.stop="destroyCompaniesTable(users.item.id)"
                            >
                              <i class="el-icon-delete" />
                            </b-button>
                          </div>
                        </span>
                    </template>

                </b-table>
                <div class="row">
                    <div class="col-sm-12 col-xs-12 clearfix">
                        <div v-if="showPagination" class="dataTables_paginate paging_bootstrap">
                            <b-pagination
                                v-model="page"
                                :total-rows="totalRows"
                                :per-page="perPage"
                                class="my-0"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import { getListFranchiseUsers, delFranchiseUser } from '../../../api/income';

const countRows = 30;

export default {
    name: "usersTable",
    data: () => ({
        isBusy: false,
        items: [],
        fields: [
            {
                key: 'id',
                sortable: true,
                label: 'ID',
            },
            {
                key: 'first_name',
                sortable: false,
                label: 'Имя',
            },
            {
                key: 'last_name',
                sortable: false,
                label: 'Фамилия',
            },
            {
                key: 'phone',
                sortable: false,
                label: 'Телефон',
            },
            {
                key: 'email',
                sortable: false,
                label: 'Email',
            },
            {
                key: 'franchise',
                sortable: false,
                label: 'Франшиза',
            },
            {
                key: 'delete',
                sortable: false,
                label: 'Удалить',
            },
        ],
        totalRows: 0,
        lastPage: 0,
        sortDesc: false,
        sortBy: 'id',
        perPage: 30,
        page: 1,
        type: '0',
    }),
    computed: {
        showPagination() {
            return this.totalRows > countRows;
        },
    },
    watch: {
        page: function(newVal, oldVal) {
            if (newVal > 0 && newVal <= this.lastPage) {
                this.getListUsers();
            } else {
                this.page = oldVal;
            }
        },
    },
    created() {
        this.getListUsers();
    },
    methods: {
        async getListUsers() {
            let payload = {
                sortDesc: this.sortDesc,
                sortBy: this.sortBy,
                perPage: this.perPage,
                page: this.page,
            };

            try {
                const { data, links, meta } = await getListFranchiseUsers(payload);

                this.items = data;

                this.totalRows = parseInt(meta.total);
                this.page = parseInt(meta.current_page);
                this.perPage = parseInt(meta.per_page);
                this.lastPage = parseInt(meta.last_page);
            } catch (error) {
                console.log(error);
            }
        },

        sortingChanged(table) {
            this.page = table.page = 1;
            this.sortDesc = table.sortDesc;
            this.sortBy = table.sortBy;

            this.getListUsers();
        },
        onRowClick(item, index) {
            this.$router.push('/franchise/users/edit/' + item.id);
        },
        goCreateUser() {
            this.$router.push('/franchise/users/create');
        },
        async destroyCompaniesTable(user_id) {
            try {
                const { data } = await delFranchiseUser({ user_id: user_id});

                if (data.success) {
                    this.getListUsers();
                }
            } catch (error) {
                console.log(error);
            }
        }
    },
}
</script>

<style scoped>

</style>