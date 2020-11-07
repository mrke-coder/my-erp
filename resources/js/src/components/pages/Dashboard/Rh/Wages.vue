<template>
    <div id="wage">
        <div class="row">
            <div class="col-lg-12 equel-grid">
                <div class="grid">
                    <p class="grid-header">BASE DE DONNEES DES SALAIRES</p>
                    <div class="grid-body">
                        <div class="item-wrapper">
                            <button class="btn btn-primary" @click.prevent="showModal">
                                <i class="fa fa-plus"></i>&nbsp; Nouveau salaire
                            </button>
                            <br><br>
                            <div class="table-responsive">
                                <table class="table table-striped" width="100%" cellpadding="0">
                                    <thead>
                                        <tr>
                                            <th>EMPLOYE</th>
                                            <th>MONTANT</th>
                                            <th>ACTIONS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-show="loading">
                                            <td colspan="3">
                                                <div class="d-flex justify-content-center mb-3">
                                                    <b-spinner variant="primary" label="Text Centered"></b-spinner>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-for="wage in wages.data" :key="wage.wage_id" :class="{'is_deteled':wage.deleted_at}">
                                            <td>{{wage.firstName+' '+wage.lastName}}</td>
                                            <td>{{amountFormat(wage.amount)}}</td>
                                            <td>
                                                <button class="btn btn-success btn-sm" :disabled="wage.deleted_at" @click.prevent="onEdit(wage.wage_id)"><i class="fa fa-edit"></i></button>
                                                <button class="btn btn-danger btn-sm" v-show="!wage.deleted_at" @click.prevent="onDelete(wage.wage_id)"><i class="fa fa-trash"></i></button>
                                                <button class="btn btn-warning btn-sm" v-show="wage.deleted_at" @click.prevent="onRestore(wage.wage_id)"><i class="fa fa-refresh"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <pagination :data="wages" @pagination-page-change="getWages">
                                <span slot="prev-nav">Précédent</span>
                                <span slot="next-nav">Suivant</span>
                            </pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <b-modal id="salaire_modal" size="md" :title="modalTitle" hide-footer>
            <form id="form_salaire" autocomplete="off">
                <div class="row">
                    <div class="form-group mb-3 col-md-12 col-sm-12">
                        <sui-form-field>
                            <sui-form-field>
                                <label>Choisissez un employé</label>
                                <sui-form-field required :class="{'error':errors.employee_id}">
                                    <sui-dropdown
                                        :options="employees"
                                        placeholder="Choisir Un Employé"
                                        search
                                        selection
                                        v-model="wage.employee_id"
                                    />
                                </sui-form-field>
                            </sui-form-field>
                        </sui-form-field>
                    </div>
                    <div class="form-group mb-3 col-md-12 col-sm-12">
                        <label>Montant Du Salaire</label>
                        <input type="number" min="0" class="form-control" v-model="wage.amount" :class="{'is-invalid':errors.amount}">
                        <div class="invalid-feedback" role="alert" v-if="errors.amount">
                            {{errors.amount[0]}}
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="reset" class="btn btn-danger" @click.prevent="hideModal">Annuler</button>
                        <button type="submit" class="btn btn-success" @click.prevent="onSubmit" v-show="!editing"> <i class="fa fa-check-circle"></i>&nbsp;Enregister maintenant</button>
                        <button type="submit" class="btn btn-warning" @click.prevent="onUpdate" v-show="editing"> <i class="fa fa-refresh"></i> &nbsp;Modifier maintenant</button>
                    </div>
                </div>
            </form>
        </b-modal>
    </div>
</template>

<script>
    import *as service from "../../../../services/wageService"
    export default {
        name: "Wages",
        data (){
            return {
                modalTitle:'',
                employees: [],
                wages:{},
                loading: false,
                wage:{
                    id:'',
                    amount:0,
                    employee_id:''
                },
                errors: '',
                editing: false
            }
        },
        created(){
            service.employees()
                .then(response => {
                    this.employees = response.data;
                })
                .catch(e => console.log(e.response));
        },
        mounted(){
          this.getWages();
        },
        methods:{

            amountFormat (montant){
                return new Intl.NumberFormat('de-DE',{style:'currency',currency:'XOF'}).format(montant);
            },

            showModal () {
                this.modalTitle = "Ajouter un nouveau salaire";
                this.$bvModal.show('salaire_modal');
                $("#form_salaire").trigger('reset');
                this.wage = {};
            },

            hideModal () {
                this.modalTitle = "Modification d'un salaire";
                this.$bvModal.hide('salaire_modal');
                $("#form_salaire").trigger('reset');
                this.wage = {};
            },

            getWages (page=1){
                this.loading = true;
                service.wages(page)
                    .then(response => {
                        setTimeout(() => {
                            this.loading = false;
                            this.wages = response.data
                        }, 2000)
                    })
                    .catch(e => console.log(e.response));
            } ,

            onSubmit: async function (){
                try {
                    const response = await service.post_wage(this.wage);
                    this.hideModal();
                    $("#form_salaire").trigger('reset');
                    this.wages.data.unshift(response.data);
                    this.$toastr.success("Salaire ajouté avec succès");
                } catch (e) {
                    if (e.response.status === 422){
                        this.$toastr.error("Le champ employé est obligatoir, vérifier qu'un employé a été selectionné puis continuer","ERREUR CHAMP")
                    } else if (e.response.status === 500){
                        this.$toastr.error("Erreur, le serveur rencontre un problème inattendu, veuillez réssayer","ERREUR SERVEUR");
                    } else {
                        this.$toastr.warning("Il y a quelque chose qui s'est mal passé, rééssayer plus tard","ERREUR INCONNUE");
                    }
                }
            },

            onEdit (id){
                this.editing = true;
                service.wage(id)
                    .then(response => {
                        this.wage = response.data;
                        console.log(response)
                    })
                    .catch(e => console.log(e.response));
                this.$bvModal.show('salaire_modal');
            },

            onUpdate: async function (){
                try {
                    const response = await service.update_wage(this.wage.id, this.wage);
                    this.wages.data.map((el,i) => {
                        if (el.wage_id === response.data.wage_id){
                            this.wages.data[i] = response.data
                        }
                    });
                    this.$toastr.success("Le Salaire de :"+response.data.firstName+' '+response.data.lastName+" a été modifié avec succès","MODIFICATION REUSSIE");
                    this.hideModal();
                } catch (e) {
                    if (e.response.status === 422){
                        this.$toastr.error("Le champ employé est obligatoir, vérifier qu'un employé a été selectionné puis continuer","ERREUR CHAMP")
                    } else if (e.response.status === 500){
                        this.$toastr.error("Erreur, le serveur rencontre un problème inattendu, veuillez réssayer","ERREUR SERVEUR");
                    } else {
                        this.$toastr.warning("Il y a quelque chose qui s'est mal passé, rééssayer plus tard","ERREUR INCONNUE");
                    }
                }
            },

            onDelete (id) {
                if (confirm("Voulez-vous vraiment supprimer ?")){
                    service.delete_wage(id)
                        .then(response => {
                            this.$toastr.success(response.data,"SUPPRESSION REUSSIE");
                            this.getWages();
                        })
                        .catch(e => console.log(e.response))
                }
            },

            onRestore (id) {
                if (confirm("Voulez-vous vraiment restorer cette suppression ?")){
                    service.restore_wage(id)
                        .then(response => {
                            this.$toastr.success(response.data,"RECCUPERATION REUSSIE");
                            this.getWages();
                        })
                        .catch(e => console.log(e.response))
                }
            }

        }
    }
</script>

<style scoped>

</style>
