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
                                        <th>D&Eacute;BUT FORMATION</th>
                                        <th>FIN FORMATION</th>
                                        <th>DUR&Eacute;E FORMATION</th>
                                        <th>ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>

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
                        <label>MOTIF DE L'HEURE SUPPL&Eacute;MENTAIRE</label>
                        <input type="text" class="form-control" v-model="training.type" placeholder="Type De Formation">
                    </div>

                    <div class="form-group col-lg-12 col-sm-12 mb-3">
                        <label>DATE D&Eacute;BUT</label>
                        <b-form-datepicker v-model="training.start_date" class="mb-2"></b-form-datepicker>
                    </div>


                    <div class="form-group col-lg-12 col-sm-12 mb-3">
                        <label>DATE FIN</label>
                        <b-form-datepicker v-model="training.end_date" class="mb-2"></b-form-datepicker>
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
        data (){
            return {
                employees:[],
                trainings:{},
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
        methods:{

            showModal (){
                this.training = {};
                this.$bvModal.show('training_modal');
                this.modalTitle = "Ajout une nouvelle formation";
                this.editing = false;
            },

            hideModal (){
                this.editing = false;
                this.training ={};
                this.$bvModal.hide('training_modal')
            },

            getTrainings (page = 1) {
                service.trainings(page)
                    .then(response => this.trainings === response.data);
            },

            onSubmit: async function (){
                try {
                    const response = await service.post_training(this.training);
                    console.log(response)
                }catch (e) {
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
                            this.$toastr.error("Quelque chose s'est mal passé, veuillez rééssayer","ERREUR INCONNUE");
                    }
                }
            }

        }
    }
</script>

<style scoped>

</style>
