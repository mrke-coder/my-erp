<template>
    <div>
        <div class="loader" v-show="loading">
            <b-progress :value="min_progress" :max="max_progress" show-progress animated></b-progress>
        </div>
        <div id="dashboard" v-show="!loading">
            <!-- TOPBAR -->
            <div class="loader"></div>
            <nav class="t-header">
                <div class="t-header-brand-wrapper">
                    <a href="index.html">
                        <img class="logo" src="../../../assets/logo.png" alt="logo" style="width:50px; height: auto">
                        <img class="logo-mini" src="../../../assets/logo.png" alt="logo-mini" style="width:50px; height: auto">
                    </a>
                </div>
                <div class="t-header-content-wrapper">
                    <div class="t-header-content">
                        <button class="t-header-toggler t-header-mobile-toggler d-block d-lg-none">
                            <i class="mdi mdi-menu"></i>
                        </button>
                        <form class="t-header-search-box">
                            <div class="input-group">
                                <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="rechercher" autocomplete="off">
                                <button class="btn btn-primary" type="submit"><i class="mdi mdi-arrow-right-thick"></i></button>
                            </div>
                        </form>
                        <ul class="nav ml-auto">
                            <li class="nav-item dropdown">
                                <router-link class="nav-link" to="#" id="notificationDropdown" data-toggle="dropdown" aria-expanded="false">
                                    <i class="mdi mdi-bell-outline mdi-1x"></i>
                                </router-link>
                                <div class="dropdown-menu navbar-dropdown dropdown-menu-right" aria-labelledby="notificationDropdown">
                                    <div class="dropdown-header">
                                        <h6 class="dropdown-title">Notifications</h6>
                                        <p class="dropdown-title-text">Vous avez 4 notifications non lues</p>
                                    </div>
                                    <div class="dropdown-body">
                                        <div class="dropdown-list">
                                            <div class="icon-wrapper rounded-circle bg-inverse-primary text-primary">
                                                <i class="mdi mdi-alert"></i>
                                            </div>
                                            <div class="content-wrapper">
                                                <small class="name">Storage Full</small>
                                                <small class="content-text">Server storage almost full</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dropdown-footer">
                                        <router-link to="#">Voir tout</router-link>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <router-link class="nav-link" to="#" id="messageDropdown" data-toggle="dropdown" aria-expanded="false">
                                    <i class="mdi mdi-message-outline mdi-1x"></i>
                                    <span class="notification-indicator notification-indicator-primary notification-indicator-ripple"></span>
                                </router-link>
                                <div class="dropdown-menu navbar-dropdown dropdown-menu-right" aria-labelledby="messageDropdown">
                                    <div class="dropdown-header">
                                        <h6 class="dropdown-title">Messages</h6>
                                        <p class="dropdown-title-text">Vous avez 1 message non lu</p>
                                    </div>
                                    <div class="dropdown-body">
                                        <div class="dropdown-list">
                                            <div class="image-wrapper">
                                                <img class="profile-img" src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcTPIwZebm5rD1USnvdsYGaOKH7tGK74t5gBbg&usqp=CAU" alt="profile image">
                                                <div class="status-indicator rounded-indicator bg-success"></div>
                                            </div>
                                            <div class="content-wrapper">
                                                <small class="name">Clifford Gordon</small>
                                                <small class="content-text">Lorem ipsum dolor sit amet.</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dropdown-footer">
                                        <router-link to="#">Voir tout</router-link>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <router-link class="nav-link" to="#" id="appsDropdown" data-toggle="dropdown" aria-expanded="false">
                                    <i class="mdi mdi-apps mdi-1x"></i>
                                </router-link>
                                <div class="dropdown-menu navbar-dropdown dropdown-menu-right" aria-labelledby="appsDropdown">
                                    <div class="dropdown-header">
                                        <h6 class="dropdown-title">Profil</h6>
                                        <p class="dropdown-title-text mt-2">Gestion de votre compte</p>
                                    </div>
                                    <div class="dropdown-body border-top pt-0">
                                        <router-link to="#" class="dropdown-grid">
                                            <i class="grid-icon mdi mdi-account mdi-2x"></i>
                                            <span class="grid-tittle">Compte</span>
                                        </router-link>
                                        <router-link to="#" class="dropdown-grid">
                                            <i class="grid-icon mdi mdi-settings mdi-2x"></i>
                                            <span class="grid-tittle">Paramètres</span>
                                        </router-link>
                                        <router-link to="#" class="dropdown-grid">
                                            <i class="grid-icon mdi mdi-help mdi-2x"></i>
                                            <span class="grid-tittle">Aides</span>
                                        </router-link>
                                        <button @click.prevent="logout" class="dropdown-grid" style="border:none">
                                            <i class="grid-icon mdi mdi-logout mdi-2x"></i>
                                            <span class="grid-tittle">Déconnexion</span>
                                        </button>
                                    </div>
                                    <div class="dropdown-footer">
                                        <a href="#">View All</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- END TOPBAR -->

            <div class="page-body">

                <!-- SIDEBAR -->
                <div class="sidebar">
                    <div class="user-profile">
                        <div class="display-avatar animated-avatar">
                            <img class="profile-img img-lg rounded-circle" :src="$store.state.profile.user.avatar" alt="profile image">
                        </div>
                        <div class="info-wrapper">
                            <p class="user-name">{{$store.state.profile.user.firstName+' '+$store.state.profile.user.lastName}}</p>
                            <h6 class="display-income">
                                <span v-if="$store.state.profile.roles[0].role === 'administrator'">Administrateur</span>
                                <span v-else>{{$store.state.profile.user.email}}</span>
                            </h6>
                        </div>
                    </div>
                    <AdminLlinks v-if="!$route.path.split('/').find(el => el === 'rh')"/>
                    <RhLinks v-if="$route.path.split('/').find(el => el === 'rh')"/>
                </div>
                <!-- END SIBAR -->

                <div class="page-content-wrapper">
                    <div class="page-content-wrapper-inner">
                        <div class="content-viewport">
                            <router-view/>
                        </div>
                    </div>


                    <footer class="footer">
                        <div class="row">
                            <div class="col-sm-6 text-center text-sm-right order-sm-1">
                                <ul class="text-gray">
                                    <li><router-link to="#">Conditions d'utilisation</router-link></li>
                                    <li><router-link to="#">Politique de confidentialité</router-link></li>
                                </ul>
                            </div>
                            <div class="col-sm-6 text-center text-sm-left mt-3 mt-sm-0">
                                <small class="text-muted d-block">Copyright © 2020 <a href="mailto:ernestkouassi02@gmail.com">Ernest K.</a>. Tous les droits sont réservés</small>
                            </div>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import * as auth from "../../../services/authService"
    import AdminLlinks from "../../Shared/AdminLlinks";
    import RhLinks from "../../Shared/RhLinks";
export default {
    name: "index",
    data() {
        return {
            languages: [
                { name: 'English', languageCode: 'en', path: require('../../../assets/flags/en.png') },
                { name: 'French', languageCode: 'fr', path: require('../../../assets/flags/fr.png') },
            ],
            loading: true,
            min_progress: 5,
            max_progress: 100
        }

    },
    computed: {
        selectedLanguageFlag() {
            const vm = this;
            switch(vm.$i18n.locale) {
                case 'en': return require('../../../assets/flags/en.png');
                case 'fr': return require('../../../assets/flags/fr.png');
            }
        },
    },
    created(){
        setTimeout(()=> {
            this.loading = false
        }, 3000);
        setInterval(() => {
            this.onLoading();
        }, 500);
        this.verifySession();
        //console.log(this.$store.state.profile.roles)
    },
    methods: {
        selectLanguage(code) {
            const vm = this;

            vm.$root.setLanguage(code);
        },
        logout : async function () {
            auth.logout();
            this.$toastr.success("Vous avez mis fin à votre session, à bientôt");
            await this.$router.push('/')
        },
        verifySession (){
            if(localStorage.getItem('isRefresh') === null){
                localStorage.setItem('isRefresh',true);
                document.location.reload();
            }
        },
        onLoading (){
            while (this.min_progress < this.max_progress){
                this.min_progress += 5;
            }
        }
    },
    components:{
        AdminLlinks,
        RhLinks
    }
}
</script>

<style scoped>
    .progress{
        position: absolute;
        width: 50%;
        top: calc(50% - 20px);
        left: 25%;
        height: 20px;
    }
    .loader{
        position: fixed;
        width: 100%;
        height: 100vh;
    }
</style>
