<template>
  <div id="inspire">
      <router-view/>
    </div>
</template>

<script>
    import * as auth from "./services/authService"
export default {
  name: 'App',
    beforeCreate(){
        if(auth.isLoggedIn()) {
            auth.getProfil()
            .then(response => {
                this.$store.state.user = response.data.user;
                this.$store.state.roles = response.data.roles;
                console.log(this.$store.state.roles)
            })
            .catch(e => console.log(e.response));     
        }
    },
    updated() {
      this.verifySessionDuration();
      document.getElementsByTagName('title')[0].innerHTML = this.$store.state.siteTitle;
      localStorage.setItem('page_title',this.$store.state.siteTitle);
     let loader = this.$loading.show();
        setTimeout(() => {
            loader.hide();
        },5000 );
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
    .sidebar a.router-link-exact-active, .sidebar .navigation-menu a:hover
    {
        background-color: #7fb2d4;
        border-left: 3px solid #223C61;
        color: white !important;
        font-weight: bold;
    }
    .sidebar a.router-link-exact-active i
    {
        color: white !important;
        font-weight: bold;
    }
    .b-time-footer{
        position: relative;
        padding: 0;
    }
</style>
