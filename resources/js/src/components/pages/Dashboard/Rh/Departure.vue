<template>
    <div id="departure">
        <div class="row">
            <div class="col-lg-12 equel-grid">
                <div class="grid">
                    <p class="grid-header">BASE DE DONN&Eacute;ES DES EMPLOY&Eacute;S D&Eacute;MISSIONN&Eacute;S</p>
                    <div class="grid-body">
                       <div class="item-wrapper">
                           <button class="btn btn-primary" @click.prevent="showModal">
                               <i class="fa fa-plus"></i>&nbsp;Nouvelle démission
                           </button>
                           <br><br>
                          <div class="table-responsive">
                              <table class="table table-striped" width="100%">
                                  <thead>
                                    <tr>
                                        <th>EMPLOY&Eacute;</th>
                                        <th>DATE D&Eacute;MISSION</th>
                                        <th>MOTIFS DE LA D&Eacute;MISSION</th>
                                        <th>ACTIONS</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr v-show="loading">
                                        <td colspan="4">
                                            Chargement en cours...
                                        </td>
                                    </tr>
                                    <tr v-for="departure in departures.data" :key="departure.id" :class="{'is_deteled':departure.deleted_at}">
                                        <td>{{departure.prenom+' '+departure.prenom}}</td>
                                        <td>{{departure.departure_date | moment("D MMM YYYY")}}</td>
                                        <td>{{departure.patterns.substr(0, 15)}}...</td>
                                        <td>
                                            <button class="btn btn-success" @click.prevent="onEditing(departure.id)" :disabled="departure.deleted_at"><i class="fa fa-edit"></i></button>
                                            <button class="btn btn-danger" v-show="!departure.deleted_at" @click.prevent="onDelete(departure.id)"><i class="fa fa-trash"></i></button>
                                            <button class="btn btn-warning" v-show="departure.deleted_at" @click.prevent="onRestore(departure.id)"><i class="fa fa-refresh"></i></button>
                                        </td>
                                    </tr>
                                  </tbody>
                              </table>
                          </div>
                       </div>
                    </div>
                </div>
            </div>
        </div>
        <b-modal id="departure_modal" hide-footer size="md" :title="modalTitle">
            <div class="row">
                <div class="form-group col-lg-12 col-sm-12">
                    <label>EMPLOY&Eacute;</label>
                    <sui-dropdown
                        :options="employees"
                        placeholder="Choisir Un Employé"
                        search
                        selection
                        v-model="departure.employee_id"
                        :class="{'error':errors.employee_id}"
                    />
                    <div class="invalid-feedback" role="alert" v-if="errors.employee_id">
                        Le champ employé est obligatoire.
                    </div>
                </div>

                <div class="form-group col-lg-12 col-sm-12">
                    <label>DATE DE D&Eacute;MISSION</label>
                    <b-form-datepicker v-model="departure.departure_date" class="mb-2" :class="{'is-invalid':errors.departure_date}"></b-form-datepicker>
                    <div class="invalid-feedback" role="alert" v-if="errors.departure_date">
                        Le champ date de départ en mission est obligatoire.
                    </div>
                </div>

                <div class="form-group col-lg-12 col-sm-12">
                    <label>MOTIFS DE LA D&Eacute;MISSION</label>
                    <textarea class="form-control" v-model="departure.patterns" rows="5"
                              placeholder="Préciser les motifs de la démission"
                              :class="{'is-invalid':errors.patterns}"></textarea>
                    <div class="invalid-feedback" role="alert" v-if="errors.patterns">
                        {{errors.patterns[0]}}
                    </div>
                </div>

                <div class="form-group col-lg-12 col-sm-12">
                    <button class="btn btn-danger" type="reset" @click.prevent="hideModal">Annuler</button>
                    <button class="btn btn-success" type="submit" v-show="!editing" @click.prevent="onSubmit">Sauvegarder maintenant</button>
                    <button class="btn btn-warning" type="submit" v-show="editing" @click.prevent="onUpdate">Modifier maintenant</button>
                </div>

            </div>

        </b-modal>
    </div>
</template>

<script>
    import *as service from "../../../../services/departureService";
    export default {
        name: "Departure",
        data (){
            return {
                employees:[],
                departures: {},
                editing: false,
                loading: false,
                errors: '',
                modalTitle: '',
                departure: {
                    id: '',
                    departure_date: '',
                    patterns: '',
                    employee_id: '',
                }
            }
        },

        created() {
            service.employees()
                .then(response => this.employees = response.data);
        },

        mounted() {
            this.getDepartures();
        },

        methods:{

            showModal (){
                this.$bvModal.show('departure_modal');
                this.departure = {};
                this.editing = false;
                this.errors = '';
                this.modalTitle = "Ajouter une nouvelle démission";
            },

            hideModal () {
                this.$bvModal.hide('departure_modal');
                this.departure = {};
                this.errors = '';
                this.editing = false;
            },

            getDepartures (page = 1) {
                this.loading =true;
                service.departures(page)
                    .then(response => {
                        setTimeout(() => {
                            this.loading = false;
                            this.departures = response.data;
                        }, 2000);
                    })
            },

            onSubmit: async function () {
                try {
                    const response = await service.post_departure(this.departure);
                    this.$toastr.success("Une démssion ajouté à la liste avec succès", "SAUVEGARDE REUSSIE");
                    this.departures.data.unshift(response.data);
                    this.hideModal();
                } catch (e) {
                    switch (e.response.status) {
                        case 422:
                            this.errors = e.response.data;
                            this.$toastr.error("Un ou Plusieur champs sont requis, il faut bien les renseigner", "ERREUR CHAMPS");
                            break;
                        case 500:
                            this.$toastr.error("Erreur rencontrée lors de la connexion avec le serveur", "ERREUR SERVEUR");
                            break;
                        default:
                            this.$toastr.warning("Quelque chose s'est mal passé, rééssayer","ERREUR INCONNUE");
                    }
                }
            },

            onEditing (id) {
                this.editing = true;
                service.departure(id)
                    .then(response => this.departure = response.data);
                this.$bvModal.show('departure_modal');
            },

            onUpdate: async function () {
                try {
                    const response = await service.update_departure(this.departure);
                    this.$toastr.success("Modification des information a été effectuée avec succès", "MODIFICATION REUSSIE");
                    this.hideModal();
                    this.departures.data.map( (el, i) => {
                       if(el.id === response.data.id) {
                           this.departures.data[i] = response.data;
                       }
                    });

                }catch (e) {
                    switch (e.response.status) {
                        case 422:
                            this.errors = e.response.data;
                            this.$toastr.error("Un ou Plusieur champs sont requis, il faut bien les renseigner", "ERREUR CHAMPS");
                            break;
                        case 500:
                            this.$toastr.error("Erreur rencontrée lors de la connexion avec le serveur", "ERREUR SERVEUR");
                            break;
                        default:
                            this.$toastr.warning("Quelque chose s'est mal passé, rééssayer","ERREUR INCONNUE");
                    }
                }
            },

            onDelete (id) {
                if (confirm("Voulez-vous vraiment supprimer ?")) {
                    service.delete_departure(id)
                        .then(response => {
                            this.$toastr.success(response.data, "SUPPRESSION REUSSIE");
                            this.getDepartures();
                        })
                        .catch(e => console.log(e.response));
                }
            },

            onRestore (id) {
                if (confirm("Voulez-vous vraiment réccuprérer ?")) {
                    service.restore_departure(id)
                        .then(response => {
                            this.$toastr.success(response.data, "RECCUPERATION REUSSIE");
                            this.getDepartures();
                        })
                        .catch(e => console.log(e.response));
                }
            }
        }

    }
</script>

<style scoped>

</style>
