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
                                        <tr>
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
                            v-model="addHour.h_1"
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
                            v-model="addHour.h_2"
                        ></b-form-timepicker>
                    </div>

                    <div class="form-group col-lg-12 col-sm-12 mb-3">
                        <label>MOTIF DE L'HEURE SUPPL&Eacute;MENTAIRE</label>
                        <textarea rows="3" class="form-control" v-model="addHour.patterns" placeholder="Entrer les motifs du travail supplémentaire"></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <button class="btn btn-danger" type="reset" @click.prevent="hideModal">Annuler</button>
                        <button class="btn btn-success" type="submit" @click.prevent="onSubmit" v-show="!editing">Sauvegarder maintenant</button>
                        <button class="btn btn-warning" type="submit" v-show="editing">Modifier maintenant</button>
                    </div>
                </div>
            </form>
        </b-modal>
    </div>
</template>

<script>
    import *as service from "../../../../services/add-hourService";
    export default {
        name: "AdditionalHour",
        data (){
            return {
                employees: {},
                addhours: {},
                editing: false,
                errors: '',
                modalTitle: '',
                addHour: {
                    id: '',
                    start: '',
                    end: '',
                    h_1: '',
                    h_2: '',
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
        },
        methods:{
            showModal (){
                this.addHour = {};
                this.$bvModal.show('hours_modal');
                this.editing = false;
            },

            hideModal (){
                this.editing = false;
                this.addHour ={};
                this.$bvModal.hide('hours_modal')
            },

            getAdditionalHours (page = 1) {
                service.add_hours(page)
                    .then(response => {
                        this.addhours = response.data
                    })
            },

            onSubmit: async function () {
                const start = this.addHour.start+' '+this.addHour.h_1;
                const end = this.addHour.end+' '+this.addHour.h_2;

                if (start.length > 0 && start === end){
                    this.$toastr.error("La date ou l'heure du début doit être strictement différent de la date ou l'heure de fin");
                } else {
                    this.addHour.start = start;
                    this.addHour.end = end;
                    try {
                        const response = await  service.post_add_hour(this.addHour);
                    } catch (e) {
                        console.log(e.response)
                    }
                }
            }

        }
    }
</script>

<style scoped>

</style>
