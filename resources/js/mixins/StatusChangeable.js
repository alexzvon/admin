import Core from '../core';

import Queueable from './Queueable';
import Page from './Page';

export default {
  mixins: [
    Page,
    Queueable,
  ],

  methods: {
    /**
     * Смена статуса записи.
     *
     * @param id
     */
    statusChange(id) {
      this.addToQueue('status', new Core.requestHandler('get', this.makePageApiUrl(`${id}/status`)));
    },
  },

  created() {
    this.addQueue('status', 'iteration');
  },
};

