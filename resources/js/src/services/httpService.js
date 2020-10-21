import axios from "axios";
import store from "../store";
import * as auth from "./authService";
export const http = () => {
    return axios.create({
        baseURL: store.state.apiUrl,
        headers:{
            Authorization: 'Bearer '+auth.getAccessData()
        }
    })
};

export const httpFile = () => {
     return axios.create({
         baseURL: store.state.apiUrl,
         headers: {
             Authorization: 'Bearer '+auth.getAccessData(),
             'Content-Type': 'multipart/form-data, charset=UTF-8',
             'Access-Control-Origin-Allow':'*'
         }
     })
};
