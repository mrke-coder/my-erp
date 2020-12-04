<template>
  <div id="profile">
    <div class="row">
      <div class="col-lg-12">
        <div class="equel-grid">
            <div class="grid" style="background:#fbfcfd !important">
                <div class="grid-header">
                    <button type="button" class="btn btn-default btn-round" @click.prevent="backToRoute">
                        <i class="fa fa-arrow-left"></i>
                    </button>
                </div>
                <div class="grid-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <div>
                                <img :src="admin.avatar"
                                 class="profile-img rounded-circle accout-img"
                                 ref="displayProfileAvatar"
                                 alt="USER AVATAR" style="width: 150px; height: auto"/>
                                <form id="new-avatar" name="">
                                    <input type="file" name="avatar" id="avatar-input" ref="profileAvatar" @change="chargerImage"/>
                                </form>
                                <div class="form-group" style="margin-left:20px" v-show="sending">
                                     <button class="btn btn-success"  @click.prevent="EditAvatar" >Sauvegarder</button>
                                </div>
                            </div>
                             <h3>{{admin.firstName+' '+admin.lastName}}</h3><br>
                             <span>{{$store.state.userProfile.fonction}}</span>
                             <aside class="menu">
                                 <p class="menu-item">
                                     <router-link  :to="`/dashboard/profile/${crypterRoute(admin.lastName+admin.firstName)}/${admin.id}/profil`" class="menu-link" exact-active-class="profile-active">
                                        <i class="fa fa-user-circle mr-2"></i>Mon Profil
                                     </router-link>
                                 </p>
                                  <p class="menu-item">
                                     <router-link :to="
                                                `/dashboard/profile/${crypterRoute(admin.lastName+admin.firstName)}/${admin.id}/parametres`"  class="menu-link" exact-active-class="profile-active">
                                        <i class="fa fa-cog mr-2"></i>paramètres
                                     </router-link>
                                 </p>
                                  <p class="menu-item">
                                     <router-link :to="
                                                `/dashboard/profile/${crypterRoute(admin.lastName+admin.firstName)}/${admin.id}/aides`
                                            "  class="menu-link" exact-active-class="profile-active">
                                     <i class="fa fa-info-circle mr-2"></i>Aides
                                     </router-link>
                                 </p>
                             </aside>
                        </div>
                        <div class="col-lg-9">
                           <router-view />
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {editAvatar} from '../../../../services/authService';
import Account from './Account.vue';
import Settings from './Settings.vue';
import Helps from './Helps';
import jwt from 'jsonwebtoken';
import {mapState} from 'vuex';
export default {
  components: { Account, Settings, Helps },
  name: "index",
  data (){
      return {
          avatar:'',
          id:'',
          sending: false
      }
  },
  computed: mapState({
      admin: state => state.userProfile
  }) ,
  methods:{
      backToRoute (){
          this.$router.go(-1);
      },
      EditAvatar: async function () {
          try {
              const response = await editAvatar({id: this.$store.state.userProfile.id, avatar: this.avatar});
              console.log(response);
              this.avatar="";
              this.id="";
              this.$toastr.success("Votre photo de profil a été changée", "OPERATION REUSSIE");
          } catch (error) {
              switch(error.response.status){
                case 422:
                      this.$toastr.error(
                          error.response.data.avatar[0],
                          "ERREUR DE FICHIER"
                      );
                      break;
                case 500:
                    this.$toastr.warning(
                        "Erreur, le serveur ne répond pas.",
                        "ERREUR SERVEUR"
                    );
                    break;
                default:
                    this.$toastr.error("Quelque chose s'est mal passé, l'exécution s'est arretée", "ERREUR INCONNUE");

              }
          }
      },

      chargerImage() {
          this.avatar = this.$refs.profileAvatar.files[0];
          let reader = new FileReader();

          reader.addEventListener('load', function() {
              this.$refs.displayProfileAvatar.src = reader.result;
          }.bind(this), false);
          reader.readAsDataURL(this.avatar)

          this.avatar !== "" ? this.sending=true : this.sending=false
      },
      crypterRoute (value){
          return jwt.sign(value, 'myerp.auth.url.crypt');
      }
  }
};
</script>

<style scoped>
    button.btn.btn-warning.btn-sm{
        position: absolute;
        right: 66px;
        top: 100px;
        padding: 25px;
    }
    p.menu-item{
        font-size: 2rem;
        margin-bottom: 1.5rem;
        text-align: left;
    }
    p.menu-item a {
    padding: 5px 30px 5px 0;
    font-size: 16px;
    line-height: 1;
    color: #525c5d;
    letter-spacing: 0.03rem;
    font-weight: 500;
    }
    a.menu-link.profile-active, a.menu-link:hover {
        color: rgba(0,0,0,0.9);
    }
    #new-avatar{
        background: url(../../../../assets/add_circle.png) no-repeat center center / 60px 60px ;
        border-radius: 50%;
        position: absolute;
        margin-left: -55px;
        margin-top: -30px;
    }
    #avatar-input{
        cursor: pointer;
        opacity: 0;
    }
</style>
