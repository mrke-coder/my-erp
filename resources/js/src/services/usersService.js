import {http, httpFile} from "./httpService";

export const users = () => {
    return http().get('users');
}
export const roles = () => {
    return http().get('roles');
}

export const rolesByUser = () => {
    return http().get('role_by_user');
}

export const register = (user) => {
    return http().post('register',user);
}

export const user = (id) => {
    return http().get('/users/'+id);
}
export const update = (user) => {
    return http().put('update',user);
}
