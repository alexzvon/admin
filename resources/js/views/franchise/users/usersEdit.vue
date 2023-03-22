<template>
    <el-container>
        <el-header height>
            <div class="info-reply">
                <span class="h2">Редактирование пользователя партнёра #{{user_id}}</span>
                <div>
                    <el-button size="small" type="primary" @click="updateUser">Сохранить</el-button>
                    <el-button size="small" @click="turnBack">Список</el-button>
                </div>
            </div>
        </el-header>
<!--        <el-main style="width: auto; padding: 0;">-->
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

import {getFranchiseUser, updateFranchiseUser} from '../../../api/income';

export default {
    name: "usersEdit",
    data: () => ({
        user_id: 0,
        first_name: '',
        last_name: '',
        phone: '',
        email: '',
        password: '',
        confirm_password: '',
        is_franchise: true,
        is_designer: false,
    }),
    created() {
        this.user_id = this.$route.params.id;
        this.loadUser();
    },
    methods: {
        validationForm() {
            let result = true;

            this.first_name = !!this.first_name ? this.first_name.trim() : '';
            this.last_name = !!this.last_name ? this.last_name.trim() : '';
            this.phone = !!this.phone ? this.phone.trim() : '';
            this.email = !!this.email ? this.email.trim() : '';

            result = !!this.first_name && !!this.last_name && !!this.phone && !!this.email;

            if (!!this.password) {
                if (this.password !== this.confirm_password) {
                    this.$message.error('Пароль не подтвержден');
                    return false;
                }
            }

            if (!result) {
                this.$message.error('Все поля должны быть заполнены');
            }

            return result;
        },
        async loadUser() {
            try {
                const { data } = await getFranchiseUser(this.user_id);

                this.first_name = data.first_name;
                this.last_name = data.last_name;
                this.phone = data.phone;
                this.email = data.email;
            } catch (error) {
                console.log(error);
            }
        },
        async updateUser() {
            if (this.validationForm()) {
                let payload = {
                    user_id: this.user_id,
                    first_name: this.first_name,
                    last_name: this.last_name,
                    phone: this.phone,
                    email: this.email,
                    password: this.password,
                    confirm_password: this.confirm_password,
                };

                try {
                    const { data } = await updateFranchiseUser(payload);

                    if (data.success) {
                        this.$router.push('/franchise/users/table');
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

<style scoped lang="scss">
    .info-reply_bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 1px 4px -2px rgba(0, 0, 0, 0.55);
    }

</style>