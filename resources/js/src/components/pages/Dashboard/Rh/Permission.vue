<template>
   <div id="permission">
       <div class="row">
            <div class="col-lg-12 equel-grid">
                <div class="grid">
                    <p class="grid-header">BASE DE DONN&Eacute;ES DES PERMISSIONS</p>
                    <div class="grid-body">
                        <div class="item-wrapper">
                            <button class="btn btn-primary" @click.prevent="showModal">
                                <i class="fa fa-plus"></i>&nbsp; Nouvelle permission
                            </button>
                            <br><br>
                            <div class="table-responsive">
                                <table class="table table-stripped" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>EMPLO&Eacute;</th>
                                            <th>DATE D&Eacute;BUT</th>
                                            <th>DATE FIN</th>
                                            <th>ACTIONS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="leave in permissions.data" :key="leave.leave_id" :class="{'is_deteled': leave.leave_deleted_at}">
                                            <td>{{leave.firstName+' '+leave.lastName}}</td>
                                            <td>{{leave.start | moment('D MMM YYYY')}}</td>
                                            <td>{{leave.end | moment('D MMM YYYY')}}</td>
                                            <td>
                                                <button class="btn btn-success" @click.prevent="onEditing(leave.leave_id)" :disabled="leave.leave_deleted_at"><i class="fa fa-edit"></i></button>
                                                <button class="btn btn-danger" v-show="!leave.leave_deleted_at" @click.prevent="onDelete(leave.leave_id)"><i class="fa fa-trash"></i></button>
                                                <button class="btn btn-warning" v-show="leave.leave_deleted_at" @click.prevent="onRestore(leave.leave_id)"><i class="fa fa-refresh"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>

                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
       </div>
       <b-modal id="leave_modal" size="md" :title="modalTitle" hide-footer>
           <form autocomplete="off">
               <div class="row">
                   <div class="form-group col-lg-12 col-sm-12">
                       <label>EMPLO&Eacute;</label>
                       <sui-dropdown
                           :options="employees"
                           placeholder="Choisir Un Employé"
                           search
                           selection
                           v-model="permission.employee_id"
                           :class="{'error':errors.employee_id}"
                       />
                        <div class="invalid-feedback" role="alert" v-if="errors.employee_id">
                            Le champ employé est obligatoire.
                        </div>
                   </div>
                   <div class="form-group col-lg-12 col-sm-12">
                       <label>DATE DE D&Eacute;BUT DE LA PERMISSION</label>
                       <b-form-datepicker v-model="permission.leave_date" class="mb-2" :class="{'is-invalid':errors.leave_date}"></b-form-datepicker>
                       <div class="invalid-feedback" role="alert" v-if="errors.leave_date">
                           Le champ date de début est obligatoire.
                       </div>
                   </div>
                   <div class="form-group col-lg-12 col-sm-12">
                       <label>DATE DE FIN DE LA PERMISSION</label>
                       <b-form-datepicker v-model="permission.leave_end_date" class="mb-2"></b-form-datepicker>

                   </div>
                   <div class="form-group col-lg-12 col-sm-12">
                       <label>MOTIFS DE LA PERMISSION</label>
                       <textarea class="form-control" v-model="permission.patterns" rows="3" placeholder="les motifs" :class="{'is-invalid':errors.patterns}"></textarea>
                       <div class="invalid-feedback" role="alert" v-if="errors.patterns">
                           Le champ Motif de la permission est obligatoire.
                       </div>
                   </div>
                   <div class="form-group col-lg-12 col-sm-12">
                       <button class="btn btn-danger" type="reset" @click.prevent="hideModale">Annuler</button>
                       <button class="btn btn-success" type="submit" v-show="!editing" @click.prevent="onSubmit">Sauvegarder maintenant</button>
                       <button class="btn btn-warning" type="submit" v-show="editing" @click.prevent="onUpdate">Modifier maintenant</button>
                   </div>
               </div>
           </form>
       </b-modal>
   </div>
</template>

<script>
    import *as service from '../../../../services/leaveService';
    import {update_permission} from "../../../../services/leaveService";
    export default {
        name: "Permission",
        data (){
            return{
                modalTitle:'',
                editing: false,
                errors: '',
                employees: {},
                permissions:{},
                permission: {
                    id: '',
                    leave_date: '',
                    leave_end_date: '',
                    patterns: '',
                    employee_id: ''
                },
            }
        },
        created() {
            service.employees()
                .then(response => this.employees = response.data)
                .catch(e => console.log(e));
        },
        mounted() {
            this.getLeaves();
        },
        methods:{
            showModal (){
                this.$bvModal.show('leave_modal');
                this.permission = {};
                this.modalTitle = "Ajout d'une nouvelle permission";
            },

            hideModale () {
                this.$bvModal.hide('leave_modal');
                this.editing= false;
                this.permission = {};
            },

            getLeaves (page = 1) {
                service.permissions(page)
                    .then(response => {
                        this.permissions = response.data;
                    })
                    .catch(e => console.log(e));
            },

            onSubmit: async function () {
                try {
                    const response = await service.post_permission(this.permission);
                    this.hideModale();
                    this.$toastr.success("Permission enregistrée avec succès !", "SAUVEGARDE REUSSIE")
                    this.permissions.data.unshift(response.data)
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
                this.editing =true;
                service.permission(id)
                    .then(response => {
                        this.permission = response.data;
                    })
                    .catch(e => console.log(e.response));
                this.$bvModal.show('leave_modal')
            },

            onUpdate: async function (){
                try {
                    const response = await service.update_permission(this.permission);
                    this.hideModale();
                    this.$toastr.success("Modification de la permission a été effectuée avec succès","MODIFICATION REUSSIE");
                    this.permission = {};
                    this.permissions.data.map((el, i) => {
                       if (el.leave_id === response.data.leave_id){
                           this.permissions.data[i] = response.data;
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
                    service.delete_permission(id)
                        .then(response => {
                            this.$toastr.success("La Permission a été supprimée avec succès","SUPPRESSION REUSSIE")
                        })
                        .catch(e => {
                            this.$toastr.error("Quelque s'est mal passé lors de la suppression", "ERREUR SERVEUR");
                            console.log(e.response)
                        });
                }
            },

            onRestore (id) {
                if (confirm("Voulez-vous vraiment récuperer ?")){
                    service.restore_leave(id)
                        .then(response => {
                            this.$toastr.success(response.data, "SUPPRESSION REUSSIE")
                        })
                        .catch(e => this.$toastr.error("Quelques chose s'est mal passé !" ,"ERREUR SERVEUR"));
                }
            }
        }
    }
</script>

<style scoped>

</style>
