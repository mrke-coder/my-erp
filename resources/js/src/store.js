import Vue from "vue";
import Vuex from "vuex";
import * as auth  from "./services/authService"
Vue.use(Vuex);
export default new Vuex.Store({
    state: {
        isLoggedIn: null,
        profile:{
            user:{
                firstName:'admin',
                lastName: 'admin',
                avatar:'https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.seekpng.com%2Fipng%2Fu2q8r5t4i1y3w7e6_existing-user-default-avatar%2F&psig=AOvVaw05tiBvh57ci6eseCAOAFjA&ust=1602067578825000&source=images&cd=vfe&ved=0CAIQjRxqFwoTCKiWp__kn-wCFQAAAAAdAAAAABAK'
            },
            roles:[{role:''}]
        }
        ,
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
        },
    },
    getters: {}
});
