<template>
  <div id="speciality">
    <div class="row">
        <div class="col-lg-12 equel-grid">
            <div class="grid">
                <p class="grid-header">BASE DE DONNEES DES SPECIALIT&Eacute;S</p>
                <div class="grid-body">
                    <div class="item-wrapper">
                        <button class="btn btn-primary" @click="showModal">
                            <i class="fa fa-plus"></i> Nouvelle spécialité
                        </button>
                        <div class="table-responsive">
                            <table class="table table-striped" width="100%">
                                <thead>
                                <tr>
                                    <td>SPECIALITE</td>
                                    <td>DEPARTEMENT</td>
                                    <td>CREE LE</td>
                                    <td>ACTIONS</td>
                                </tr>
                                <tr v-show="loading">
                                    <td colspan="4">
                                        <div class="d-flex justify-content-center mb-3" variant="primary">
                                            <b-spinner label="Loading..."></b-spinner>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-show="!loading" v-for="speciality in specialities.data" :key="speciality.id" :class="{'is_deteled':speciality.deleted_at}">
                                    <td>{{speciality.name}}</td>
                                    <td v-for="dept in departements" :key="dept.id" v-if="dept.id === speciality.department_id">{{dept.name}}</td>
                                    <td>{{speciality.created_at | moment('D/MM/Y')}}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success" @click.prevent="onEdit(speciality.id)"><i class="fa fa-edit"></i></button>
                                        <button type="button" class="btn btn-sm btn-danger" @click.prevent="onDelete(speciality.id)" :disabled="speciality.deleted_at"><i class="fa fa-trash"></i></button>
                                        <button type="button" class="btn btn-sm btn-warning" @click.prevent="onRestore(speciality.id)" v-show="speciality.deleted_at"><i class="fa fa-refresh"></i></button>
                                    </td>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <pagination :data="specialities" @pagination-change-page="loadSpecilities">
                                <span slot="prev-nav">Précédent</span>
                                <span slot="next-nav">Suivant</span>
                            </pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <b-modal id="speciality_modal" size="md" :title="modalTitle" hide-footer>
        <!-- FORM ADD -->
      <form v-show="!editing" @submit.prevent="onSubmit">
          <div class="from-group">
              <label>Selectionner Le Département Lié A Cette Spécialité</label>
              <select v-model="speciality.department_id" class="form-control" :class="{'is-invalid': errors.department_id}">
                  <option value="">----Choisir Un Département----</option>
                  <option v-for="dept in departements" :key="dept.id" :value="dept.id">{{dept.name}}</option>
              </select>
              <div class="invalid-feedback" role="alert" v-if="errors.department_id">
                  {{errors.department_id[0]}}
              </div>
          </div>
          <div class="from-group">
            <label>Nom De La spécialité</label>
            <input v-model="speciality.name" type="text" class="form-control" :class="{'is-invalid':errors.name}" placeholder="Entrer Le Libellé De La Spécialité"/>
            <div class="invalid-feedback" role="alert" v-if="errors.name">
                {{errors.name[0]}}
            </div>
          </div>
          <div class="from-group">
            <label>Description De La spécialité (Optionnel)</label>
            <textarea v-model="speciality.description" class="form-control" rows="3" placeholder="Description.."></textarea>
          </div><br>
          <div class="form-group">
              <button type="reset" @click="hideModal" class="btn btn-danger"><i class="fa fa-close"></i> Annuler</button>
            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Enregistrer</button>
          </div>
      </form>
        <!-- FORM UPDATE -->
      <form v-show="editing" @submit.prevent="onUpdate">
          <div class="from-group">
              <label>Selectionner Le Département Lié A Cette Spécialité</label>
              <select v-model="speciality.department_id" class="form-control" :class="{'is-invalid': errors.department_id}">
                  <option value="">----Choisir Un Département----</option>
                  <option v-for="dept in departements" :key="dept.id" :value="dept.id">{{dept.name}}</option>
              </select>
              <div class="invalid-feedback" role="alert" v-if="errors.department_id">
                  {{errors.department_id[0]}}
              </div>
          </div>
          <div class="from-group">
            <label>Nom De La spécialité</label>
            <input v-model="speciality.name" type="text" class="form-control" :class="{'is-invalid':errors.name}" placeholder="Entrer Le Libellé De La Spécialité"/>
            <div class="invalid-feedback" role="alert" v-if="errors.name">
                {{errors.name[0]}}
            </div>
          </div>
          <div class="from-group">
            <label>Description De La spécialité (Optionnel)</label>
            <textarea v-model="speciality.description" class="form-control" rows="3" placeholder="Description.."></textarea>
          </div><br>
          <div class="form-group">
              <button type="reset" @click="hideModal" class="btn btn-danger"><i class="fa fa-close"></i> Annuler</button>
            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Modifier</button>
          </div>
      </form>

    </b-modal>
  </div>
</template>

<script>
import * as service from "../../../../services/specilityService";
export default {
  data (){
    return {
      specialities:{},
      departements: {},
      speciality:{
          speciality_id:'',
          department_id:'',
          name:'',
          description:''
      },
      errors: '',
      modalTitle:'',
      editing: false,
      loading: false
    }
  },
  created (){
      service.departements()
          .then(response => this.departements = response.data)
          .catch(error => console.log(error));
  },
  mounted(){
    this.loadSpecilities();
  },
  methods: {

    loadSpecilities (page = 1) {
      this.loading = true;
      service.specialities(page).then(response => {
       setTimeout(() => {
           this.loading = false;
           this.specialities = response.data;
       },3000);
      })
    },

    showModal(){
      this.speciality.description="";
      this.speciality.speciality_id="";
      this.speciality.department_id="";
      this.errors ="";
      this.editing = false;
      this.modalTitle ="Ajouter une Nouvelle Spécialité";
      this.$bvModal.show('speciality_modal');
    },

    hideModal (){
        this.speciality.description="";
        this.speciality.speciality_id="";
        this.speciality.department_id="";
        this.errors = ""
        this.editing = false;
        this.$bvModal.hide('speciality_modal');
    },

    onEdit (id){
        this.editing = true;
        this.modalTitle = "Modifier La Spécialité";
        this.$bvModal.show('speciality_modal');
        service.speciality(id)
            .then(response => {
                this.speciality.speciality_id = response.data.id;
                this.speciality.name = response.data.name;
                this.speciality.description = response.data.description;
                this.speciality.department_id = response.data.department_id
            })
            .catch(error => console.log(error.response))
    },

    onSubmit : async function (){
      try {
        const response = await service.post_speciality(this.speciality);
        this.hideModal();
        this.specialities.data.unshift(response.data);
        this.$toastr.success("Spécialité enregistrée avec succès","INSERTION REUSSIE");
      } catch (e) {
        console.log(e.response);
        switch (e.response.status) {
            case 422:
                this.errors = e.response.data;
                this.$toastr.error('Un ou Plusieurs champs doivent être correctement renseigné(s)',"ERREUR CHAMP");
                break;
            case 500:
                this.$toastr.warning("Une erreur survenue lors de la communication avec le serveur, Veuillez rééssayer.", "ERREUR SERVEUR");
                break;
            default:
                this.$toastr.error("Erreur inconnue", "ERREUR NON REPERTORIEE");
        }
      }
    },

    onUpdate: async function (){
        try {
            const response = await service.post_speciality(this.speciality);
            this.specialities.data.map((el, i) => {
                if (el.id === response.data.id){
                    this.specialities.data[i] = response.data;
                }
            });
            this.hideModal();
            this.$toastr.success("Spécialité Numéro: "+response.data.id+" modifiée avec succès", "MODIFICATION REUSSIE")
        } catch (e) {
            console.log(e);
            switch (e.response.status) {
                case 422:
                    this.errors = e.response.data;
                    this.$toastr.error('Un ou Plusieurs champs doivent être correctement renseigné(s)',"ERREUR CHAMP");
                    break;
                case 500:
                    this.$toastr.warning("Une erreur survenue lors de la communication avec le serveur, Veuillez rééssayer.", "ERREUR SERVEUR");
                    break;
                default:
                    this.$toastr.error("Erreur inconnue", "ERREUR NON REPERTORIEE");
            }
        }
    },

    onDelete: async function (id) {
       if (confirm("Êtes-vous sûr de vouloir supprimer cette spécialité ?")){
           try {
               const response = await service.delete_speciality(id);
               this.$toastr.success(response.data, 'SUPPRESSION');
               this.specialities.data.filter(el => el.id !== id)
           }catch (e) {
               console.log(e.response)
           }
       }
    },

    onRestore: async function (id) {
        if (confirm("Êtes-vous sûr de vouloir restorer cette suppression ?")){
            service.restore_trushedSpecialiy(id)
                .then(response => {
                    this.$toastr.success(`
                    La spécialité : <b style="color: red">${response.data.name}</b> a été restorée avec succès
                    `,"RECCUPERATION DE SUPPRESSION");
                })
                .catch(e => console.log(e.response));
        }
    }
  }
}
</script>

<style>
    tr.is_deteled{
        color: #fff !important;
        background-color: #f4433675 !important;
        border-color: #f4433675 !important;
        font-weight: bold;
    }
    .ui.selection.dropdown{
        width: 100% !important;
        background-color: #f8f9fb;
        border: 1px solid #f1f4f8;
    }
</style>
