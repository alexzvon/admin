import request from '../utils/request';

export function createShowRoomGroup(data) {
    return request({
        url: '/franchise/showrooms/group',
        method: 'post',
        data: data,
    });
}

export function showShowRoomGroup(id) {
    return request({
        url: '/franchise/showrooms/group/' + id,
        method: 'get'
    });
}

export function updateShowRoomGroup(id, data) {
    return request({
        url: '/franchise/showrooms/group/' + id,
        method: 'patch',
        data: data,
    });
}

export function getListShowRoomGroup() {
    return request({
        url: '/franchise/showrooms/group',
        method: 'get',
    });
}

export function getListRegionsTop() {
    return request({
        url: '/franchise/showrooms/group/create',
        method: 'get',
    });
}

export function getListCitiesTopRegion(region_id) {
    return request({
        url: '/franchise/territory/cities/ctr/' + region_id,
        method: 'get',
    });
}

export function createRoom(data) {
    return request({
        url: '/franchise/showrooms/room',
        method: 'post',
        data: data,
    });
}

export function getListRooms(group_id) {
    return request({
        url: '/franchise/showrooms/room/' + group_id,
        method: 'get',
    });
}

export function getEditRoom(id) {
    return request({
        url: '/franchise/showrooms/room/' + id + '/edit',
        method: 'get',
    });
}

export function updateShowRoom(id, data) {
    return request({
        url: '/franchise/showrooms/room/' + id,
        method: 'patch',
        data: data,
    });
}

export function saveVideo(id, data) {
    return request({
        url: '/franchise/showrooms/room/' + id + '/video',
        method: 'post',
        data: data,
    });
}
