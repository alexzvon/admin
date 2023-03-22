//import Noty from 'noty'

const defaultParms = {
    type: "info",
    progressBar: false,
    theme: "bootstrap-v4",
    "timeout": 5000,
};

export default function notify(message, options = {}) {
    if (typeof message === "object" && message.text) {
        options = message;
    }
    else {
        options.text = message;
    }

    new Noty({
        ...defaultParms,
        ...options,
    }).show();
}