<template>
    <el-container>
        <el-header height>
            <div class="info-reply">
                <span class="h2">Создать пользователя партнера</span>
                <div>
                    <el-button size="small" type="primary" @click="createUser">Сохранить</el-button>
                    <el-button size="small" @click="turnBack">Список</el-button>
                </div>
            </div>
        </el-header>
        <el-main style="width:96%;">
            <div class="info-reply">
                <span class="h2">Имя</span>
                <el-input
                    v-model="first_name"
                    placeholder="Please input"
                    clearable
                />
            </div>
            <div class="info-reply">
                <span class="h2">Фамилмя</span>
                <el-input
                    v-model="last_name"
                    placeholder="Please input"
                    clearable
                />
            </div>
            <div class="info-reply">
                <span class="h2">Телефон</span>
                <el-input
                    v-model="phone"
                    placeholder="Please input"
                    clearable
                />
            </div>
            <div class="info-reply">
                <span class="h2">E-mail</span>
                <el-input
                    v-model="email"
                    placeholder="Please input"
                    clearable
                />
            </div>
            <div class="info-reply">
                <span class="h2">Пароль</span>
                <el-input
                    v-model="password"
                    placeholder="Please input"
                    clearable
                    show-password
                />
            </div>
            <div class="info-reply_bottom">
                <span class="h2">Повторить пароль</span>
                <el-input
                    v-model="confirm_password"
                    placeholder="Please input"
                    clearable
                    show-password
                />
            </div>
        </el-main>
    </el-container>
</template>

<script>

import { createFranchiseUser } from '../../../api/income';

export default {
    name: "usersCreate",
    data: () => ({
        first_name: '',
        last_name: '',
        phone: '',
        email: '',
        password: '',
        confirm_password: '',
        is_franchisee: true,
        is_designer: false,
    }),
    methods: {
        validationForm() {
            let result = true;

            this.first_name = !!this.first_name ? this.first_name.trim() : '';
            this.last_name = !!this.last_name ? this.last_name.trim() : '';
            this.phone = !!this.phone ? this.phone.trim() : '';
            this.email = !!this.email ? this.email.trim() : '';

            result = !!this.first_name &&
                !!this.last_name &&
                !!this.phone &&
                !!this.email &&
                !!this.password &&
                !!this.confirm_password;

            if (!result) {
                this.$message.error('Все поля должны быть заполнены');
            }

            if (this.password !== this.confirm_password) {
                this.$message.error('Пароль не подтвержден');
            }

            return result;
        },
        async createUser() {
            if (this.validationForm()) {

                let payload = {
                    first_name: this.first_name,
                    last_name: this.last_name,
                    phone: this.phone,
                    email: this.email,
                    password: this.password,
                    confirm_password: this.confirm_password,
                    is_franchisee: this.is_franchisee,
                    is_designer: this.is_designer,
                };

                try {
                    const { data } = await createFranchiseUser(payload);

                    if (!!data.id) {
                        this.$router.push('/franchise/users/edit/' + data.id);
                    }
                } catch (error) {
                    console.log(error);
                }
            }
        },
        turnBack() {
            this.$router.push('/franchise/users/table');
        }
    },
}
</script>

<style scoped>
.info-reply {
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0px 1px 4px -2px rgb(0 0 0 / 55%);
    margin-bottom: 20px;
}
.info-reply_bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 1px 4px -2px rgba(0, 0, 0, 0.55);
}
.h2 {
    display: inline-block;
    font-size: 15px;
    line-height: 1.4;
    margin: 0;
    padding: 10px 16px 7px;
    font-weight: 600;
}
.el-main {
    margin: 20px;
    width: 50%;
    /* border: 1px solid black; */
    box-shadow: 0px 1px 4px -2px rgb(0 0 0 / 55%);
}
.el-select,.el-input {
    width: 480px !important;
}
</style>