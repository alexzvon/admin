import HistoryStateTable from "./HistoryStateTable";

export default {
    mixins: [
        HistoryStateTable
    ],

    methods: {
        loadData() {
            this.fetchMainData().then(data => this.initMainData(data));
        },

        sortingChanged(ctx) {
            ctx.page = 1;
        },

        search(phrase) {
            if (this.searchPhrase != phrase) {
                this.searchPhrase = phrase;
            }

            this.page = 1;
            this.refreshTable();
        },

        getValidSearchPhrase(value) {
            return decodeURIComponent(value);
        },

        setPerPage(value) {
            this.perPage = value;
            this.page = 1;
        },

        refreshTable() {
            this.$nextTick(() => {
                if (this.$refs.table) {
                    this.$refs.table.refresh();
                }
            });
        },

        nanToNum(value, num = 0) {
            return isNaN(value) ? num : value;
        }
    }
};