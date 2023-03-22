import notify from "./notify.js";
import requestHandler from "./apiRequest.js";
import storage from "./storage.js";
import dataHandler from "./dataHandler.js";
import stageHandler from "./stageHandler.js";
import transliteration from "./transliteration";
import queueHandler from "./queueHandler";
import user from "./user";
import EventsHandler from "./EventsHandler";
import {asyncPackageDataCollector} from "./queueHandler";
import _ from "lodash";

/**
 * todo: вынести функции, которые форматируют строки, цифры в отдельную область.
 * todo: пересмотреть все с учетом loadash
 */

export default {
    // ['отзыв', 'отзыва', 'отзывов']
    pluralize(value, titles) {
        let cases = [2, 0, 1, 1, 1, 2];
        return titles[(value % 100 > 4 && value % 100 < 20) ? 2 : cases[(value % 10 < 5) ? value % 10 : 5]];
    },

    translate(key) {
        return _.get(window.config.translates, key);
    },

    config(key) {
        return _.get(window.config, key);
    },

    /**
     * Запуск callback (с проверкой - функция ли это).
     *
     * @param callback
     */
    runCallback(callback) {
        if (typeof callback === "function") {
            callback.apply(callback, Array.prototype.slice.call(arguments, 1));
        }
    },

    /**
     * Первый символ в верхний регистр.
     *
     * @param string
     * @returns {string}
     */
    capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    },

    notify,
    requestHandler,
    storage,
    dataHandler,
    stageHandler,
    queueHandler,
    user,
    events: new EventsHandler(),

    transliteration,

    /**
     * Создание url на основе строки. (Включает в себя перевод с русского, удаление лишних симоволов).
     *
     * @param string
     * @returns {*}
     */
    makeUrl(string) {
        return this.transliteration(string.toLowerCase().replace("_", "-").replace(/\s/g, "-"), true).replace(/[^0-9a-zA-Z\-]+/g, "");
    },

    marketUrl(url = "", lang) {
        if (!url || url.indexOf("http") === 0) {
            return url;
        }

        return "https://candellabra.com/" + _.trim(url, "/");
    },

    /**
     * todo: Сделать удаление неограниченного кол-ва повторяющихся симоволов. Сделать возможность указывать стороны.
     *
     * @param str
     * @param symbol
     * @returns {string}
     */
    trim(str, symbol = " ") {
        let before = new RegExp(`${symbol}$`);
        let after = new RegExp(`^${symbol}`);

        return str.toString().replace(before, "").replace(after, "");
    },

    /**
     * todo: Что-то сделать с бородой ниже
     * @param value
     * @param precision
     * @returns {*}
     */
    formatPrice(value, currency) {
        let formator = new Intl.NumberFormat("ru-RU", {
            style: "currency",
            currency: currency.code,
            maximumFractionDigits: currency.precision
        });

        return this.formatNumber(value, currency.precision, formator);
    },

    formatNumber(number, precision, formator) {
        formator = formator || new Intl.NumberFormat("ru-RU", {
            maximumFractionDigits: precision
        });

        number = this.prepareNumberToFormat(number);
        number = formator.format(number);
        number = number.toString().replace(".", ",");

        return this.replaceUnnecessaryZeros(number, precision);
    },

    prepareNumberToFormat(number) {
        return (number || 0).toString().replace(",", ".");
    },

    replaceUnnecessaryZeros(value, precision) {
        return value.replace("," + "".padStart(precision, "0"), "");
    },

    /**
     * Преобразование строки в CamelCase регистр.
     *
     * @param str
     * @param capitalizeFirstLetter
     * @returns {*}
     */
    camelize(str, capitalizeFirstLetter = false) {
        let replaceFunc;
        if (capitalizeFirstLetter) {
            replaceFunc = letter => letter.toUpperCase();
        }
        else {
            replaceFunc = (letter, index) => {
                return index === 0 ? letter.toLowerCase() : letter.toUpperCase();
            };
        }

        return str.replace(/(?:^\w|[A-Z]|\b\w)/g, replaceFunc).replace(/[\_\-\s]+/g, "");
    },

    /**
     * Формирование объекта на основе непроверенных данных и данных по-умолчанию.
     *
     * todo: Это вряд-ли должно находится тут.
     *
     * @param data
     * @param defaultValues
     */
    combineDataWithDefault(data = {}, defaultValues = {}) {
        let result = {};

        for (let fieldName in defaultValues) {
            if (fieldName in data) {
                result[fieldName] = data[fieldName];
            }
            else {
                result[fieldName] = defaultValues[fieldName];
            }
        }

        return result;
    },

    /**
     * Создание уникального id.
     *
     * @param idStrLen
     * @returns {string}
     */
    uniqueId(idStrLen = 16) {
        let idStr = (Math.floor((Math.random() * 25)) + 10).toString(36) + "_";
        idStr += (new Date()).getTime().toString(36) + "_";

        do {
            idStr += (Math.floor((Math.random() * 35))).toString(36);
        } while (idStr.length < idStrLen);

        return (idStr);
    },

    generatePassword(length = 10) {
        // Исключены похожие символы и символы, недоступные на английской раскладке
        let chars = "abcdefghkmnpqrstuvwxyzABCDEFGHKMNPQRSTUVWXYZ23456789_-~!@#$%&*()+<>";

        return _.sampleSize(chars, length).join("");
    },

    imageIsLoaded(elImg) {
        if (!elImg.complete) {
            return false;
        }

        if (typeof elImg.naturalWidth !== "undefined" && elImg.naturalWidth === 0) {
            return false;
        }

        return true;
    },

    onImageLoaded(elImg, cb) {
        if (this.imageIsLoaded(elImg)) {
            this.runCallback(cb);
        }
        else {
            let onLoad = () => {
                elImg.removeEventListener("load", onLoad);
                this.runCallback(cb);
            };

            elImg.addEventListener("load", onLoad);
        }
    },

    /**
     * Добавление api_token пользователя в url.
     *
     * @param url
     * @returns {string}
     */
    addApiTokenToUrl(url) {
        if (url.indexOf("?") === -1) {
            url += "?";
        }
        else {
            url += "&";
        }

        return url + "api_token=" + this.getApiToken();
    },

    /**
     * Получение api_token текущего пользователя.
     *
     * @returns {string}
     */
    getApiToken() {
        return this.user.get("token");
    },

    init() {
        return new Promise(resolve => {
            const a = new asyncPackageDataCollector();

            a.add(() => this.user.init());

            a.onDone(data => {
                resolve();
            });

            a.start();
        });
    }
};