<template>
    <div class="authentication-theme auth-style_1">
        <div class="row">
            <div class="col-12 logo-section">
                <a href="javascript:void(0)" class="logo">
                    <img src="../../../assets/logo.png" class="login-logo" alt="logo" />
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 col-md-7 col-sm-9 col-11 mx-auto">
                <div class="grid">
                    <div class="grid-body">
                        <div class="row">
                            <div class="col-lg-7 col-md-8 col-sm-9 col-12 mx-auto form-wrapper">
                                <form id="login_form" @submit.prevent="login">
                                    <div class="form-group input-rounded">
                                        <input type="email"
                                               class="form-control"
                                               placeholder="Entrer adresse e-mail"
                                               v-model="user.email"
                                        >
                                    </div>
                                    <div class="form-group input-rounded">
                                        <input :type="hidePassword ? 'password' : 'text'" class="form-control"
                                               placeholder="Entrer mot de passe"
                                               v-model="user.password" id="password">
                                            <i class="mdi" :class=" hidePassword ? 'mdi-eye': 'mdi-eye-off'" @click="hidePassword = !hidePassword" id="pwd_state"></i>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block" v-show="!loading">Se connecter</button>
                                    <button class="btn btn-primary btn-block" type="button" disabled v-show="loading">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import * as authService from "../../../services/authService"
import {mapState} from 'vuex';
export default {
    name: "Login",
    created() {
        $(function () {
           $("body").attr('class','');
        });
    },
    mounted (){
        this.$nextTick(function() {
             this.$store.state.siteTitle = "ERP - USDNCI | Authentification"
        });
    },
    data() {
        return {
            user:{
                email: "",
                password: "",
            },
            loading: false,
            hidePassword: true,
            error: false,
            showResult: false,
            color:"",
            result: "",
            rules: {
                required: value => !!value || "Champ obligatoire."
            },
            delay: Math.floor(Math.random()*5000)
        };
    },
    methods: {
        login : async function (){
            const vm = this;
            this.loading = true;
            if (!vm.user.email || !vm.user.password) {
                this.loading = false;
                this.$toastr.error("L'e-mail et le mot de passe ne peuvent pas être nuls.", 'Erreur Champ');
                return;
            }
            try {
              const response = await authService.login(this.user);
              this.$toastr.success("Authentification réussie, redirection en cours...", 'Authenfié !');
               setTimeout(() => {
                    localStorage.setItem('connected_at',this.$moment(new Date()).add(15,'minute').format('YYYY-MM-DD hh:mm:ss'));
                    this.loading = false;
                    document.getElementsByTagName('body')[0].setAttribute('class','header-fixed');

                    switch (response.token_scope) {
                        case 'do_anyThings':
                            window.location.href = `/dashboard/admin/${authService.hashUrlParams(response.user.id)}`;
                            break;
                        case 'Rh':
                            window.location.href = `/dashboard/rh/${authService.hashUrlParams(response.user.id)}/rh`
                            break;
                        default:
                            this.$toastr.error('redirection impossible');
                    }
               },this.delay);
            }catch (e) {
                this.loading=false;
                switch (e.response.status) {
                    case 401:
                        this.$toastr.error("Identifiants de connexion incorrects, rééssayer.", 'Erreur ideentiants')
                        break;
                    case 500:
                        this.$toastr.warning("Erreur serveur, en cas de persistence, fais appel à votre developpeur.", "Erreur Serveur")
                        break;
                    default:
                        this.$toastr.info("Erreur de nature inconnue, veuillez rééssayer plutard.", "Erreur inconnue")
                }
            }
        }
    }
};
</script>

<style>
    i#pwd_state {
        float: right;
        margin-top: -25px;
        margin-right: 10px;
        font-size: 20px;
        color: #696ffb;
        cursor: pointer;
    }

   .login-logo{
       animation: rotateLogo infinite 20s linear;
   }

    @keyframes rotateLogo {
        from{
            transform: rotate(0deg);
        }
        to{
            transform: rotate(360deg);
        }
    }

</style>
