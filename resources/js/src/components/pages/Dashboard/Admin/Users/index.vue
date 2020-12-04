<template>
  <div>
      <div class="col-lg-12">
          <button
              class="btn btn-primary"
              @click="addUser"
          >
              <i class="fa fa-plus"></i> Nouvel utilisateur
          </button>
          <br><br>
          <div class="grid">
              <p class="grid-header">Base de données des utilisateurs</p>
          </div>
          <div class="row">
              <div class="col-lg-12" v-show="load">
                 <div class="grid">
                     <div class="item-wrapper">
                         <div class="text-center text-info">
                             <div class="spinner-border" role="status">
                                 <span class="sr-only">Loading...</span>
                             </div>
                         </div>
                     </div>
                 </div>
              </div>
              <div class="col-lg-4" v-for="user in users.data" :key="user.id" v-show="user.id !== $store.state.userProfile.id">
                  <div class="card-user mt-4">
                      <div class="card-user-up"></div>
                      <div class="avatar mx-auto">
                          <img :src="user.avatar" class="rounded profile-img" alt="profile image">
                      </div>
                      <div class="card-user-body text-center">
                          <h4 class="card-title">
                              {{user.firstName+' '+user.lastName}}
                          </h4>
                          <p> {{user.email}}</p>
                          <hr>
                          <p class="text-left">
                            <ul class="roles">
                                <li class="role-item"
                                v-for="(role, i) in roles" :key="i">
                                    <label class="badge badge-danger text-left"
                                            :class="'ui '+createUserRoleClass(role.role)"
                                            v-if="role.user_id === user.id">
                                        {{role.role}}
                                        <i class="mdi mdi-close"  style="cursor: pointer; float:right"></i>
                                    </label>
                                </li>
                            </ul>
                          </p>
                          <div>
                            <button class="btn btn-success btn-xs" @click="editUser(user.id)">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-xs">
                                <i class="fa fa-trash"></i>
                            </button>
                            <button class="btn btn-info btn-xs" @click.prevent="showRoleModal(user.id)">
                                <i class="fa fa-plus mr-1"></i>role
                            </button>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
        <!--- formulaire insertion d'utilisateur-->
      <b-modal id="userModal" size="lg" :title="title" hide-footer>
          <form class="container" id="form-user" autocomplete="off" v-show="!showRoleForm">
              <div class="form-group" v-show="editing">
                <div class="checkbox">
                    <label>
                    <input type="checkbox" class="form-check-input" v-model="user.ex_password">
                        Utiliser l'ancien mot de passe
                     <i class="input-frame"></i>
                    </label>
                </div>
              </div>
              <div class="row">
                  <div class="col-md-4 mb-3" :class="{'col-md-12':user.ex_password}">
                      <label for="email">
                          Adresse E-mail
                          <span class="requis">*</span>
                      </label>
                      <input
                          type="email"
                          v-model="user.email"
                          id="email"
                          class="form-control"
                          :class="{'is-invalid':errors.email}"
                          placeholder="Entrer Adresse email"
                      />
                      <div class="invalid__form" v-if="errors.email">{{errors.email[0]}}</div>
                  </div>
                  <div class="col-md-4 mb-3" v-show="!user.ex_password">
                      <label for="password">
                          Mot De Passe
                          <span class="requis">*</span>
                      </label>
                      <input
                          type="password"
                          v-model="user.password"
                          id="password"
                          class="form-control"
                          :class="{'is-invalid':errors.password}"
                          placeholder="Entrer mot de passe"
                      />
                      <div class="invalid__form" v-if="errors.password">{{errors.password[0]}}</div>
                  </div>
                  <div class="col-md-4 mb-3"  v-show="!user.ex_password">
                      <label for="password_confirmation">
                          Confirmer Le Mot De Passe
                          <span class="requis">*</span>
                      </label>
                      <input
                          type="password"
                          v-model="user.password_confirmation"
                          id="password_confirmation"
                          class="form-control"
                          placeholder="Répéter le mot de passe"
                      />
                  </div>
                  <div class="col-md-6 mb-3">
                      <label for="firstName">
                          Prénoms
                          <span class="requis">*</span>
                      </label>
                      <input
                          type="text"
                          v-model="user.firstName"
                          class="form-control"
                          :class="{'is-invalid':errors.firstName}"
                          id="firstNAme"
                          placeholder="Entrer Prénom"
                      />
                      <div class="invalid__form" v-if="errors.firstName">{{errors.firstName[0]}}</div>
                  </div>
                  <div class="col-md-6 mb-3">
                      <label for="lastName">Nom De Famille</label>
                      <input
                          type="text"
                          v-model="user.lastName"
                          class="form-control"
                          id="lastName"
                          placeholder="Entrer Nom De Famille"
                      />
                  </div>
                  <div class="col-md-12 mb-3" v-show="!editing">
                      <label class="typo__label" for="role">Role de l'utilisateur</label>
                      <div class="form-group row">
                          <div class="form-check" v-for="role in roleData" :key="role.id">
                              <label class="form-check-label" v-show="role.role !== 'administrator'">
                                  <input class="form-check-input"
                                         id="role" :class="{'is-invalid':errors.role}"
                                         v-model="user.role" :value="role.id"
                                         type="checkbox"> {{role.role}}&nbsp;
                              </label>
                          </div>
                          <div class="invalid__form" v-if="errors.role">{{errors.role[0]}}</div>
                      </div>
                  </div>
              </div>
              <button class="btn btn-danger" type="reset" @click.prevent="hideModal">
                  <i class="fa fa-close"></i>&nbsp; Annuler
              </button>&nbsp;
              <button class="btn btn-success" @click.prevent="register"  v-show="!editing">
                  <i class="fa fa-check-circle"></i>&nbsp; Sauvegarder
              </button>&nbsp;
              <button class="btn btn-warning" type="submit" @click.prevent="update"  v-show="editing">
                  <i class="fa fa-check-circle"></i>&nbsp; Modifier
              </button>
          </form>
          <form class="container" @submit.prevent="OnaddNewRole" id="form-role" autocomplete="off" v-show="showRoleForm">
              <div class="form-group" v-for="role in roleData" :key="role.id">
                   <div class="checkbox">
                        <label>
                        <input type="checkbox" class="form-check-input" :value="role.id" v-model="userRole.role">
                            {{role.role}}
                        <i class="input-frame"></i>
                        </label>
                    </div>
              </div>
              <div class="form-group">
                    <button class="btn btn-danger" type="reset" @click.prevent="hideModal">
                        <i class="fa fa-close"></i>&nbsp; Annuler
                    </button>&nbsp;
                    <button class="btn btn-success" type="submit">
                        <i class="fa fa-check-circle"></i>&nbsp; Ajouter
                    </button>&nbsp;
              </div>
          </form>
      </b-modal>
  </div>
</template>

<script>
import * as userService from "../../../../../services/usersService";
export default {
  name: "index",
  data() {
    return {
      users: [],
      roles: [],
      load: false,
      editing: false,
      title: 'Ajouter un nouvel utilisateur',
      showRoleForm: false,
      isLoading: false,
      submitted: false,
      roleData: [],
      user_role: false,
      valid: false,
      errors: "",
      user:{
          user_id: '',
          email: '',
          password: '',
          password_confirmation: '',
          ex_password: false,
          firstName: '',
          lastName: '',
          role:[]
      },
      userRole: {
          user_id: '',
          role: []
      }
    };
  },
  created() {
       this.$store.state.siteTitle = `ERP - USDNCI | Utilisateurs`;
  },
  mounted () {
    this.getRoles();
    this.getUserRoles();
  },
  methods: {

    getUserRoles() {
        this.load = true;
    userService
      .users()
      .then((response) => {
        setTimeout(() => {
          this.load = false;
          this.users = response.data.users;
          this.roles = response.data.roles;
        }, 3000);
      })
      .catch((error) => console.log(error));
    },
    getRoles (){
      userService
      .roles()
      .then((response) => {
        this.roleData = response.data;
        this.isLoading = false;
      })
      .catch((error) => console.log(error));
    },
    addUser() {
      this.$bvModal.show('userModal');
      this.editing = false;
      this.user = {};
      this.errors = '';
      this.showRoleForm = false;
    },


    hideModal(){
        this.$bvModal.hide('userModal');
        this.editing = false;
        this.user = {};
        this.errors = '';
        this.showRoleForm = false;
    },

    showRoleModal (id) {
        this.showRoleForm = true;
        this.editing = false;
        this.errors = '';
        this.userRole.user_id = id;
        this.$bvModal.show('userModal');
        this.title = "Ajouter un nouveau rôle"
    },

    createUserRoleClass(role){
        const data = role.split(' ');
        let classData='';
        data.map(el => {
            if (el !== 'administrator'){
                classData += el.substr(0,1);
            } else {
                classData = 'admin'
            }
        });

        return classData;
    },

   editUser: async function(id){
        this.editing = true;
        this.errors = '';
       try {
           const response = await userService.user(id);
               this.user = response.data
               this.$bvModal.show('userModal')
       } catch (e) {
           console.log(e.response)
       }
   },

    register: async function () {
      try {
        const response = await userService.register(this.user);
          this.$toastr.success("Utilisateur enregistré avec succès", "SAUVEGARDE REUSSIE");
          this.hideModal();
          this.users.data.unshift(response.data.user)
          response.data.roles.forEach(role => {
               this.roleData.unshift(role);
          });
          this.user = {};

      } catch (e) {
        if (e.response.status === 422) {
            this.errors = e.response.data;
        } else {
            this.$toastr.error("Erreur inconnue");
        }
        this.submitted = false;
      }
    },


    update: async function (){
       try {
          const response = await userService.update(this.user);
          //console.log(response)
           this.hideModal();
           this.users.data.map((el, i) => {
               if (el.id === response.data.id){
                   this.users.data[i] = response.data;
               }
           });
           this.$toastr.success("Utilisateur Modifié avec succès !", "MODIFICATION REUSSIE");
       }catch (e) {
           switch (e.response.status) {
               case 422:
                   this.errors = e.response.data;
                   this.$toastr.error('Un ou Plusieurs champs ne sont pas correctement renseignés')
                   break;
               case 500: this.$toastr.warning('Une erreur s\'est produit lors de la communication avec le serveur');
                    break;
               default:
                   this.$toastr.info('Quelque s\'est mal passée, rééssayer.')
           }
       }
    },

    OnaddNewRole: async function () {
        try {
           const response = await userService.addRole(this.userRole);
           console.log(response.data)
           response.data.data.map(el => {
               this.roles.unshift(el);
           });
           this.getUserRoles();
           this.hideModal();
           if(response.data.nb_ignored > 0){
               this.$toastr.warning('Attention, parmi les rôles selectionnés, il y a d\'autres qui appartiennent déjà donc nous les avons ignoréss', "UNE EXCEPTION LEVEE");
           } else{
               this.$toastr.success("Ajout de rôle effectué avec succès !", "SAUVEGARDE REUSSIE");
           }
        } catch (e) {
            switch (e.response.status) {
                case 422:
                    this.$toastr.error('Selectionner un rôle puis continuer !', 'erreur champ')
                    break;

                default:
                    this.$toastr.warning('Quelque chose s\'est mal passé !', 'Erreur inconnue')
                    break;
            }
        }
    }

  },
};
</script>
<style scoped>
span.ui {
  background-color: gray;
  border-radius: 30px;
  box-shadow: 1px 1px 5px rgb(0, 0, 0, 0.5);
  color: white;
  padding: 5px;
}
label.badge{
    width: 100% !important;
}
label.badge.ui.admin {
  background-color: #DB504A !important;
  border-color: #DB504A !important;
}

label.badge.badge-danger.ui.hr {
  background-color: #4caf50 !important;
  border-color: #4caf50 !important;
}

label.badge.badge-danger.ui.gs{
    background-color: #857bff !important;
    border-color: #857bff !important;
}
.requis {
  color: red;
}
img.avatar{
  width: 40px;
  height: 40px;
  border-radius: 100%;
}

.card-user{
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    background-color: #FFF;
    background-clip: border-box;
    border-radius: .25rem;
    max-width: 22rem;
    border: 0;
    font-weight: 400;
    box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,0.12);
}
.card-user .card-user-up{
    height: 120px;
    overflow: hidden;
    border-top-left-radius: .25rem;
    border-top-right-radius: .25rem;
    background: linear-gradient(40deg, #2096ff, #05ffa3) !important;
}
.card-user .avatar{
    width: 120px;
    margin-top: -60px;
    overflow: hidden;
    border: 5px solid #FFF;
    border-radius: 50%;
    background-color: #FFF !important;
}
.card-user .avatar img{
    width: 100%;
    height: 100%;
}
.card-user-body{
    border-radius: 0 !important;
    padding:1.5rem 1.25rem;
    min-height: 1px;
    flex: auto;
}
.card-title{
    margin-bottom: .75rem !important;
    font-weight: 400;
}
p{
    margin-top: 0;
    margin-bottom: 1rem;
}
</style>
