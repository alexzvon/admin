import request from '../utils/request';

export function getOptionsTree() {
    return request({
        url: '/shop/quick_filter/categories',
        method: 'get',
    });
}

export function createQuickFilter(data) {
    return request({
        url: '/shop/quick_filter',
        method: 'post',
        data: data,
    });
}

export function getQuickFilter(id) {
    return request({
        url: '/shop/quick_filter/' + id,
        method: 'get',
    });
}

export function updateQuickFilter(id, data) {
    return request({
        url: '/shop/quick_filter/' + id,
        method: 'patch',
        data: data
    });
}

export function getListQuickFilters(params) {
    return request({
        url: '/shop/quick_filter',
        method: 'get',
        params: params,
    });
}
