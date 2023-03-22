import request from '../utils/request';

export function getList(query) {
    return request({
        url: '/shop/reply/list',
        method: 'get',
        params: query,
    });
}

export function getEdit(return_id, product_id) {
    return request({
        url: '/shop/reply/edit/' + return_id + '/' + product_id,
        method: 'get',
    });
}

export function saveEdit(data) {
  return request({
    url: '/shop/reply/save',
    method: 'post',
    data: data
  });
}

