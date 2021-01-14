<template>
  <div class="departement">
  <div class="row">
      <div class="col-lg-12 equel-grid">
          <div class="grid">
              <p class="grid-header">BASE DE DONNEES DES DEPARTEMENTS / SERVICES</p>
              <div class="grid-body">
                  <div class="item-wrapper">
                      <button @click.prevent="showModal" class="btn btn-primary">
                          <i class="fa fa-plus"></i>&nbsp; Nouveau Département
                      </button>
                      <br><br>
                      <div class="table-responsive">
                          <table class="table table-striped" width="100%" cellpadding="0">
                              <thead>
                              <tr>
                                  <td>NOM DEPARTEMENT</td>
                                  <td>NOMBRE EMPLOYES</td>
                                  <td>CREE LE</td>
                                  <td>ACTIONS</td>
                              </tr>
                              </thead>
                              <tbody>
                              <tr v-show="loading">
                                  <td colspan="4">
                                      <div class="d-flex justify-content-center mb-3">
                                          <b-spinner variant="primary" label="Loading..."></b-spinner>
                                      </div>
                                  </td>
                              </tr>
                              <tr v-for="service in services.data" :key="service.id" v-show="!loading" :class="{'is_deteled': service.deleted_at}">
                                  <td>{{service.name}}</td>
                                  <td style="text-align: center"> {{getEmployeesByDepartment(service.id)}}</td>
                                  <td>{{service.created_at | moment('DD/MM/Y')}}</td>
                                  <td>
                                      <button @click.prevent="editDepartement(service.id)" class="btn btn-xs btn-success"><i class="fa fa-edit"></i></button>
                                      <button @click.prevent="onDelete(service.id)" class="btn btn-xs btn-danger" :disabled="service.deleted_at"><i class="fa fa-trash"></i></button>
                                      <button @click.prevent="onRestoreDelete(service.id)" class="btn btn-xs btn-primary" v-show="service.deleted_at"><i class="fa fa-refresh"></i></button>
                                  </td>
                              </tr>
                              </tbody>
                          </table>
                      </div>
                      <pagination :data="services" @pagination-change-page="getResults">
                          <span slot="prev-nav">Précédent</span>
                          <span slot="next-nav">Suivant</span>
                      </pagination>
                  </div>
              </div>
          </div>
      </div>
  </div>
    <b-modal id="departement_modal" size="md" :title="modalTitle" hide-footer>

        <!-- FORM ADD -->
      <form @submit.prevent="onSubmint" v-show="!editing">
          <div class="form-group">
              <label>Nom Du Département</label>
              <input type="text" v-model="service.name" class="form-control" :class="{'is-invalid':errors.name}" placeholder="Entrer Nom Département / Service"/>
              <div class="invalid-feedback" v-if="errors.name">
                {{errors.name[0]}}
              </div>
          </div>
          <div class="form-group">
              <button type="reset" @click="hideModal" class="btn btn-danger"><i class="fa fa-close"></i> Annuler</button>
              <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Enregistrer</button>
          </div>
      </form>
        <!-- FORM UPDATE -->
      <form @submit.prevent="onUpdate" v-show="editing">
          <input type="hidden" v-model="service.id" class="form-control"/>
          <div class="form-group">
              <label>Nom Du Département</label>
              <input type="text" v-model="service.name" class="form-control" :class="{'is-invalid':errors.name}" placeholder="Entrer Nom Département / Service"/>
              <div class="invalid-feedback" v-if="errors.name">
                {{errors.name[0]}}
              </div>
          </div>
          <div class="form-group">
              <button type="reset" @click="hideModal" class="btn btn-danger"><i class="fa fa-close"></i> Annuler</button>
              <button type="submit" class="btn btn-warning"><i class="fa fa-check-circle"></i> Modifier</button>
          </div>
      </form>

    </b-modal>
  </div>
</template>

<script>
import * as depService from "../../../../services/departementService"
export default {

  data() {
    return {
      service:{
        id: '',
        name: '',
      },
      services: {},
      errors:'',
      loading: false,
      editing: false,
      modalTitle: ''
    }
  },

  watch: {
     services: function(newServices, services) {
         //console.log(services);
        this.services = newServices;
     }
  },

  created(){
  },
  mounted(){
    this.getResults();
    //console.log(this.services)
    this.$store.state.pageName ="Départements";
  },
  methods:{

    showModal (){
      this.modalTitle ="Ajouter un nouvel Département"
      this.$bvModal.show('departement_modal');
      this.service.name = ''
    },

    hideModal (){
      $('form').get(0).reset();
      this.errors = ''
      this.$bvModal.hide('departement_modal');
      this.editing = false;
    },

    editDepartement: async function(id){
      this.modalTitle="Modification du Département :"
      this.editing = true;
      this.$bvModal.show('departement_modal');

    depService.show_departement(id).then(response => {
        this.service = response.data
        //console.log(this.service)
    }).catch(error => console.log(error.response));
    },

    onSubmint: async function (){
      try {
        const response = await depService.post_departement(this.service);
        //console.log('succes',response)
          this.$toastr.success("Engistrement effecuté avec succès",'INSERTION');
          $("form").get(0).reset();
          this.services.data.unshift(response.data);
          this.hideModal()
      } catch (e) {
        //console.log('error', e)
        switch (e.response.status) {
          case 422:
            this.errors = e.response.data;
            this.$toastr.error(e.response.data.name[0], 'Erreur Champs')
            break;
          case 500:
            this.$toastr.info("Erreur produite lors de la connexion au serveur", 'Erreur serveur')
            break;
          default:
            this.$toastr.warning('Erreur non repertoriée', 'Erreur inconnue')
        }
      }
    },

    onUpdate: async function(){
        try {
          const response = await depService.update_departement(this.service.id,this.service);
            this.services.data.map((service, i) => {
                if (service.id === response.data.id) {
                    this.services.data[i] = response.data
                }
            });
            this.$toastr.success('Mise à jour effecutée avec succès', 'MODIFICATION REUSSIE');
            this.hideModal();
        } catch (e) {
          //console.log(e.response);
          switch (e.response.status) {
            case 422:
              this.errors = e.response.data;
              this.$toastr.error(e.response.data.nom_departement[0], 'Erreur Champs')
              break;
            case 500:
                this.$toastr.error('Une erreur s\'est produite lors de la connexion au serveur','Erreur serveur')
              break;
            default:
                this.$toastr.error('la source de cette erreur n\'est pas definie, rééssayer plus tard', 'Erreur inconnue')
          }
        }
    },

    onDelete: async function(id){
      if (confirm("Êtes-vous sûr de vouloir supprimer ce departement ?")) {
        depService.delete_departement(id)
        .then(response => {
          this.$toastr.success('Suppression du Département: '+response.data.name+' effecuté avec succès', 'SUPPRESSION');
          this.getResults();
        }).catch(error => console.log(error.response));
      }
    },

    onRestoreDelete: async function(id){
      if (confirm("Vous allez restorer ce Département qui avait été supprimé auparavant")) {
        depService.restore_deleted(id)
        .then(response => {
            //console.log(this.services.data);
            this.$toastr.success("Restoration de "+response.data.name+" effectuée avec succès.", "RESTORATION");
            this.getResults();
        })
        .catch(error => this.$toastr.error('Erreur lors de la communication avec le serveur, rééssayer','ERREUR SERVEUR'));
      }
    },

    getResults(page=1) {
      this.loading =true;
      depService.departements(page).then(response => {
        setTimeout(()=>{
          this.services = response.data;
          //console.log(this.services);
          this.loading = false
        },3000)
      }).catch(err => this.loading = false)
    },
    
    getEmployeesByDepartment (idDep) {
      depService.employees_by_department(idDep)
      .then(response =>{
       response.data.nombre
      })
    }
  },

}
</script>

<style>
  tr.is_deteled{
    color: #fff !important;
    background-color: #f4433675 !important;
    border-color: #f4433675 !important;
    font-weight: bold;
  }
</style>
