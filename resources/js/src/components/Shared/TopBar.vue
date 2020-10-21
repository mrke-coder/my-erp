<template>
    <div class="header navbar">
        <!--<div class="header-container">
            <ul class="nav-left">
                <li>
                    <a id='sidebar-toggle' class="sidebar-toggle" href="javascript:void(0);">
                        <i class="ti-menu"></i>
                    </a>
                </li>
                <li class="search-box">
                    <a class="search-toggle no-pdd-right" href="javascript:void(0);">
                        <i class="search-icon ti-search pdd-right-10"></i>
                        <i class="search-icon-close ti-close pdd-right-10"></i>
                    </a>
                </li>
                <li class="search-input">
                    <input class="form-control" type="text" placeholder="Search...">
                </li>
            </ul>
            <ul class="nav-right">
                <li class="notifications dropdown">
                    <span class="counter bgc-red">3</span>
                    <a href="" class="dropdown-toggle no-after" data-toggle="dropdown">
                        <i class="ti-bell"></i>
                    </a>

                    <ul class="dropdown-menu">
                        <li class="pX-20 pY-15 bdB">
                            <i class="ti-bell pR-10"></i>
                            <span class="fsz-sm fw-600 c-grey-900">Notifications</span>
                        </li>
                        <li>
                            <ul class="ovY-a pos-r scrollable lis-n p-0 m-0 fsz-sm">
                                <li>
                                    <a href="" class='peers fxw-nw td-n p-20 bdB c-grey-800 cH-blue bgcH-grey-100'>
                                        <div class="peer mR-15">
                                            <img class="w-3r bdrs-50p" src="https://randomuser.me/api/portraits/men/1.jpg" alt="">
                                        </div>
                                        <div class="peer peer-greed">
                            <span>
                              <span class="fw-500">John Doe</span>
                              <span class="c-grey-600">liked your <span class="text-dark">post</span>
                              </span>
                            </span>
                                            <p class="m-0">
                                                <small class="fsz-xs">5 mins ago</small>
                                            </p>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="pX-20 pY-15 ta-c bdT">
                            <span>
                              <router-link to="#" class="c-grey-600 cH-blue fsz-sm td-n">Afficher toutes les notifications<i class="ti-angle-right fsz-xs mL-10"></i></router-link>
                            </span>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <router-link to="#" class="dropdown-toggle no-after peers fxw-nw ai-c lh-1" data-toggle="dropdown">
                        <div class="peer mR-10">
                           <img class="w-2r bdrs-50p" :src="$store.state.profile.user.avatar" alt="">
                        </div>
                        <div class="peer">
                          <span class="fsz-sm c-grey-900">{{$store.state.profile.user.firstName +' '+$store.state.profile.user.lastName}}</span>
                        </div>
                    </router-link>
                    <ul class="dropdown-menu fsz-sm">
                        <li>
                            <router-link to="#" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700">
                                <i class="ti-settings mR-10"></i>
                                <span>Paramètres</span>
                            </router-link>
                        </li>
                        <li>
                            <router-link to="#" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700">
                                <i class="ti-user mR-10"></i>
                                <span>Profil</span>
                            </router-link>
                        </li>
                        <li>
                            <router-link to="/" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700">
                                <i class="ti-email mR-10"></i>
                                <span>Messages</span>
                            </router-link>
                        </li>
                        <li role="separator" class="divider"></li>
                        <li @click="logout">
                            <router-link to="javascript:void()" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700" exact>
                                <i class="ti-power-off mR-10"></i>
                                <span>Déconnexion</span>
                            </router-link>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>-->
        <nav class="t-header">
            <div class="t-header-brand-wrapper">
                <a href="index.html">
                    <img class="logo" src="../../assets/logo.png" alt="">
                    <img class="logo-mini" src="../../assets/logo.png" alt="">
                </a>
            </div>
            <div class="t-header-content-wrapper">
                <div class="t-header-content">
                    <button class="t-header-toggler t-header-mobile-toggler d-block d-lg-none">
                        <i class="mdi mdi-menu"></i>
                    </button>
                    <form action="#" class="t-header-search-box">
                        <div class="input-group">
                            <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="Search" autocomplete="off">
                            <button class="btn btn-primary" type="submit"><i class="mdi mdi-arrow-right-thick"></i></button>
                        </div>
                    </form>
                    <ul class="nav ml-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#" id="notificationDropdown" data-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-bell-outline mdi-1x"></i>
                            </a>
                            <div class="dropdown-menu navbar-dropdown dropdown-menu-right" aria-labelledby="notificationDropdown">
                                <div class="dropdown-header">
                                    <h6 class="dropdown-title">Notifications</h6>
                                    <p class="dropdown-title-text">You have 4 unread notification</p>
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
                                    <div class="dropdown-list">
                                        <div class="icon-wrapper rounded-circle bg-inverse-success text-success">
                                            <i class="mdi mdi-cloud-upload"></i>
                                        </div>
                                        <div class="content-wrapper">
                                            <small class="name">Upload Completed</small>
                                            <small class="content-text">3 Files uploded successfully</small>
                                        </div>
                                    </div>
                                    <div class="dropdown-list">
                                        <div class="icon-wrapper rounded-circle bg-inverse-warning text-warning">
                                            <i class="mdi mdi-security"></i>
                                        </div>
                                        <div class="content-wrapper">
                                            <small class="name">Authentication Required</small>
                                            <small class="content-text">Please verify your password to continue using cloud services</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-footer">
                                    <a href="#">View All</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#" id="messageDropdown" data-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-message-outline mdi-1x"></i>
                                <span class="notification-indicator notification-indicator-primary notification-indicator-ripple"></span>
                            </a>
                            <div class="dropdown-menu navbar-dropdown dropdown-menu-right" aria-labelledby="messageDropdown">
                                <div class="dropdown-header">
                                    <h6 class="dropdown-title">Messages</h6>
                                    <p class="dropdown-title-text">You have 4 unread messages</p>
                                </div>
                                <div class="dropdown-body">
                                    <div class="dropdown-list">
                                        <div class="image-wrapper">
                                            <img class="profile-img" src="../../assets/user.png" alt="profile image">
                                            <div class="status-indicator rounded-indicator bg-success"></div>
                                        </div>
                                        <div class="content-wrapper">
                                            <small class="name">Clifford Gordon</small>
                                            <small class="content-text">Lorem ipsum dolor sit amet.</small>
                                        </div>
                                    </div>
                                    <div class="dropdown-list">
                                        <div class="image-wrapper">
                                            <img class="profile-img" src="../../assets/user.png" alt="profile image">
                                            <div class="status-indicator rounded-indicator bg-success"></div>
                                        </div>
                                        <div class="content-wrapper">
                                            <small class="name">Rachel Doyle</small>
                                            <small class="content-text">Lorem ipsum dolor sit amet.</small>
                                        </div>
                                    </div>
                                    <div class="dropdown-list">
                                        <div class="image-wrapper">
                                            <img class="profile-img" src="../../assets/user.png" alt="profile image">
                                            <div class="status-indicator rounded-indicator bg-warning"></div>
                                        </div>
                                        <div class="content-wrapper">
                                            <small class="name">Lewis Guzman</small>
                                            <small class="content-text">Lorem ipsum dolor sit amet.</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-footer">
                                    <a href="#">View All</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#" id="appsDropdown" data-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-apps mdi-1x"></i>
                            </a>
                            <div class="dropdown-menu navbar-dropdown dropdown-menu-right" aria-labelledby="appsDropdown">
                                <div class="dropdown-header">
                                    <h6 class="dropdown-title">Apps</h6>
                                    <p class="dropdown-title-text mt-2">Authentication required for 3 apps</p>
                                </div>
                                <div class="dropdown-body border-top pt-0">
                                    <a class="dropdown-grid">
                                        <i class="grid-icon mdi mdi-jira mdi-2x"></i>
                                        <span class="grid-tittle">Jira</span>
                                    </a>
                                    <a class="dropdown-grid">
                                        <i class="grid-icon mdi mdi-trello mdi-2x"></i>
                                        <span class="grid-tittle">Trello</span>
                                    </a>
                                    <a class="dropdown-grid">
                                        <i class="grid-icon mdi mdi-artstation mdi-2x"></i>
                                        <span class="grid-tittle">Artstation</span>
                                    </a>
                                    <a class="dropdown-grid">
                                        <i class="grid-icon mdi mdi-bitbucket mdi-2x"></i>
                                        <span class="grid-tittle">Bitbucket</span>
                                    </a>
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
    </div>
</template>
<script>

export default {


};
</script>
<style>
</style>
