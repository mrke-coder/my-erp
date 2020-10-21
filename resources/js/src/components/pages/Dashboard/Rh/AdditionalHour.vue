<template>
    <div id="add-hour">
        <div class="row">
            <div class="col-lg-12 equel-grid">
                <div class="grid">
                    <p class="grid-header">BASE DE DONN&Eacute;ES DES HEURES SUPPL&Eacute;MENTAIRES</p>
                    <div class="grid-body">
                        <div class="item-wrapper">
                            <button class="btn btn-primary mb-3" @click.prevent="showModal"><i class="fa fa-plus">&nbsp;Nouvelle heure supplémentaire</i></button>
                            <div class="table-responsive">
                                <table class="table table-striped" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>EMPLOY&Eacute;</th>
                                            <th>NOMBRE D'HEURE</th>
                                            <th>DATE</th>
                                            <th>MOTIFS</th>
                                            <th>ACTIONS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="add_h in addhours.data" :key="add_h.add_hour_id" :class="{'is_deteled':add_h.add_hour_deleted_at}">
                                            <td>{{add_h.firstName+' '+add_h.lastName}}</td>
                                            <td></td>
                                            <td>{{add_h.start | moment('D MMM YYYY')}}</td>
                                            <td>{{add_h.patterns.substr(0,15)}}...</td>
                                            <td>
                                                <button class="btn btn-success" @click.prevent="onEditing(add_h.add_hour_id)" :disabled="add_h.add_hour_deleted_at"><i class="fa fa-edit"></i></button>
                                                <button class="btn btn-danger" v-show="!add_h.add_hour_deleted_at" @click.prevent="onDelete(add_h.add_hour_id)"><i class="fa fa-trash"></i></button>
                                                <button class="btn btn-warning" v-show="add_h.add_hour_deleted_at" @click.prevent="onRestore(add_h.add_hour_id)"><i class="fa fa-refresh"></i></button>
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

        <b-modal id="hours_modal" size="lg" :title="modalTitle" hide-footer>
            <form autocomplete="off">
                <div class="row">
                    <div class="form-group col-lg-12 col-sm-12 mb-3">
                        <label>Employé concerné</label>
                        <sui-dropdown
                            :options="employees"
                            placeholder="Choisir Un Employé"
                            search
                            selection
                            v-model="addHour.employee_id"
                            :class="{'error':errors.employee_id}"
                        />
                    </div>

                    <div class="form-group col-lg-6 col-sm-12 mb-3">
                        <label>DATE D&Eacute;BUT</label>
                        <b-form-datepicker v-model="addHour.start" class="mb-2"></b-form-datepicker>
                    </div>

                    <div class="form-group col-lg-6 col-sm-12 mb-3">
                        <label>HEURE D&Eacute;BUT</label>
                        <b-form-timepicker
                            now-button
                            locale="fr"
                            v-model="h_1"
                        ></b-form-timepicker>
                    </div>

                    <div class="form-group col-lg-6 col-sm-12 mb-3">
                        <label>DATE FIN</label>
                        <b-form-datepicker v-model="addHour.end" class="mb-2"></b-form-datepicker>
                    </div>

                    <div class="form-group col-lg-6 col-sm-12 mb-3">
                        <label>HEURE FIN</label>
                        <b-form-timepicker
                            now-button
                            locale="fr"
                            v-model="h_2"
                        ></b-form-timepicker>
                    </div>

                    <div class="form-group col-lg-12 col-sm-12 mb-3">
                        <label>MOTIF DE L'HEURE SUPPL&Eacute;MENTAIRE</label>
                        <textarea rows="3" class="form-control" v-model="addHour.patterns" placeholder="Entrer les motifs du travail supplémentaire"></textarea>
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
    import *as service from "../../../../services/add-hourService";
    import moment from "moment";
    export default {
        name: "AdditionalHour",
        data (){
            return {
                employees: {},
                addhours: {},
                editing: false,
                errors: '',
                modalTitle: '',
                h_1: '',
                h_2: '',
                addHour: {
                    id: '',
                    start: '',
                    end: '',
                    patterns: '',
                    employee_id: ''
                }
            }
        },
        created() {
            service.employees()
                .then(response => this.employees = response.data);
        },
        mounted() {
            this.getAdditionalHours();
        },
        methods:{
            showModal (){
                this.addHour = {};
                this.h_1 = '';
                this.h_2 = '';
                this.$bvModal.show('hours_modal');
                this.editing = false;
            },

            hideModal (){
                this.editing = false;
                this.addHour ={};
                this.h_1 = '';
                this.h_2 = '';
                this.$bvModal.hide('hours_modal')
            },

            getAdditionalHours (page = 1) {
                service.add_hours(page)
                    .then(response => {
                        this.addhours = response.data
                    })
            },

            onSubmit: async function () {
                const start = this.addHour.start+' '+this.h_1;
                const end = this.addHour.end+' '+this.h_2;
                this.addHour.start = start;
                this.addHour.end = end;
                try {
                    const response = await  service.post_add_hour(this.addHour);
                    this.hideModal();
                    this.$toastr.success("Heure Supplémentaire enregistrée avec succès", "SAUVEGARDE REUSSIE");
                    this.addhours.data.unshift(response.data)
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
                            this.$toastr.error("Quelque chose s'est mal passé, veuillez rééssayer","ERREUR INCONNUE");
                    }
                }
            },

            onEditing(id){
                this.editing = true;
                this.$bvModal.show('hours_modal');
                service.add_hour(id)
                    .then(response => {
                        this.h_1 = response.data.start.split(" ")[1];
                        this.h_2 = response.data.end.split(" ")[1];
                        this.addHour = response.data
                    });
            },

            onUpdate: async function (){
                try {
                    const start = this.addHour.start.split(" ")[0]+' '+this.h_1;
                    const end = this.addHour.end.split(" ")[0]+' '+this.h_2;
                    this.addHour.start = start;
                    this.addHour.end = end;
                    console.log(this.addHour);
                    const response = await service.update_add_hour(this.addHour);
                    this.hideModal();
                    this.$toastr.success("Modification prise en compte et sauvegardée avec succès","MODIFICATION REUSSIE");
                    this.addhours.data.map((el, i) => {
                        if (el.add_hour_id === response.data.add_hour_id){
                            this.addhours.data[i] = response.data;
                        }
                    });
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
            },

            onDelete (id) {
                if (confirm("Voulez-vous vraiment supprimer ?")){
                    service.delete_add_hour(id)
                        .then(response => this.$toastr.success(response.data, "SUPPRESSION EFFECTIVE"))
                        .catch(e => console.log(e.response));
                }
            },

            onRestore (id) {
                if (confirm("Voulez-vous vraiment réccuperer ?")){
                    service.restore_add_hour(id)
                        .then(response => this.$toastr.success(response.data, "RECCUPERATION REUSSIE"))
                        .catch(e => console.log(e.response));
                }
            }
        }
    }
</script>

<style scoped>

</style>
