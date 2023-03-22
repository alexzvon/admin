import Core from '../core'

import Page from './Page'
import {asyncPackageDataCollector} from "../core/queueHandler";

export default {
  mixins: [
    Page
  ],

  data() {
    return {
      tableItemsDataName: 'items',
      items: []
    }
  },

  methods: {
    loadData() {
      const a = new asyncPackageDataCollector()
      a.add(() => this.fetchMainData())
      a.add(this.fetchItems())

      a.onDone(data => {
        this.initData(data)
      })

      a.start()
    },

    initData(data = {}) {
      this.initMainData(data)
      this.initItems(data[this.tableItemsDataName])
    },

    /**
     * Инициализация списка.
     */
    initItems (items = []) {
      this.items = items
    },

    /**
     * Загрузка списка.
     */
    fetchItems() {
      return new Core.requestHandler('get', this.makePageApiUrl())
    },
    /**
     * Показ окна для удаления записи.
     *
     * @param id
     */
    remove(id) {
      let _ = this;

      this.toRemoveId = id
      this.$refs.removeModal.show()
    },

    /**
     * Удаление записи.
     */
    removeConfirm() {
      new Core.requestHandler('delete', this.makePageApiUrl(`${this.toRemoveId}`))
        .success(() => this.refreshTable())
        .start()
    },

    /**
     * Обновление списка записей.
     */
    refreshTable() {
      this.fetchItems()
        .success(response => this.initItems(response.data[this.tableItemsDataName]))
        .start();
    },
  },

  created() {
    this.loadData()
  }
}