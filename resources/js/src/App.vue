<template>
  <div id="inspire">
      <router-view/>
    </div>
</template>

<script>
    import * as auth from "./services/authService"
export default {
  name: 'App',
    beforeCreate() {
      auth.getProfil().then(response => {
          this.$store.state.profile = response.data;
         // console.log(this.$store.state.profile)
      }).catch(e => console.log(e.response))
    }
}
</script>

<style>
    a.router-link-exact-active.router-link-active,  .navigation-menu a:hover
    {
        background-color: #7fb2d4;
        border-left: 3px solid #223C61;
        color: white !important;
        font-weight: bold;
    }
    a.router-link-exact-active.router-link-active i
    {
        color: white !important;
        font-weight: bold;
    }
    .b-time-footer{
        position: relative;
        padding: 0;
    }
</style>
