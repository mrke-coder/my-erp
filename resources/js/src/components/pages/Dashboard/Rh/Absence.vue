<template>
    <div id="absence">
        <div class="row">
            <div class="col-lg-12 equel-grid">
                <div class="grid">
                    <p class="grid-header">BASE DE DONN&Eacute;ES DES ABSENCES</p>
                    <div class="grid-body">
                        <div class="item-wrapper">
                            <button class="btn btn-primary mb-3" @click.prevent="showModal">
                                <i class="fa fa-plus"></i>&nbsp;Nouvelle absence
                            </button>
                            <div class="table-responsive">
                                <table class="table table-striped" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>EMPLOY&Eacute;</th>
                                            <th>DATE ABSENCE</th>
                                            <th>DUR&Eacute; DE L'ABSENCE</th>
                                            <th>JUSTIFI&Eacute;</th>
                                            <th>MOTIFS</th>
                                            <th>ACTIONS</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-show="loading">
                                            <td colspan="6">
                                                <div class="d-flex justify-content-center mb-3">
                                                    <b-spinner variant="primary" label="Spinning"></b-spinner>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-show="!loading" v-for="absence in absences.data" :key="absence.absence_id">
                                            <td>{{absence.firstName+' '+absence.lastName}}</td>
                                            <td>{{absence.absence_date}}</td>
                                            <td></td>
                                            <td>
                                                <button class="btn action-btn btn-outline-danger btn-rounded" v-show="absence.justified ===0">
                                                   NON
                                                </button>
                                                <button class="btn action-btn btn-outline-success btn-rounded" v-show="absence.justified ===1">
                                                    OUI
                                                </button>
                                            </td>
                                            <td>{{absence.patterns.substr(0,15)}}...</td>
                                            <td>
                                                <button class="btn btn-success btn-sm" :disabled="absence.absence_deleted_at" @click.prevent="onEditing(absence.absence_id)"><i class="fa fa-edit"></i></button>
                                                <button class="btn btn-danger btn-sm" v-show="!absence.absence_deleted_at" @click.prevent="onDelete(absence.absence_id)"><i class="fa fa-trash"></i></button>
                                                <button class="btn btn-warning btn-sm" v-show="absence.absence_deleted_at" ><i class="fa fa-refresh"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <pagination :data="absences">
                                            <span slot="prev-nav">&lt; Précédent</span>
                                            <span slot="next-nav">Suivant &gt;</span>
                                        </pagination>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <b-modal id="absence_modal" size="md" :title="modalTitle" hide-footer>
            <form autocomplete="off">
                <div class="row">
                    <div class="form-group mb-3 col-md-12 col-sm-12">
                        <label>Choisissez un employé</label>
                        <sui-dropdown
                            :options="employees"
                            placeholder="Choisir Un Employé"
                            search
                            selection
                            v-model="absence.employee_id"
                            :class="{'error':errors.employee_id}"
                        />
                    </div>

                    <div class="form-group mb-3 col-md-12 col-sm-12">
                        <label>DATE D'ABSENCE</label>
                        <b-form-datepicker v-model="absence.absence_date" class="mb-2"></b-form-datepicker>
                    </div>

                    <div class="form-group mb-3 col-md-12 col-sm-12">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="form-check-input" v-model="end_today"> L'absence a pris fin aujourd'hui <i class="input-frame"></i>
                            </label>
                        </div>
                    </div>

                    <div class="form-group mb-3 col-md-12 col-sm-12" v-show="!end_today">
                        <label>DATE DE FIN DE L'ABSENCE</label>
                        <b-form-datepicker v-model="absence.fin_absence" class="mb-2"></b-form-datepicker>
                    </div>

                    <div class="form-group col-md-12 col-sm-12">
                        <div class="form-inline">
                            <div class="radio mb-3">
                                <label class="radio-label mr-4">
                                    <input v-model="absence.justified" value="1" type="radio" checked>ABSENCE JUSTIFI&Eacute;E <i class="input-frame"></i>
                                </label>
                            </div>
                            <div class="radio mb-3">
                                <label class="radio-label">
                                    <input  v-model="absence.justified" value="0" type="radio">ABSENCE NON JUSTIFI&Eacute;E <i class="input-frame"></i>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3 col-md-12 col-sm-12">
                        <label>MOTIF DE L'ABSENCE</label>
                        <textarea v-model="absence.patterns" class="form-control" rows="3" placeholder="Motifs de l'absence."></textarea>
                    </div>

                    <div class="form-group mb-3 col-lg-12 col-sm-12">
                        <button class="btn btn-danger" type="reset" @click.prevent="hideModal">Annuler</button>
                        <button class="btn btn-success" type="submit" @click.prevent="onSubmit" v-show="!editing">Sauvegarder maintenant</button>
                        <button class="btn btn-warning" type="submit" @click.prevent="onUpdate" v-show="editing">Modifier maintenant</button>
                    </div>

                </div>
            </form>
        </b-modal>
    </div>
</template>

<script>
    import *as service from '../../../../services/absenceService';
    export default {
        name: "Absence",
        data (){
            return {
                employees: {},
                absences: {},
                modalTitle: '',
                end_today: false,
                errors: '',
                editing: false,
                loading: true,
                absence:{
                    id: '',
                    absence_date: '',
                    fin_absence: '',
                    patterns: '',
                    justified: false,
                    employee_id: ''
                }
            }
        },
        created() {
            service.employees()
                .then(response => this.employees = response.data)
                .catch(e => console.log(e.response));
        },
        mounted() {
            this.getAbsences();
        },
        methods:{
            getAbsences (page = 1) {
                service.absences(page)
                    .then(response => {
                       setTimeout(() =>{
                           this.loading = false;
                           this.absences = response.data;
                       }, 3000)
                    })
                    .catch(e => console.log(e.response));
            },

            showModal (){
                this.modalTitle = "Ajouter une nouvelle absence";
                this.$bvModal.show('absence_modal');
                this.absence = {};
            },

            hideModal () {
                this.$bvModal.hide('absence_modal');
                this.absence = {};
            },

            onSubmit: async function (){
                try {
                    const response = await service.post_absence(this.absence);
                    this.hideModal();
                    this.$toastr.success("Absence Sauvegardée avec succès","SAUGARDE REUSSIE");
                    this.absences.data.unshift(response.data);
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

            onEditing(id) {
                this.editing = true;
                this.$bvModal.show('absence_modal');
                service.absence(id)
                    .then(response => {
                        this.absence = response.data;
                    })
                .catch(e => console.log(e.response));
            },

            onUpdate: async function () {
                try {
                    const response = await service.update_absence(this.absence.id,this.absence);
                    this.hideModal();
                    this.$toastr.success("Absence Modifiée avec succès","MODIFICATION REUSSIE");
                    this.absences.data.map((el, i) => {
                        if (response.data.absence_id === el.absence_id){
                            this.absences.data[i] = response.data;
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
                if (confirm("Voulez-vous vraiment supprimer ?")){
                    service.absence(id)
                        .then(response => {
                            this.getAbsences();
                            this.$toastr.success('Suppression de l\'absence a été effectuée avec succès', "SUPPRESSION REUSSIE");
                        })
                        .catch(e => console.log(e.response));
                }
            }
        }
    }
</script>

<style scoped>

</style>
