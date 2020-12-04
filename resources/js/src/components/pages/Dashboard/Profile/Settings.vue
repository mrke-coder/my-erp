<template>
  <div id="setting">
      <reset-password />
      <email />
  </div>
</template>

<script>
import Email from './Email.vue';
import ResetPassword from './ResetPassword.vue';
export default {
    components: {
        ResetPassword,
        Email
    },
    mounted (){
        this.$store.state.pageName ="Paramètres";
    }
}
</script>

<style>

</style>
