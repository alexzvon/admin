export function getSearch(type, url, params) {
    switch (type) {
        case 'region':
            url = url +
                '?callback=' + params.callback +
                '&contentType=' + params.contentType +
                '&limit=' + params.limit +
                '&query=' + params.query +
                '&_=' + params._;
            break;
        case 'district':
            url = url +
                '?callback=' + params.callback +
                '&regionId=' + params.regionId +
                '&contentType=' + params.contentType +
                '&parentType=' + params.parentType +
                '&parentId=' + params.parentId +
                '&limit=' + params.limit +
                '&query=' + params.query +
                '&_=' + params._;
            break;
        case 'city':
            url = url +
                '?callback=' + params.callback +
                '&districtId=' + params.districtId +
                '&contentType=' + params.contentType +
                '&parentType=' + params.parentType +
                '&parentId=' + params.parentId +
                '&limit=' + params.limit +
                '&query=' + params.query +
                '&_=' + params._;
            break;
        case 'street':
            url = url +
                '?callback=' + params.callback +
                '&cityId=' + params.cityId +
                '&contentType=' + params.contentType +
                '&parentType=' + params.parentType +
                '&parentId=' + params.parentId +
                '&limit=' + params.limit +
                '&query=' + params.query +
                '&_=' + params._;
            break;
        case 'building':
            url = url +
                '?callback=' + params.callback +
                '&streetId=' + params.streetId +
                '&contentType=' + params.contentType +
                '&parentType=' + params.parentType +
                '&parentId=' + params.parentId +
                '&limit=' + params.limit +
                '&query=' + params.query +
                '&_=' + params._;
            break;
        default:
            url = '';
            break;
    }

    return $.ajax({url: url, type: 'get', dataType: 'jsonp'});
}