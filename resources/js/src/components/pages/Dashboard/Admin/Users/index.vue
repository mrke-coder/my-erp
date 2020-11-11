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
              <div class="item-wrapper">
                  <div class="table-responsive">
                      <table class="table table-hover" width="100%">
                          <thead>
                          <tr>
                              <th>Profil</th>
                              <th>Roles</th>
                              <th>Actions</th>
                          </tr>
                          </thead>
                          <tbody>
                          <tr v-show="load">
                              <td colspan="3">
                                  <div class="text-center text-info">
                                      <div class="spinner-border" role="status">
                                          <span class="sr-only">Loading...</span>
                                      </div>
                                  </div>
                              </td>
                          </tr>
                          <tr v-for="user in users.data" :key="user.id" v-show="user.id !== $store.state.profile.user.id">
                              <td class="d-flex align-items-center border-top-0">
                                  <img class="profile-img img-sm img-rounded mr-2" :src="user.avatar" alt="profile image">
                                  <span>{{user.firstName}} {{user.lastName}}</span>
                              </td>
                              <td>
                                  <label class="badge badge-danger"
                                         :class="'ui '+createUserRoleClass(role.role)"
                                         v-for="role in roles" :key="role.id"
                                         v-if="role.user_id === user.id">
                                            {{role.role}}
                                      <i class="mdi mdi-close"  style="cursor: pointer"></i>
                                  </label>
                              </td>
                              <td>
                                  <button class="btn btn-success btn-xs" @click="editUser(user.id)">
                                      <i class="fa fa-edit"></i>
                                  </button>
                                  <button class="btn btn-danger btn-xs">
                                      <i class="fa fa-trash"></i>
                                  </button>
                                  <button class="btn btn-info btn-xs">
                                      <i class="fa fa-info-circle"></i>
                                  </button>
                                  <button class="btn btn-warning btn-xs">
                                      <i class="fa fa-refresh"></i>
                                  </button>
                              </td>
                          </tr>
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
      </div>
        <!--- formulaire insertion d'utilisateur-->
      <b-modal id="userModal" size="lg" title="Modal with Popover" hide-footer>
          <form class="container" id="form-user" autocomplete="off">
              <div class="row">
                  <div class="col-md-4 mb-3">
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
                  <div class="col-md-4 mb-3">
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
                  <div class="col-md-4 mb-3">
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
                  <div class="col-md-12 mb-3">
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
          firstName: '',
          lastName: '',
          role:[]
      }
    };
  },
  created() {
    this.load = true;
    userService
      .users()
      .then((response) => {
        setTimeout(() => {
          this.load = false;
          this.users = response.data.users;
          this.roles = response.data.roles;
          //console.log(this.users)
        }, 3000);
      })
      .catch((error) => console.log(error));

       userService
      .roles()
      .then((response) => {
        this.roleData = response.data;
        this.isLoading = false;
      })
      .catch((error) => console.log(error));
  },
  methods: {
    addUser() {
      this.$bvModal.show('userModal');
      this.editing = false;
    },
    hideModal(){
        this.$bvModal.hide('userModal');
        this.editing = false;
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
          this.users.data.unshift(response.data.user[0])
          response.data.roles.forEach(role => {
               this.roles.unshift(role);
          });

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
           this.$toastr("Utilisateur Modifié avec succès !", "MODIFICATION REUSSIE");
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
</style>
