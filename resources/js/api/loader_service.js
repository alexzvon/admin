import request from '../utils/request_service';

export function getList() {
    return request({
        url: '/loader/list/',
        method: 'get'
    });
}

export function load(data) {
    return request({
        url: '/loader/load/',
        method: 'post',
        data: data,
    });
}

export function start(id) {
    return request({
        url: '/loader/start/' + id,
        method: 'get',
    });
}

export function stop(id) {
    return request({
        url: '/loader/stop/' + id,
        method: 'get',
    });
}

export function pause(id) {
    return request({
        url: '/loader/pause/' + id,
        method: 'get',
    });
}

export function proceed(id) {
    return request({
        url: '/loader/proceed/' + id,
        method: 'get',
    });
}

export function remote(id) {
    return request({
        url: '/loader/remote/' + id,
        method: 'get',
    });
}

export function reload(id) {
    return request({
        url: '/loader/reload/' + id,
        method: 'get',
    });
}

export function back(id) {
    return request({
        url: '/loader/back/' + id,
        method: 'get',
    });
}