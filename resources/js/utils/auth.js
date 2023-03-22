import Cookies from 'js-cookie';

const TokenKey = 'Admin-Token';
const now = new Date();
const midnight = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59, 59, 0);

export function getToken() {
  return Cookies.get(TokenKey);
}

export function setToken(token) {	
  return Cookies.set(TokenKey, token, { expires: midnight }); 
}

export function removeToken() {
  console.log('Token removed');
  return Cookies.remove(TokenKey);
}
