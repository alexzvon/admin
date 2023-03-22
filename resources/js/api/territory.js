import request from '../utils/request';

export function getListCities(params) {
    return request({
        url: '/franchise/territory/cities',
        method: 'get',
        params: params,
    });
}

export function search(params) {
    return request({
        url: '/franchise/territory/cities/search',
        method: 'get',
        params: params,
    });
}

export function createCities(data) {
    return request({
        url: '/franchise/territory/cities',
        method: 'post',
        data: data,
    });
}

export function getCitiesEdit(id) {
    return request({
        url: '/franchise/territory/cities/' + id,
        method: 'get',
    });
}

export function updateCities(id, data) {
    return request({
        url: '/franchise/territory/cities/' + id,
        method: 'patch',
        data: data
    });
}

export function getListCountry() {
    return request({
        url: '/franchise/territory/country',
        method: 'get',
    });
}

export function getListGmt() {
    return request({
        url: '/franchise/territory/gmt',
        method: 'get',
    });
}

export function createCity(data) {
    return request({
        url: '/franchise/territory/city',
        method: 'post',
        data: data,
    });
}

export function getCity(id) {
    return request({
        url: '/franchise/territory/city/' + id,
        method: 'get',
    });
}

export function updateCity(id, data) {
    return request({
        url: '/franchise/territory/city/' + id,
        method: 'patch',
        data: data
    });
}

export function getListCity(params) {
    return request({
        url: '/franchise/territory/city',
        method: 'get',
        params: params,
    });
}
