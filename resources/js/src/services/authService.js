import {http, httpFile} from "./httpService";
import store from "../store";
var jwt = require('jsonwebtoken');

export const login = user => {
   return http().post('login',user)
       .then(response => {
       if(response.status === 200){
           setToken(response.data)
       }
       return response.data;
   });
};

const setToken = data => {
    const token = jwt.sign({data: data}, 'authkeyforapimyerp@usdnci.2020');
    localStorage.setItem('loggedInUser',token);
    store.dispatch('authenticate',data.data)
}

export const isLoggedIn = () => {
    const token = localStorage.getItem('loggedInUser');
    return token != null;
}

export const getAccessData = () => {
    const token = localStorage.getItem('loggedInUser')
    if(!token){
        return null;
    }
    const tokenData = jwt.verify(token,'authkeyforapimyerp@usdnci.2020')
    return tokenData.data.access_token;
}

export const getProfil = () => {
    return http().get('profil');
}

export const editAvatar = user => {
    return httpFile().put(`edit-avatar/${user.id}`, user);
}

export const update_password = user => {
    return http().put(`edit-password/${user.id}`, user);
}

export const update_email = user => {
    return http().put(`edit-email/${user.id}`, user);
}

export const update_profile = user => {
    return http().put('/edit-my-infos/'+user.id, user);
}

export const logout = () => {
    //code
    http().get('logout');
    localStorage.removeItem('loggedInUser');
    //localStorage.removeItem('isRefresh');
    localStorage.removeItem('connected_at');
}



