import axios from 'axios';
import Core from '../core';
import { getToken, setToken } from './auth';
import { ParseError } from "./parseerrormessage";

const service = axios.create({
  baseURL: process.env.MIX_BASE_API,
  timeout: 10000,
});

service.interceptors.request.use(
  config => {
    const token = getToken();

    if (token) {
      config.headers.Authorization = getToken();
    }
    else {
      config.headers.Authorization = Core.getApiToken();
    }

     if (config.method === 'get') {
        if (config.params instanceof Object) {
            config.params.api_token = Core.getApiToken();
        }
        else {
            config.params = {'api_token': Core.getApiToken()};
        }
    }
    else if (['post', 'put', 'patch', 'delete'].indexOf(config.method) !== -1) {
        if(config.data instanceof FormData) {
            config.headers['Content-Type'] = 'multipart/form-data';
            config.data.append('api_token', Core.getApiToken());
            if (['put', 'patch', 'delete'].indexOf(config.method) !== -1) {
                config.data.append('_method', config.method);
                config.method = 'post';
            }
        }
        else if (config.data instanceof Object) {
            config.data.api_token =  Core.getApiToken();
        }
        else {
            config.data = { 'api_token': Core.getApiToken() };
        }
    }

    return config;
  },
  error => {
    // Do something with request error
    console.log('Debug'); // for debug
    console.log(error); // for debug
    Promise.reject(error);
  }
);

service.interceptors.response.use(
  response => {
    if (response.headers.authorization) {
      setToken(response.headers.authorization);
      response.data.token = response.headers.authorization;
    }

    return response.data;
  },
  error => {
    let errMes = ParseError(error);
    Core.notify(errMes.message, {type: errMes.type});

    return Promise.reject(error);
  }
);

export default service;
