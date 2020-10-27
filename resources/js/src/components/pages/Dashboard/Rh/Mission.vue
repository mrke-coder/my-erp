<template>
    <div id="mission">
        <div class="row">
            <div class="col-lg-12 equel-grid">
                <div class="grid">
                    <p class="grid-header">BASE DE DONN&Eacute;S DES MISSIONS</p>
                    <div class="grid-body">
                        <div class="item-wrapper">
                            <button class="btn btn-primary" @click.prevent="showModal">
                                <i class="fa fa-plus"></i>&nbsp;Nouvelle mission
                            </button>
                            <br>
                            <br>
                            <table class="table table-striped" width="100%">
                                <thead>
                                <tr>
                                    <th>EMPLOY&Eacute;</th>
                                    <th>P&Eacute;RIODE DE LA MISSION</th>
                                    <th>DESTINATION</th>
                                    <th>FRAIS D'H&Eacute;BERGEMENT</th>
                                    <th>BUDGET</th>
                                    <th>ACTIONS</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr v-show="loading">
                                        <td colspan="7">
                                            <div class="text-center">
                                                <b-spinner variant="primary" label="Spinning"></b-spinner>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-for="mission in displacements.data" :key="mission.id" :class="{'is_deteled':mission.deleted_at}">
                                        <td>{{mission.prenom+' '+mission.nom}}</td>
                                        <td>Du {{mission.displacement_date | moment('D MMM YYYY')}}&nbsp;au&nbsp;{{mission.return_date | moment('D MMM YYYY')}}</td>
                                        <td>{{mission.destination}}</td>
                                        <td>{{amountFormat(mission.accommodation_costs)}}</td>
                                        <td>{{amountFormat(mission.costs)}}</td>
                                        <td>
                                            <button class="btn btn-success" @click.prevent="onEditing(mission.id)" :disabled="mission.deleted_at"><i class="fa fa-edit"></i></button>
                                            <button class="btn btn-danger" v-show="!mission.deleted_at" @click.prevent="onDelete(mission.id)"><i class="fa fa-trash"></i></button>
                                            <button class="btn btn-warning" v-show="mission.deleted_at" @click.prevent="onRestore(mission.id)"><i class="fa fa-refresh"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <b-modal id="mission_modal" size="lg" :title="modalTittle" hide-footer>
            <div class="row">

                <div class="form-group col-lg-12 col-sm-12">
                    <label>EMPLOY&Eacute;</label>
                    <sui-dropdown
                        :options="employees"
                        placeholder="Choisir Un Employé"
                        search
                        selection
                        v-model="displacement.employee_id"
                        :class="{'error':errors.employee_id}"
                    />
                    <div class="invalid-feedback" role="alert" v-if="errors.employee_id">
                        Le champ employé est obligatoire.
                    </div>
                </div>

                <div class="form-group col-lg-6 col-sm-12">
                    <label>DATE DE D&Eacute;PART EN MISSION</label>
                    <b-form-datepicker v-model="displacement.displacement_date" class="mb-2" :class="{'is-invalid':errors.displacement_date}"></b-form-datepicker>
                    <div class="invalid-feedback" role="alert" v-if="errors.displacement_date">
                        Le champ date de départ en mission est obligatoire.
                    </div>
                </div>

                <div class="form-group col-lg-6 col-sm-12">
                    <label>DATE DE FIN DE LA MISSION</label>
                    <b-form-datepicker v-model="displacement.return_date" class="mb-2" :class="{'is-invalid':errors.return_date}"></b-form-datepicker>
                    <div class="invalid-feedback" role="alert" v-if="errors.return_date">
                        Le champ date de fin de mission est obligatoire.
                    </div>
                </div>

                <div class="form-group col-lg-6 col-sm-12">
                    <label>FRAIS D'H&Eacute;BERGEMENT</label>
                    <input type="number" class="form-control" v-model="displacement.accommodation_costs"
                           placeholder="Entrer les frais d'hébergement" :class="{'is-invalid':errors.accommodation_costs}">
                    <div class="invalid-feedback" v-if="errors.accommodation_costs">
                        Le champ frais d'hébergement est obligatoire
                    </div>
                </div>

                <div class="form-group col-lg-6 col-sm-12">
                    <label>LES AUTRES FRAIS DE LA MISSION</label>
                    <input type="number" class="form-control" v-model="displacement.costs"
                           placeholder="Entrer les autres frais de mission" :class="{'is-invalid':errors.costs}">
                    <div class="invalid-feedback" v-if="errors.costs">
                        Le champ frais de mission est obligatoire.
                    </div>
                </div>

                <div class="form-group col-lg-6 col-sm-12">
                    <label>DESTINATION</label>
                    <input type="text" class="form-control" v-model="displacement.destination" placeholder="Entrer la destination de la mission" :class="{'is-invalid':errors.destination}">
                    <div class="invalid-feedback" v-if="errors.destination">
                        {{errors.destination[0]}}
                    </div>
                </div>

                <div class="form-group col-lg-6 col-sm-12">
                    <label>DESCRIPTION DES OBJECTIFS DE LA MISSION</label>
                    <textarea class="form-control" v-model="displacement.means" rows="1" placeholder="Description des objectif de la mission" :class="{'is-invalid':errors.means}"></textarea>
                    <div class="invalid-feedback" role="alert" v-if="errors.means">
                        Le champ Motif de la permission est obligatoire.
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
    import *as service from "../../../../services/DisplacementService";
    export default {
        name: "Mission",
        data () {
            return {
                employees: [],
                displacements: {},
                errors: '',
                editing: false,
                modalTittle: "Création d'une nouvelle mission pour employé",
                loading: false,
                displacement: {
                    id: '',
                    displacement_date: '',
                    return_date: '',
                    destination: '',
                    means: '',
                    costs: '',
                    accommodation_costs: '',
                    employee_id: ''
                }
            }
        },

        created() {
            service.employees()
                .then(response => this.employees = response.data);
        },

        mounted() {
            this.getDisplacements();
        },

        methods:{

            showModal (){
                this.displacement ={};
                this.editing = false;
                this.modalTitle = "Création d'une nouvelle mission pour employé";
                this.$bvModal.show('mission_modal');
            },

            hideModal () {
                this.displacement ={};
                this.editing = false;
                this.$bvModal.hide('mission_modal');
            },

            amountFormat (montant){
                return new Intl.NumberFormat('de-DE',{style:'currency',currency:'XOF'}).format(montant);
            },

            getDisplacements (page = 1) {
                this.loading = true;
                service.displacements(page)
                    .then(response => {
                        setTimeout(() => {
                            this.loading = false;
                            this.displacements = response.data
                        }, 2000)
                    });
            },

            onSubmit: async function () {
                try {
                   const response = await service.post_displacement(this.displacement);
                   this.hideModal();
                   this.$toastr.success("Mission ajoutée avec succès!", "SAUVEGARDE REUSSIE");
                   this.displacements.data.unshift(response.data);
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
                service.displacement(id)
                    .then(response => this.displacement = response.data)
                    .catch(e => console.log(e.response));
                this.$bvModal.show('mission_modal')
            },

            onUpdate: async function () {
                try {
                    const response = await  service.update_displacement(this.displacement);
                    this.$toastr.success("Modification des information est effective", "MODIFICATION REUSSIE");
                    this.hideModal();
                    this.displacements.data.map( (el, i) => {
                        if (el.id === response.data.id){
                            this.displacements.data[i] = response.data;
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
                if (confirm("Êtes-vous sûr de vouloir supprimer ?")){
                    service.delete_displacement(id)
                        .then(response => this.$toastr.success(response.data))
                        .catch(e => console.log(e.response));
                }
            },

            onRestore (id) {
                if (confirm("Êtes-vous sûr de vouloir restorer ?")){
                    service.restore_displacement(id)
                        .then(response => this.$toastr.success(response.data, "RECCUPERATION REUSSIE"))
                        .catch(e => console.log(e.response));
                }
            }

        }
    }
</script>

<style scoped>

</style>
