import Core from "./";
import deepFreeze from "deep-freeze";

export default {
    __user: null,

    isSuperAdmin() {
        return this.__user.isSuper;
    },

    get(param) {
        return this.__user[param];
    },

    getId() {
        return parseInt(this.__user.id);
    },

    can(action) {
//      if (this.isSuperAdmin()) { return true; }
        return this.hasPermission(action);
    },

    hasPermission(action) {
        if (this.__user.permissions.indexOf(action) === -1) {
            console.warn('not permitted', action);
        }
//      if (this.isSuperAdmin()) { return true; }
        return this.__user.permissions.indexOf(action) !== -1;
    },

    init() {
        return new Promise(resolve => {
            if (this.__user === null) {
                this.__user = window.config.user;
                deepFreeze(this.__user);
            }
            resolve();
        });
    }
};