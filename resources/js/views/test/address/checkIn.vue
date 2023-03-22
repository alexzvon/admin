<template>
    <el-container>
        <el-header>
            header
        </el-header>
        <el-main>

            <!--        <el-select-->
            <!--            v-model="value"-->
            <!--            multiple-->
            <!--            filterable-->
            <!--            remote-->
            <!--            reserve-keyword-->
            <!--            placeholder="Please enter a keyword"-->
            <!--            :remote-method="remoteMethod"-->
            <!--            :loading="loading">-->
            <!--            <el-option-->
            <!--                v-for="item in options"-->
            <!--                :key="item.value"-->
            <!--                :label="item.label"-->
            <!--                :value="item.value">-->
            <!--            </el-option>-->
            <!--        </el-select>-->

            <el-form>
                <el-form-item label="Регион">
                    <el-select
                        v-model="value"
                        filterable
                        remote
                        reserve-keyword
                        placeholder=""
                        :remote-method="searchRegion"
                        :loading="loading">
                        <el-option
                            v-for="item in options"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                        </el-option>
                    </el-select>
                </el-form-item>
            </el-form>


        </el-main>


        <el-footer>
            footer
        </el-footer>
    </el-container>
</template>


<script>
'use not strict';

import { getSearch } from "../../../api/kladr";

export default {
    name: "checkIn",
    data() {
        return {
            limit: 10,
            current: 1,
            url: 'https://kladr-api.ru/api.php',



            options: [],
            value: [],
            list: [],
            loading: false,
            states: ["Alabama", "Alaska", "Arizona",
                "Arkansas", "California", "Colorado",
                "Connecticut", "Delaware", "Florida",
                "Georgia", "Hawaii", "Idaho", "Illinois",
                "Indiana", "Iowa", "Kansas", "Kentucky",
                "Louisiana", "Maine", "Maryland",
                "Massachusetts", "Michigan", "Minnesota",
                "Mississippi", "Missouri", "Montana",
                "Nebraska", "Nevada", "New Hampshire",
                "New Jersey", "New Mexico", "New York",
                "North Carolina", "North Dakota", "Ohio",
                "Oklahoma", "Oregon", "Pennsylvania",
                "Rhode Island", "South Carolina",
                "South Dakota", "Tennessee", "Texas",
                "Utah", "Vermont", "Virginia",
                "Washington", "West Virginia", "Wisconsin",
                "Wyoming"]
        }
    },
    mounted() {
        this.list = this.states.map(item => {
            return {value: `value:${item}`, label: `label:${item}`};
        });
    },
    methods: {

        async searchRegion(query) {
            let params = {
                query: query,
                contentType: 'region',
//                callback: 'responseRegion',
                limit: this.limit,
                _: this.current++,
            };

            let data = await getSearch('region', this.url, params);

            console.log(data);




//            callback();
//            console.log(data);
        },

        responseRegion(result) {
            console.log('responseRegion(searchContext, result)');
            console.log(result);
        },

        changeMethod() {
            console.log('changeMethod');
        },

        remoteMethod(query) {

            console.log(query);
            console.log(e);
            console.log(o);





            return;
        }
    }
}
</script>

<style scoped lang="scss">
    li.el-select-dropdown__item.hover {
        background-color: #0d95e8;
    }
</style>