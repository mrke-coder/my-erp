import Vue from "vue";
import Vuex from "vuex";
import * as auth  from "./services/authService"
Vue.use(Vuex);
export default new Vuex.Store({
    state: {
        isLoggedIn: null,
        siteTitle: null,
        userProfile:'',
        userRoles: [{role:''}],
        pageName: '',
        loading: true,
        apiUrl: "http://127.0.0.1:8000/api/user/",
        serverUrl: "http://127.0.0.1:8000/user/"
    },
    mutations: {
        authenticate (state, payload){
            state.isLoggedIn = auth.isLoggedIn();
            if(state.isLoggedIn){
                state.profile = payload
            } else {
                state.profile = {};
            }
        },
    },
    actions: {
        authenticate (context, payload){
            context.commit('authenticate', payload);
        }
    },
    getters: {}
});
