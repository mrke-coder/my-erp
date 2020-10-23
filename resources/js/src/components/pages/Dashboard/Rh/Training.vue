<template>
    <div id="training">
        <div class="row">
            <div class="col-lg-12 equel-grid">
                <div class="grid">
                    <p class="grid-header">BASE DE DONN&Eacute;ES DE FORMATION DES EMPLOY&Eacute;S</p>
                    <div class="grid-body">
                        <div class="item-wrapper">
                            <button class="btn btn-primary mb-3" @click.prevent="showModal">
                                <i class="fa fa-plus"></i>&nbsp;Nouvelle Formation
                            </button>
                            <table class="table table-striped" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>EMPLOY&Eacute;</th>
                                        <th>TYPE FORMATION</th>
                                        <th>D&Eacute;BUT FORMATION</th>
                                        <th>FIN FORMATION</th>
                                        <th>DUR&Eacute;E FORMATION</th>
                                        <th>ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="training in trainings.data" :key="training.training_id" :class="{'is_deteled':training.training_deleted_at}">
                                        <td>{{training.firstName+' '+training.lastName}}</td>
                                        <td>{{training.type}}</td>
                                        <td>{{training.start_date | moment("D MMM YYYY")}}</td>
                                        <td>{{training.end_date | moment("D MMM YYYY")}}</td>
                                        <td>{{training.duration > 1 ? training.duration+' Jours':training.duration+' Jour'}}</td>
                                        <td>
                                            <button class="btn btn-success btn-sm" @click.prevent="onEditing(training.training_id)" :disabled="training.training_deleted_at"><i class="fa fa-edit"></i></button>
                                            <button class="btn btn-danger btn-sm" v-show="!training.training_deleted_at" @click.prevent="onDelete(training.training_id)"><i class="fa fa-trash"></i></button>
                                            <button class="btn btn-warning btn-sm" v-show="training.training_deleted_at" @click.prevent="onRestore(training.training_id)"><i class="fa fa-refresh"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <b-modal id="training_modal" size="md" :title="modalTitle" hide-footer>
            <form autocomplete="off">
                <div class="row">
                    <div class="form-group col-lg-12 col-sm-12 mb-3">
                        <label>Employé concerné</label>
                        <sui-dropdown
                            :options="employees"
                            placeholder="Choisir Un Employé"
                            search
                            selection
                            v-model="training.employee_id"
                            :class="{'error':errors.employee_id}"
                        />
                    </div>

                    <div class="form-group col-lg-12 col-sm-12 mb-3">
                        <label>TYPE DE FORMATION</label>
                        <input type="text" class="form-control" v-model="training.type" placeholder="Type De Formation" :class="{'is-invalid':errors.type}">
                        <div class="invalid-feedback" role="alert" v-if="errors.type">{{errors.type[0]}}</div>
                    </div>

                    <div class="form-group col-lg-12 col-sm-12 mb-3">
                        <label>DATE D&Eacute;BUT</label>
                        <b-form-datepicker v-model="training.start_date" class="mb-2" :class="{'is-invalid':errors.start_date}"></b-form-datepicker>
                        <div class="invalid-feedback" role="alert" v-if="errors.start_date">{{errors.start_date[0]}}</div>
                    </div>


                    <div class="form-group col-lg-12 col-sm-12 mb-3">
                        <label>DATE FIN</label>
                        <b-form-datepicker v-model="training.end_date" class="mb-2" :class="{'is-invalid':errors.end_date}"></b-form-datepicker>
                        <div class="invalid-feedback" role="alert" v-if="errors.end_date">{{errors.end_date[0]}}</div>
                    </div>


                    <div class="form-group mb-3">
                        <button class="btn btn-danger" type="reset" @click.prevent="hideModal">Annuler</button>
                        <button class="btn btn-success" type="submit" @click.prevent="onSubmit" v-show="!editing">Sauvegarder maintenant</button>
                        <button class="btn btn-warning" type="submit" v-show="editing" @click.prevent="onUpdate">Modifier maintenant</button>
                    </div>
                </div>
            </form>
        </b-modal>
    </div>
</template>

<script>
    import *as service from "../../../../services/trainingService";
    export default {
        name: "Training",
        data() {
            return {
                employees: [],
                trainings: {},
                editing: false,
                errors: '',
                modalTitle: '',
                loading: false,
                training: {
                    id: '',
                    type: '',
                    start_date: '',
                    end_date: '',
                    duration: '',
                    employee_id: ''
                }
            }
        },
        created() {
            service.employees()
                .then(response => this.employees = response.data);
        },
        mounted() {
            this.getTrainings();
        },
        methods: {

            showModal() {
                this.training = {};
                this.$bvModal.show('training_modal');
                this.modalTitle = "Ajout une nouvelle formation";
                this.editing = false;
            },

            hideModal() {
                this.editing = false;
                this.training = {};
                this.$bvModal.hide('training_modal')
            },

            getTrainings(page = 1) {
                service.trainings(page)
                    .then(response => this.trainings = response.data);
            },

            onSubmit: async function () {
                try {
                    const response = await service.post_training(this.training);
                    this.hideModal();
                    this.$toastr.success("Les données de formation ajoutées ont été enrgistrées avec succès", "SAUVEGARDE REUSSIE");
                    this.trainings.data.unshift(response.data);
                    //console.log(response)
                } catch (e) {
                    switch (e.response.status) {
                        case 422:
                            this.errors = e.response.data;
                            this.$toastr.error("Un ou plusieurs champs ne sont pas correctement renseigné !", "ERREUR CHAMP");
                            break;
                        case 400:
                            this.$toastr.error(e.response.data, "ERREUR DATE");
                            break;
                        case 500:
                            this.$toastr.warning("Une erreur survenue lors de la connexion au serveur", "ERREUR SERVEUR");
                            break;
                        default:
                            this.$toastr.error("Quelque chose s'est mal passé, veuillez rééssayer", "ERREUR INCONNUE");
                    }
                }
            },

            onEditing(id) {
                this.editing = true;
                service.training(id)
                    .then(response => this.training = response.data);
                this.$bvModal.show('training_modal');
            },

            onUpdate: async function () {
                try {
                    const response = await service.update_training(this.training);
                    this.hideModal();
                    this.$toastr.success("Modification des données de formation a été prise en compte", "MODIFICATION REUSSIE");
                    this.trainings.data.map((el, i) => {
                        if (el.training_id === response.data.training_id) {
                            this.trainings.data[i] = response.data;
                        }
                    });
                } catch (e) {
                    switch (e.response.status) {
                        case 422:
                            this.errors = e.response.data;
                            this.$toastr.error("Un ou plusieurs champs ne sont pas correctement renseigné !", "ERREUR CHAMP");
                            break;
                        case 400:
                            this.$toastr.error(e.response.data, "ERREUR DATE");
                            break;
                        case 500:
                            this.$toastr.warning("Une erreur survenue lors de la connexion au serveur", "ERREUR SERVEUR");
                            break;
                        default:
                            this.$toastr.error("Quelque chose s'est mal passé, veuillez rééssayer", "ERREUR INCONNUE");
                    }
                }

            },

            onDelete (id) {
                if (confirm("Voulez-vous vraiement supprimer ?")){
                    service.delete_training(id)
                        .then(response => this.$toastr.success(response.data, "SUPPRESSION REUSSIE"))
                        .catch(e => {
                            console.log(e.response);
                            this.$toastr.error(e.response.data, "ERREUR")
                        });
                }
            },

            onRestore (id) {
                if (confirm("Voulez-vous vraiement réccuperer ?")){
                    service.restore_training(id)
                        .then(response => this.$toastr.success(response.data, "DONN&Eacute;E RESTOR&Eacute;"))
                        .catch(e => {
                            console.log(e.response);
                            this.$toastr.error(e.response.data, "ERREUR")
                        });
                }
            }
        }
    }
</script>
