<template>
  <div id="inspire">
      <router-view/>
    </div>
</template>

<script>
    import * as auth from "./services/authService"
export default {
  name: 'App',
    mounted: async function() {
        console.log(auth.isLoggedIn());
         if(auth.isLoggedIn()){
    try{
         const response = await auth.getProfil();
         this.$store.state.userProfile = response.data.user;
         this.$store.state.userRoles = response.data.roles;
    } catch(e){
        console.log(e.response)
    }
}
    },
    updated() {
      this.verifySessionDuration();
      document.getElementsByTagName('title')[0].innerHTML = this.$store.state.siteTitle;
      console.log(this.$store.state.userProfile);
    },
    created () {
      this.verifySessionDuration();

    },
    methods:{
        verifySessionDuration () {
          if (auth.isLoggedIn()){
              let date_connected = localStorage.getItem('connected_at');
              let now_date = this.$moment(new Date()).format('YYYY-MM-DD hh:mm:ss');
              if (date_connected > now_date){
                  localStorage.setItem('connected_at',this.$moment(new Date()).add(15,'minute').format('YYYY-MM-DD hh:mm:ss'));
              } else{
                  auth.logout();
                  this.$router.push('/')
                  this.$toastr.error("Le temps maximum d'inactivité est atteint, veuillez-vous reconnecter", "DELAI DEPASSE;");
              }
          }
        }
    }
}
</script>

<style>
    .sidebar a.router-link-exact-active.router-link-active, .sidebar .navigation-menu a:hover
    {
        background-color: #7fb2d4;
        border-left: 3px solid #223C61;
        color: white !important;
        font-weight: bold;
    }
    .sidebar a.router-link-exact-active.router-link-active i
    {
        color: white !important;
        font-weight: bold;
    }
    .b-time-footer{
        position: relative;
        padding: 0;
    }
</style>
