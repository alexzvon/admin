/*
  Очереди запросов.

  В какой-то момент все вышло из под контроля...

  Типы очередей:

  block - блокирует поступающие на обработку запросы, пока выполняется текущий.

  break - прерывает текущий запрос, если поступает следующий.

  iteration - все запросы выполняются по очереди.
*/

// todo: оформить нормально, если очереди действительно нужны.
// todo: добавить эвенты для очередей - начало - конец

//import Core from "./";
import _ from "lodash";
import apiRequest from "./apiRequest";

const availableTypes = [
    "block",
    "break",
    "iteration"
];

export default {
    __queues: {},

    makeQueue(type, name = false) {
        if (availableTypes.indexOf(type) === -1) {
            throw new Error(`Неизвестный тип очереди: ${type}.`);
        }

        if (!name) {
            throw new Error("Не задано имя очереди.");
        }

        if (name in this.__queues) {
            return this.__queues[name];
        }

        let queue;

        switch (type) {
            case "block":
                queue = new BlockQueue();
                break;

            case "break":
                queue = new BreakQueue();
                break;

            case "iteration":
                queue = new IterationQueue();
                break;
        }

        this.__queues[name] = queue;

        return queue;
    },

    addItem(name, asyncItem) {
        if (!(name in this.__queue)) {
            throw new Error("Не найдено имя очереди.");
        }

        return this.__queue[name].add(asyncItem);
    }
};

export class Queue {
    constructor() {
        this.__inProcess = false;
        this.__items = [];
        this.counter = 0;
    }

    add(asyncItem) {
        const item = this.__wrapAsync(asyncItem);
        this.__items.push(item);
        this.__run();

        return item;
    }

    clean() {
        this.__items = [];

        if (this.__itemInProcess) {
            this.__itemInProcess.abort();
            this.__itemInProcess = false;
        }
    }

    clear() {
        this.clean();
    }

    __run() {
        if (!this.__itemInProcess && this.__items.length) {
            this.__itemInProcess = this.__items[0];
            this.__items[0].start();
        }
    }

    __wrapAsync(asyncItem) {
        let itemClass = false;

        if (typeof asyncItem === "function") {
            itemClass = QueueFunction;
        }

        if (asyncItem instanceof apiRequest) {
            itemClass = QueueApiRequest;
        }

        if (!itemClass) {
            throw new Error("Неизвестный тип запроса.");
        }

        const item = new itemClass(asyncItem, () => {
            this.__remove(item);
            this.__run();
        });

        item.__id = this.counter++;

        return item;
    }

    __remove(item) {
        for (let i = 0; i < this.__items.length; i++) {
            if (item.__id === this.__items[i].__id) {
                this.__items.splice(i, 1);

                if (this.__itemInProcess && this.__itemInProcess.__id === item.__id) {
                    this.__itemInProcess = false;
                }

                break;
            }
        }
    }
}

export class BlockQueue extends Queue {
    add(asyncItem) {
        const item = this.__wrapAsync(asyncItem);

        if (!this.__items.length) {
            this.__items.push(item);
            this.__run();
        }

        return item;
    }
}

export class BreakQueue extends Queue {
    add(asyncItem) {
        if (this.__items.length) {
            this.__items[0].abort();
        }

        const item = this.__wrapAsync(asyncItem);
        this.__items.push(item);
        this.__run();

        return item;
    }
}

export class IterationQueue extends Queue {
}

class QueueItem {
    constructor(asyncItem, onBeforeDone = false) {
        this.status = true;
        this.asyncItem = asyncItem;
        this.onBeforeDone = onBeforeDone;
    }

    __beforeDone() {
        if (this.onBeforeDone) {
            this.onBeforeDone.apply(_, arguments);
        }
    }

    abort() {
        this.status = false;
        this.__beforeDone();
    }

    start() {
        let _ = this;

        _.asyncItem().then(function() {
            if (_.status) {
                _.__beforeDone.apply(_, arguments);

                if (typeof _.resolver === "function") {
                    _.resolver.apply(_, arguments);
                }
            }
        });
    }

    onDone() {
        return new Promise((resolve, reject) => {
            this.resolver = function() {
                resolve.apply(Promise, arguments);
            };
        });
    }
}

class QueueFunction extends QueueItem {
    start() {
        let _ = this;
        _.asyncItem().then(function() {
            _.__beforeDone.apply(_, arguments);
            if (typeof _.resolver === "function") {
                _.resolver.apply(_, arguments);
            }
        });
    }

    onDone() {
        let _ = this;

        return new Promise((resolve, reject) => {
            _.resolver = function() {
                if (_.status) {
                    resolve.apply(Promise, arguments);
                }
            };
        });
    }
}

class QueueApiRequest extends QueueItem {
    start() {
        let _ = this;

        _.asyncItem.any(function() {
            _.__beforeDone.call(_, _.asyncItem);
        });

        this.asyncItem.start();
    }

    abort() {
        this.__beforeDone();
        this.asyncItem.abort();
    }

    onDone() {
        return this.asyncItem;
    }
}

export class asyncPackage {
    constructor() {
        this.items = [];
        this.handlers = {};
        this.counter = 0;
    }

    add(asyncItem, handler) {

        let itemClass = false;

        if (typeof asyncItem === "function") {
            itemClass = QueueFunction;
        }

        if (asyncItem instanceof apiRequest) {
            itemClass = QueueApiRequest;
        }

        if (!itemClass) {
            throw new Error("Неизвестный тип запроса.");
        }

        this.__makeItem(asyncItem, handler, itemClass);

        return this;
    }

    __makeItem(asyncItem, handler, itemClass) {
        let _ = this;
        let item = new itemClass(asyncItem, function() {
            _.__makeHandler(item, handler);
            _.__checkDone();
        });

        item.__id = _.counter++;

        _.items.push(item);
    }

    __makeHandler(item, handler) {
        if (handler) {
            this.handlers[item.__id] = () => new Promise(resolve => {
                let result = handler.call(handler, item.onDone());

                if (result instanceof Promise) {
                    result.then(() => {
                        resolve();
                    });
                }
                else {
                    resolve();
                }
            });
        }
    }

    __checkDone() {
        if (--this.counter === 0) {
            this.__done();
        }
    }

    __done() {
        const queue = new IterationQueue();
        Object.keys(this.handlers).sort().forEach((index) => {
            queue.add(() => this.handlers[index]());
        });

        queue.add(() => new Promise(resolve => {
            this.__resolveOnDone();
            this.items = undefined;
            this.handlers = undefined;

            resolve();
        }));
    }

    __resolveOnDone() {
        if (typeof this.__onDone === "function") {
            this.__onDone();
        }
    }

    onDone(callback) {
        this.__onDone = callback;
    }

    start() {
        this.items.forEach(item => item.start());
    }
}

export class asyncPackageDataCollector extends asyncPackage {
    constructor() {
        super();
        this.data = {};
        this.__onDone = [];
    }

    __makeItem(asyncItem, handler, itemClass) {
        let _ = this;
        let item = new itemClass(asyncItem, function(data) {
            _.__pullData(data);
            _.__makeHandler(item, handler);
            _.__checkDone();
        });

        item.__id = _.counter++;

        _.items.push(item);
    }

    __pullData(response) {
        if (response instanceof apiRequest) {
            response.success(response => this.__collectData(response.data));
        }
        else if (typeof response === "object" && response !== null) {
            this.__collectData(response);
        }
    }

    __collectData(data) {
        this.data = {
            ...this.data,
            ...data
        };
    }

    __resolveOnDone() {
        this.__onDone.map(cb => cb(this.data));
    }

    onDone(callback) {
        if (typeof callback === "function") {
            this.__onDone.push(callback);
        }
    }
}