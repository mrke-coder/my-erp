<template>
    <div id="prime_employee">
        <div class="row">
            <div class="col-lg-12 equel-grid">
                <div class="grid">
                    <p class="grid-header">BASE DE DONN&Eacute;ES DES PRIMES</p>
                    <div class="grid-body">
                        <div class="item-wrapper">
                            <button class="btn btn-primary" @click.prevent="showModal">
                                <i class="fa fa-plus"></i> &nbsp;Nouvelle prime
                            </button>
                            <br><br>
                            <div class="table-responsive">
                                <table class="table table-striped" cellpadding="0" width="100%">
                                    <thead>
                                        <tr>
                                            <td>EMPLOY&Eacute;</td>
                                            <td>MONTANT</td>
                                            <td>MOTIF</td>
                                            <td>ACTIONS</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="bns in bonuses.data" :key="bns.bonus_id">
                                        <td>{{bns.firstName+' '+bns.lastName}}</td>
                                        <td>{{amountFormat(bns.amount)}}</td>
                                        <td>{{bns.patterns}}</td>
                                        <td>
                                            <button class="btn btn-success sm" :disabled="bns.bns_deleted_at" @click="onEdit(bns.bonus_id)">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger sm" :disabled="bns.bns_deleted_at" v-show="!bns.bns_deleted_at" @click="onDelete(bns.bonus_id)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <button class="btn btn-warning sm" v-show="bns.bns_deleted_at" @click="onRestore(bns.bonus_id)">
                                                <i class="fa fa-refresh"></i>
                                            </button>
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
        <b-modal id="bonus_modal" size="md" :title="modalTitle" hide-footer>
            <form id="form_salaire" autocomplete="off">

                <div class="row">
                    <div class="form-group mb-3 col-md-12 col-sm-12">
                        <label>Choisissez un employé</label>
                            <sui-dropdown
                                :options="employees"
                                placeholder="Choisir Un Employé"
                                search
                                selection
                                v-model="bonus.employee_id"
                                :class="{'error':errors.employee_id}"
                            />
                        <div class="invalid-feedback" role="alert" v-if="errors.employee_id">
                            Le champ Employé est obligatoire, selectionnez-en un.
                        </div>
                    </div>

                    <div class="form-group mb-3 col-md-12 col-sm-12">
                        <label>Montant De La Prime</label>
                        <input type="number" min="0" class="form-control" placeholder="Montant de la prime" v-model="bonus.amount" :class="{'is-invalid':errors.amount}">
                        <div class="invalid-feedback" role="alert" v-if="errors.amount">
                            Le champ Montant De La Prime est obligatoire
                        </div>
                    </div>

                    <div class="form-group mb-3 col-md-12 col-sm-12">
                        <label>Motif</label>
                        <textarea :class="{'is-invalid':errors.patterns}" rows="2" v-model="bonus.patterns" class="form-control" placeholder="Quel(s) est/sont le(s) motif(s) de cette prime ?"></textarea>
                        <div class="invalid-feedback" role="alert" v-if="errors.patterns">
                          Le champ Motif est obligatoire, Vous devez les motifs de cette prime.
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
    import * as service from '../../../../services/bonusService';
    export default {
        name: "PrimesEmployees",
        data (){
            return {
                bonuses:{},
                employees:{},
                modalTitle: '',
                bonus:{
                    id: '',
                    patterns: '',
                    amount: '',
                    employee_id: ''
                },
                editing: false,
                errors: ''
            }
        },
        created(){
            service.employees()
                .then(response => this.employees = response.data)
                .catch(e => console.log(e.response));
        },
        mounted(){
            this.getBonuses();
        },
        methods:{
            showModal (){
                this.editing = false;
                this.$bvModal.show('bonus_modal');
                this.bonus = {}
            },

            hideModal () {
                this.$bvModal.hide('bonus_modal');
                this.editing = false;
                this.bonus = {}
            },

            amountFormat (montant){
                return new Intl.NumberFormat('de-DE',{style:'currency',currency:'XOF'}).format(montant);
            },

            getBonuses (page=1){
                service.bonuses(page).then(response => {
                    this.bonuses = response.data;
                })
                    .catch(e => console.log(e.response));
            },

            onSubmit: async function () {
                try {
                    const response = await service.post_bonus(this.bonus);
                    this.bonuses.data.unshift(response.data);
                    this.hideModal();
                    this.$toastr.success("La prime de "+response.data.firstName+' '+response.data.lastName+" a été enregistrée avec succès", "SAUVEGARDE REUSSIE");
                }catch (e) {
                    switch (e.response.status) {
                        case 422:
                            this.errors = e.response.data;
                            this.$toastr.error("Un ou Plusieurs champ(s) doivent être correctement remplis !","ERREUR CHAMPS");
                            break;
                        case 500:
                            this.$toastr.warning("Une s'est declenchée lors de la connexion au serveur","ERREUR SERVEUR");
                            break;
                        default:
                            this.$toastr.info("Quelque chose s'est mal passé, veuillez rééssayer plus tard","ERREUR INCONNUE");
                    }
                }
            },

            onEdit (id){
                this.editing = true;
                service.bonus(id).then(response => {
                    this.bonus = response.data;
                    console.log(response.data)
                })
                    .catch(e => console.log(e.response));

                this.$bvModal.show('bonus_modal')
            },

            onUpdate: async  function () {
                try {
                    const response = await service.update_bonus(this.bonus.id, this.bonus);
                    this.hideModal();
                    this.$toastr.success("Votre demande de modification a été prise en charge","MODIFICATION REUSSIE");
                    this.bonuses.data.map((el, i) => {
                       if (response.data.bonus_id === el.bonus_id){
                           this.bonuses.data[i] = response.data;
                       }
                    });
                } catch (e) {
                    switch (e.response.status) {
                        case 422:
                            this.errors = e.response.data;
                            this.$toastr.error("Un ou Plusieurs champ(s) doivent être correctement remplis !","ERREUR CHAMPS");
                            break;
                        case 500:
                            this.$toastr.warning("Une s'est declenchée lors de la connexion au serveur","ERREUR SERVEUR");
                            break;
                        default:
                            this.$toastr.info("Quelque chose s'est mal passé, veuillez rééssayer plus tard","ERREUR INCONNUE");
                    }

                }
            },

            onDelete (id) {
               if(confirm("Voulez-vous vraiment supprimer ?")){
                   service.delete_bonus(id)
                       .then(response => {
                           console.log(response);
                           this.$toastr.success("Suppression de la prime est effective","SUPPRESSION REUSSIE");
                       })
                       .catch(e => console.log(e.response));
               }
            },

            onRestore (id) {
                if(confirm("Voulez-vous vraiment reccupérer ?")) {
                    service.restore_bonus(id)
                        .then(response => {
                            this.$toastr.success(response.data, "OPERATION REUSSIE")
                        })
                        .catch(e => console.log(e.response));
                }
            }
        }
    }
</script>

<style scoped>

</style>
