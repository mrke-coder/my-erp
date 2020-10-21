<template>
    <div class="employee">
        <div class="row">
            <div class="col-lg-12 equel-grid">
                <div class="grid">
                    <p class="grid-header">BASE DE DONNEES DES EMPLOY&Eacute;S</p>
                    <div class="grid-body">
                        <div class="item-wrapper">
                            <button class="btn btn-primary" @click="showModal">
                                <i class="fa fa-plus"></i> Nouvel Employé
                            </button>
                            <br><br>
                            <div class="table-responsive">
                                <table class="table table-striped" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <td>EMPLOYE</td>
                                        <td>PROFESSION</td>
                                        <td>EMBAUCHER LE</td>
                                        <td>ADRESSE</td>
                                        <td>CONTACT</td>
                                        <td>ENREGISTRER LE</td>
                                        <td>ACTION</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-show="loading">
                                        <td colspan="8">
                                            <div class="text-center">
                                                <b-spinner variant="primary" label="Text Centered"></b-spinner>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-show="!loading" v-for="employee in employees.data" :key="employee.id" :class="{'is_deteled':employee.deleted_at}">
                                        <td>{{employee.lastName +' '+ employee.firstName}}</td>
                                        <td>{{employee.speciality}}</td>
                                        <td>{{employee.hire_date | moment('D MMM YYYY')}}</td>
                                        <td>{{employee.adress }}</td>
                                        <td>{{employee.contact}}</td>
                                        <td>{{employee.created_at | moment('DD/M/Y')}}</td>
                                        <td>
                                            <button class="btn btn-success btn-xs" @click.prevent="onEdit(employee.id)" :disabled="employee.deleted_at"><i class="fa fa-edit"></i></button>
                                            <button class="btn btn-danger btn-xs" @click.prevent="onDelete(employee.id)" :disabled="employee.deleted_at"><i class="fa fa-trash"></i></button>
                                            <button class="btn btn-info btn-xs" @click.prevent="employee_informations(employee.id)" :disabled="employee.deleted_at"><i class="fa fa-info"></i></button>
                                            <button class="btn btn-warning btn-xs" @click.prevent="onRestore(employee.id)" v-show="employee.deleted_at"><i class="fa fa-refresh"></i></button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <pagination :data="employees" @pagination-page-change="getEmployees">
                                <span slot="prev-nav">Précédent</span>
                                <span slot="next-nav">Suivant</span>
                            </pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <b-modal id="employee_modal" size="lg" :title="modalTitle" hide-footer>
            <form autocomplete="off" id="add_form">
                <div class="row">
                    <div class="form-group col-lg-6 col-sm-6 mb-3">
                        <div class="form-inline">
                            <div class="radio mb-3">
                                <label class="radio-label mr-4">
                                    <input v-model="employee.family_situation" type="radio" checked value="Marié(e)">Marié(e) <i class="input-frame"></i>
                                </label>
                            </div>
                            <div class="radio mb-3">
                                <label class="radio-label">
                                    <input v-model="employee.family_situation" type="radio" value="Célibataire">Célibataire<i class="input-frame"></i>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-sm-6 mb-3">
                        <div class="form-inline">
                            <div class="radio mb-3">
                                <label class="radio-label mr-4">
                                    <input v-model="employee.civility" type="radio" checked value="M">Monsieur<i class="input-frame"></i>
                                </label>
                            </div>
                            <div class="radio mb-3">
                                <label class="radio-label">
                                    <input v-model="employee.civility" type="radio" value="Mme/Mlle">Madame / Mademoiselle <i class="input-frame"></i>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-sm-6 mb-3">
                        <label>Diplôme De L'Employé</label>
                        <input v-model="employee.diploma" :class="{'is-invalid':errors.diploma}" class="form-control" placeholder="Entrer Le Diplôme De L'Employé"/>
                        <div class="invalid-feedback" role="alert" v-if="errors.diploma">
                            {{errors.diploma[0]}}
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-sm-6 mb-3">
                        <label>Référence De La Pièce D'identité De L'Employé</label>
                        <input v-model="employee.cni_reference" class="form-control" :class="{'is-invalid':errors.cni_reference}" type="text" placeholder="Entrer La Référence De La Pièce D'identité"/>
                        <div class="invalid-feedback" role="alert" v-if="errors.cni_reference">
                            {{errors.cni_reference[0]}}
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-sm-6 mb-3">
                        <label>Nom De Famille</label>
                        <input v-model="employee.lastName" class="form-control" :class="{'is-invalid':errors.lastName}" placeholder="Entrer Le Nom De Famille" type="text" />
                        <div class="invalid-feedback" role="alert" v-if="errors.lastName">
                            {{errors.lastName[0]}}
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-sm-6 mb-3">
                        <label>Prénom</label>
                        <input v-model="employee.firstName" class="form-control" :class="{'is-invalid':errors.firstName}" placeholder="Entrer Le Prénom" type="text" />
                        <div class="invalid-feedback" role="alert" v-if="errors.firstName">
                            {{errors.firstName[0]}}
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-sm-6 mb-3">
                        <label>Ville D'Habitation</label>
                        <input v-model="employee.ville" class="form-control" :class="{'is-invalid':errors.city}" placeholder="Entrer Le Prénom" type="text" />
                        <div class="invalid-feedback" role="alert" v-if="errors.city">
                            {{errors.city[0]}}
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-sm-6 mb-3">
                        <label>Adresse De L'Employé</label>
                        <input v-model="employee.adress" class="form-control" :class="{'is-invalid':errors.adress}" type="text" placeholder="Entrer Adresse Exacte De L'Employé"/>
                        <div class="invalid-feedback" role="alert" v-if="errors.adress">
                            {{errors.adress[0]}}
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-sm-6 mb-3">
                        <label>Contacts Téléphoniques</label>
                        <input v-model="employee.contact" class="form-control" :class="{'is-invalid':errors.contact}" type="tel" placeholder="Entrer Les Contacts Téléphoniques De L'Employés"/>
                        <div class="invalid-feedback" role="alert" v-if="errors.contact">
                            {{errors.contact[0]}}}
                        </div>
                    </div>

                    <div class="form-group col-lg-6 col-sm-6 mb-3">
                        <label>Adresse Email</label>
                        <input v-model="employee.email" class="form-control" :class="{'is-invalid':errors.email}" type="email" placeholder="Entrer L'Adresse Electronique De L'Employé"/>
                        <div class="invalid-feedback" role="alert" v-if="errors.email">
                            {{errors.email[0]}}}
                        </div>
                    </div>

                    <div class="form-group col-lg-6 col-sm-6 mb-3">
                        <label>Anniversaire De L'Employé</label>
                        <input v-model="employee.birth_day" class="form-control" :class="{'is-invalid':errors.birthDay}" type="date" placeholder="Choisir La Date De Naissance"/>
                        <div class="invalid-feedback" role="alert" v-if="errors.birth_day">
                            {{errors.birth_day[0]}}}
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-sm-6 mb-3">
                        <label>Date D'Embauche</label>
                        <input v-model="employee.hire_date" class="form-control" :class="{'is-invalid':errors.hire_date}" type="date" placeholder="Choisir la Date D'Embauche"/>
                        <div class="invalid-feedback" role="alert" v-if="errors.hire_date">
                            {{errors.hire_date[0]}}}
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-sm-6 mb-3">
                        <label>Entrer Le Nombre D'Enfant De L'Employé</label>
                        <input v-model="employee.child_number" class="form-control" :class="{'is-invalid':errors.child_number}" type="number" min="0" placeholder="Entrer Le nombre d'Enfant"/>
                        <div class="invalid-feedback" role="alert" v-if="errors.child_number">
                            {{errors.child_number[0]}}}
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-sm-6 mb-3">
                        <sui-form-field>
                            <sui-form-field>
                                <label>Spécialité De L'Employé</label>
                                <sui-form-field required :class="{'error':errors.speciality_id}">
                                    <sui-dropdown
                                        :options="specialities"
                                        placeholder="Choisir La Spécialité De L'Employé"
                                        search
                                        selection
                                        v-model="employee.speciality_id"
                                    />
                                </sui-form-field>
                            </sui-form-field>
                        </sui-form-field>
                    </div>
                    <div class="form-group mb-3">
                        <button class="btn btn-danger" @click.prevent="hideModal" type="reset">Annuler</button>
                        <button class="btn btn-success" type="submit" @click.prevent="onSubmit" v-show="!editing">Sauvegarder</button>
                        <button class="btn btn-warning" type="submit" @click.prevent="onUpdate" v-show="editing">Sauvegarder</button>
                    </div>
                </div>
            </form>
        </b-modal>
    </div>
</template>

<script>
    import * as service from "../../../../services/employeeService"
    export default {
        name: "Employee",
        data (){
            return {
                loading:false,
                modalTitle:'',
                employees:{},
                employee:{
                    id: '',
                    civility:'',
                    lastName:'',
                    firstName:'',
                    cni_reference:'',
                    adress:'',
                    ville:'',
                    contact:'',
                    email:'',
                    birth_day:'',
                    family_situation:'',
                    sexe: '',
                    hire_date:'',
                    child_number:0,
                    diploma:'',
                    user_id:'',
                    speciality_id:''
                },
                specialities: [],
                errors:'',
                editing: false,
            }
        },
        created (){
            service.specialities()
                .then(response => this.specialities = response.data)
                .catch(e => console.log(e.response))

        },
        mounted() {
            this.getEmployees();
        },
        methods:{
            getEmployees: async function (page = 1){
                this.loading = true;
                service.employees(page)
                    .then(response => {
                        setTimeout(() => {
                           this.employees = response.data;
                           this.loading = false;
                        },3000);
                    })
                    .catch(error => {
                        setTimeout(() => {
                            console.log(error.response.data);
                            this.$toastr.warning("Aucun employé trouvé dans nos enregistrement", "SELECTION VIDE");
                            this.loading = false;
                        },2000)
                    });
            },

            showModal (){
                this.editing = false;
               this.employee = {};
                this.employee.id = '';
                this.$bvModal.show('employee_modal')
            },

            hideModal (){
                this.$bvModal.hide('employee_modal');
                this.errors = '';
                this.employee = {};
            },

            onSubmit: async function () {
                try {
                    const response = await service.post_employee(this.employee);
                    this.employees.data.unshift(response.data);
                    this.hideModal();
                    this.$toastr.success("Employé Créé avec succès", "NOUVEL EMPLOYE");
                    console.log(response.data)
                } catch (e) {
                    switch (e.response.status) {
                        case 422:
                            console.error((e.response.data));
                            this.errors = e.response.data;
                            this.$toastr.error("Un ou Plusieurs champs ne sont pas correctement renseignés", "ERREUR CHAMPS");
                            break;
                        case 500:
                            console.log(e.response.data);
                            this.$toastr.warning("Erreur rencontrée lors de la connexion au serveur.", "ERREUR SERVEUR");
                            break;
                        default:
                            console.log(e.response.data);
                            this.$toastr.info("Erreur non repertoriée est rencontrée.", "ERREUR INCONNUE");
                    }
                }
            },

            onEdit(id){
                this.editing = true;
                this.$bvModal.show('employee_modal');
                this.modalTitle = "MODIFICATION DE L'EMPLOYE N°"+id;

                service.employee(id).then(response =>this.employee = response.data)
                    .catch(error => console.error(error.response));

            },

            onUpdate: async function () {
                try {
                    const response = await service.update_employee(this.employee.id, this.employee);
                    console.log('updated_ok :',response.data);
                    this.employees.data.map((el,i) => {
                       if (el.id === response.data.id){
                           this.employees.data[i] = response.data;
                       }
                    });
                    this.$toastr.success("Information de l'employé modifiées avec succès", "MODIFICATION REUSSIE");
                    this.hideModal();
                } catch (e) {
                    switch (e.response.data.status) {
                        case 422:
                            console.error((e.response.data));
                            this.errors = e.response.data;
                            this.$toastr.error("Un ou Plusieurs champs ne sont pas correctement renseignés", "ERREUR CHAMPS");
                            break;
                        case 500:
                            console.log(e.response.data);
                            this.$toastr.warning("Erreur rencontrée lors de la connexion au serveur.", "ERREUR SERVEUR");
                            break;
                        default:
                            console.log(e.response.data);
                            this.$toastr.info("Erreur non repertoriée est rencontrée.", "ERREUR INCONNUE");
                    }
                }
            },

            onDelete: async function (id) {
                if (confirm("Êtes-vous sûr de vouloir supprimer cet employé ?")){
                    service.delete_employee(id)
                        .then(response =>{
                            this.$toastr.success(response.data.message,"SUPPRESSION REUSSION");
                            console.log(response.data.data)
                            this.employees.data.map((el,i) => {
                                if (el.id === id){
                                    this.employees.data[i] = response.data.data
                                }
                            })
                        })
                        .catch(error => console.log(error.response));
                }
            },

            onRestore: async function (id) {
                if (confirm("Voulez-vous vraiment reccupérer cet employé ?")){
                    try {
                        const response = await service.restore_employee(id);
                        this.$toastr.success(response.data,"RECCUPERATION REUSSIE");
                    }catch (e) {
                        if (e.response.status === 500){
                            this.$toastr.error("Erreur lors de la connexion au serveur, rééssayer ultérieurement","ERREUR SERVEUR");
                        }else {
                            this.$toastr.warning("Erreur non repertoriée rencontrée","ERREUR INCONNUE");
                        }
                    }
                }
            },

            employee_informations(id) {
                this.$router.push({ path: `/dashboard/admin/rh/employes/${id}/informations`});
            }
        }
    }
</script>

<style>

</style>
