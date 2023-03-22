import request from '../utils/request';

export function createIncome(data) {
    return request({
        url: '/franchise/income/source/',
        method: 'post',
        data: data,
    });
}

export function getIncome(id) {
    return request({
        url: '/franchise/income/source/' + id,
        method: 'get',
    });
}

export function updateIncome(id, data) {
    return request({
        url: '/franchise/income/source/' + id,
        method: 'patch',
        data: data
    });
}

export function getListIncome(params) {
    return request({
        url: '/franchise/income/source/',
        method: 'get',
        params: params,
    });
}

//users

export function getListFranchiseUsers(payload) {
    return request({
        url: '/franchise/users/get/list/',
        method: 'get',
        params: payload,
    });
}

export function delFranchiseUser(payload) {
    return request({
        url: '/franchise/users/delete/user/',
        method: 'delete',
        params: payload,
    });
}

export function getFranchiseUser(user_id) {
    return request({
        url: '/franchise/users/get/user/' + user_id,
        method: 'get',
    });
}

export function updateFranchiseUser(payload) {
    return request({
        url: '/franchise/users/update/user/',
        method: 'post',
        data: payload,
    });
}

export function createFranchiseUser(payload) {
    return request({
        url: '/franchise/users/create/user/',
        method: 'post',
        data: payload,
    });
}

export function getListFranchiseUsersNotCompanies(user_id) {
    return request({
        url: '/franchise/users/list/users/' + user_id,
        methods: 'get',
    });
}