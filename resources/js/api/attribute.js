import request from '../utils/request';

export function getAttributes(product_id) {
    return request({
        url: '/product/attribute/get/' + product_id,
        method: 'get',
    });
}

export function getGlobalAttributes(product_id){
    return request({
        url: '/product/attribute/get_global/' + product_id,
        method: 'get',
    });
}

export function getAddAttributes(product_id) {
    return request({
        url: '/product/attribute/get/add/' + product_id,
        method: 'get',
    });
}

export function getAddGlobalAttributes(product_id) {
    return request({
        url: '/product/attribute/get_global/add/' + product_id,
        method: 'get',
    });
}

export function getAllAttributes() {
    return request({
        url: '/product/attribute/all/',
        method: 'get',
    });
}

export function getAddOptionsAttributeProduct(payload) {
    return request({
        url: '/product/attribute/option/get/add/',
        method: 'post',
        data: payload,
    });
}

export function addOptionToAttribute(payload) {
    return request({
        url: '/product/attribute/option/add/',
        method: 'post',
        data: payload,
    });
}

export function createAttributeProduct(payload) {
    return request({
        url: '/shop/attributor/create/',
        method: 'post',
        data: payload,
    });
}

export function updateStateAttributeRealation(payload) {
    return request({
        url: '/product/attribute/update/state',
        method: 'post',
        data: payload
    });
}

export function deleteAttribute(rel) {
    return request({
        url: '/product/attribute/delete/' + rel,
        method: 'post',
    });
}

export function deleteOption(id) {
    return request({
        url: '/product/attribute/option/delete/' + id,
        method: 'post',
    });
}

