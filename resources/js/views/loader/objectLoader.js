export function objectLoader() {
    this.id;
    this.admin;
    this.name;
    // 'file': '',
    // 'path': '',
    // 'extension': '',
    this.size;
    // 'hash': '',
    // 'url': '',
    // 'disk': '',
    this.status;
    this.operation;

    this.status_mes;
    this.operation_mes;

    this.last_row;
    // 'error_row': '',
    // 'add_row': '',
    // 'update_row': '',
    // 'delete_row': '',
    this.total_row;
    // 'start_load': '',
    // 'end_load': '',
    this.header;
    this.processed = 0;

    this.changeProcessed = function (pusher, nameChannel, callback, err_callback) {
        pusher.bind(nameChannel + this.id, log => {
            if (-1 === parseInt(log.message)) {
                callback.call();
            }
            else if(-2 === parseInt(log.message)) {
                if (typeof err_callback == 'function') {
                    err_callback.call();
                }
            }
            else if(-1 < parseInt(log.message)) {
                this.last_row = parseInt(log.message);
                this.processed = parseInt(this.last_row / this.total_row * 100);
            }
            else {
                console.log(log.message);
                if (typeof err_callback == 'function') {
                    err_callback.call();
                }
            }
        });
    }

    this.changeTotalRow = function (pusher, nameChannel, callback, err_callback) {
        pusher.bind(nameChannel + this.id, log => {
            if (-1 === parseInt(log.message)) {
                callback.call();
            }
            else if(-2 === parseInt(log.message)) {
                if (typeof err_callback == 'function') {
                    err_callback.call();
                }
            }
            else {
                this.total_row = log.message;
            }
        });
    }
}
