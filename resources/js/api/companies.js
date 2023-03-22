import request from '../utils/request';

export function getListCity() {
    return request({
        url: '/franchise/companies/cityNotPagination',
        method: 'get',
    });
}

export function getListUsers() {
    return request({
        url: '/franchise/companies/users',
        methods: 'get',
    });
}

export function createCompanies(data) {
    return request({
        url: '/franchise/companies/companies',
        method: 'post',
        data: data,
    });
}

export function getCompany(id) {
    return request({
        url: '/franchise/companies/companies/' + id,
        method: 'get',
    });
}

export function getListIncome() {
    return request({
        url: '/franchise/companies/incomeNotPagination',
        method: 'get',
    });
}

export function updateCompanies(id, data) {
    return request({
        url: '/franchise/companies/companies/' + id,
        method: 'patch',
        data: data
    });
}

export function getListCompanies(params) {
    return request({
        url: '/franchise/companies/companies/',
        method: 'get',
        params: params,
    });
}

export function destroyCompanies(id) {
    return request({
        url: '/franchise/companies/companies/' + id,
        method: 'delete',
    });
}
