import request from '../utils/request_service';

export function getList() {
    return request({
        url: '/import/products/list/',
        method: 'get'
    });
}

export function load(data) {
    return request({
        url: '/import/products/load/',
        method: 'post',
        data: data,
    });
}

export function start(id) {
    return request({
        url: '/import/products/start/' + id,
        method: 'get',
    });
}

export function stop(id) {
    return request({
        url: '/import/products/stop/' + id,
        method: 'get',
    });
}

export function pause(id) {
    return request({
        url: '/import/products/pause/' + id,
        method: 'get',
    });
}

export function proceed(id) {
    return request({
        url: '/import/products/proceed/' + id,
        method: 'get',
    });
}

export function remote(id) {
    return request({
        url: '/import/products/remote/' + id,
        method: 'get',
    });
}

export function reload(id) {
    return request({
        url: '/import/products/reload/' + id,
        method: 'get',
    });
}

export function back(id) {
    return request({
        url: '/import/products/back/' + id,
        method: 'get',
    });
}

export function test() {
    return request({
        url: '/usersanctum/',
        method: 'get',
    });
}