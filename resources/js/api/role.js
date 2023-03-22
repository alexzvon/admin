import request from '@/utils/request';

export function fetchList(query) {
    return request({
        url: '/role',
        method: 'get',
        params: query,
    });
}

export function fetchRole(id) {
    return request({
        url: '/role/' + id,
        method: 'get',
    });
}

// export function fetchPv(id) {
//     return request({
//         url: '/articles/' + id + '/pageviews',
//         method: 'get',
//     });
// }

export function createRole(data) {
    return request({
        url: '/role',
        method: 'post',
        data,
    });
}

export function updateRole(data, id) {
    return request({
        url: '/role/' + id,
        method: 'patch',
        data,
    });
}