import request from '../utils/request';

export function getAddSearchProducts(payload) {
    return request({
        url: '/product/other/options/add/get/',
        method: 'post',
        data: payload,
    });
}

export function addOtherProductOption(payload) {
    return request({
        url: '/product/other/options/add/link/',
        method: 'post',
        data: payload,
    });
}

export function delOtherProductOption(payload) {
    return request({
        url: '/product/other/options/del/link/',
        method: 'post',
        data: payload,
    });
}

export function getOtherProductOption(product_id) {
    return request({
        url: '/product/other/options/get/other/' + product_id,
        method: 'get'
    });
}
