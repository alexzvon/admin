import Core from "../core";

export default {
    methods: {
        userCan(action, addNamespace = true) {
//            if (this.rbacNamespace && addNamespace) {
//                action = ([this.rbacNamespace, action]).join(".");
//            }
            return Core.user.can(action);
        },
    },
};