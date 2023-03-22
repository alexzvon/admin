import Core from "../core";

import Base from "./Base";

const Queueable = {
    mixins: [
        Base
    ],

    data() {
        return {
            queues: [],
            queues$: {},
            isCreated$: false
        };
    },

    methods: {
        /**
         * Добавление очереди.
         *
         * @param name
         * @param type
         */
        addQueue(name, type) {
            this.queues.push({
                name,
                type
            });

            if (this.isCreated$) {
                this.createQueue(name, type);
            }
        },

        /**
         * Создание очередей.
         */
        createQueues() {
            this.queues.forEach(({name, type}) => this.createQueue(name, type));
        },

        /**
         * Создание очереди
         *
         * @param type
         * @param name
         */
        createQueue(name, type) {
            this.queues$[name] = Core.queueHandler.makeQueue(type, name);
        },

        /**
         * Добавление запроса в очередь.
         */
        addToQueue(queueName, request) {
            return this.queues$[queueName].add(request);
        },

        /**
         * Отчистка очередей.
         */
        clearQueues() {
            for (let i in this.queues$) {
                this.clearQueue(i);
            }
        },

        /**
         * Отчистка очереди.
         *
         * @param name
         */
        clearQueue(name) {
            this.queues$[name].clear();
        }
    },

    created() {
        this.$nextTick(() => {
            this.createQueues();
            this.isCreated$ = true;
        });
    },

    beforeDestroy() {
        this.clearQueues();
    }
};

export default Queueable;