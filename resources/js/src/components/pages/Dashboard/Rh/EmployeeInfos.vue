<template>
    <div id="info_employee">
        <div class="row">
            <div class="col-lg-12 equel-grid">
                <div class="grid">
                    <div class="grid-body">
                        <div class="item-wrapper d-flex justify-content-center mb-3" v-if="loading">
                            <b-spinner variant="primary" label="Spinning"></b-spinner>
                        </div>
                        <div class="item-wrapper" v-if="!loading">
                            <div class="row">
                                <div class="col-lg-6 profil">
                                    <div class="text-center">
                                        <img src="https://image.shutterstock.com/image-photo/head-shot-close-company-executive-260nw-567771793.jpg" alt="EMPLOYE_PHOTO" width="100" height="100"><br>
                                        <button class="btn btn-danger btn-sm btn-rounded"><i class="fa fa-plus"></i></button>
                                    </div>
                                    <div class="info_profil">
                                        <h1>{{employeeData.lastName+' '+employeeData.firstName}}</h1>
                                        <p>{{speciality.description}}</p>
                                        <p>{{employeeData.email}}</p>
                                        <p>POST 0089</p>
                                    </div>
                                </div>
                                <div class="col-lg-6 infos_pro">
                                    <div class="row">
                                        <div class="pro_element col-lg-4">
                                            <i class="fa fa-calendar"></i>&nbsp;&nbsp;
                                            0<br>
                                            <span class="ml-4">Congés</span>
                                        </div>
                                        <div class="pro_element col-lg-4">
                                            <i class="fa fa-sitemap"></i>&nbsp;
                                            0<br>
                                            <span class="ml-4">Evaluations</span>
                                        </div>
                                        <div class="pro_element col-lg-4">
                                            <i class="fa fa-book"></i>&nbsp;
                                            0<br>
                                            <span class="ml-4">Contrats</span>
                                        </div>
                                        <div class="col-lg-4">
                                        </div>
                                        <div class="pro_element col-lg-4">
                                            <i class="fa fa-clock"></i>&nbsp;
                                            0<br>
                                            <span class="ml-4">absences</span>
                                        </div>
                                        <div class="pro_element col-lg-4">
                                            <i class="fa fa-money"></i>&nbsp;
                                            0 <br>
                                            <span class="ml-4">Feuilles de paie</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="menu row">
                                        <router-link class="menu-item col-lg-4" :to="`/dashboard/admin/rh/employes/${employeeData.id}/informations/informations-publiques`" exact>Informations publiques</router-link>
                                        <router-link class="menu-item col-lg-4" :to="`/dashboard/admin/rh/employes/${employeeData.id}/informations/informations-personnelles`" exact>Informations Personnelles</router-link>
                                        <router-link class="menu-item col-lg-4" :to="`/dashboard/admin/rh/employes/${employeeData.id}/informations/parametres-rh`" exact>Paramètres RH</router-link>
                                    </div>
                                    <div class="row content">
                                        <div id="infos_publics" class="col-lg-12" v-show="$route.params.page === 'informations-publiques'">
                                            <PublicInformation>
                                                <template v-slot:city>{{employeeData.ville}}<br></template>
                                                <template v-slot:address>{{employeeData.adress}}<br></template>
                                                <template v-slot:contact>{{employeeData.contact}}</template>
                                                <template v-slot:department>{{department.name}}<br></template>
                                                <template v-slot:poste>{{speciality.description}}<br></template>
                                                <template v-slot:responsable><br></template>
                                                <template v-slot:mentor><br></template>
                                                <template v-slot:is-chief><br></template>
                                            </PublicInformation>
                                        </div>
                                        <div id="infos_perso" class="col-lg-12" v-show="$route.params.page === 'informations-personnelles'">
                                            <PersonnalInformation>
                                                <template v-slot:genre>{{employeeData.civility}}<br></template>
                                                <template v-slot:etat-civil>{{employeeData.family_situation}}<br></template>
                                                <template v-slot:nb-enfant>{{employeeData.child_number}}<br></template>
                                                <template v-slot:birthday>{{employeeData.birth_day | moment("DD MMMM YYYY")}}<br></template>
                                                <template v-slot:born-city>Côte d'Ivoire<br></template>
                                                <template v-slot:cni>{{employeeData.cni_reference}}<br></template>
                                                <template v-slot:nationality>Ivoirien<br></template>
                                            </PersonnalInformation>
                                        </div>
                                        <div id="rh_params" class="col-lg-12" v-show="$route.params.page === 'parametres-rh'">
                                            <ParametreRh>
                                                <template v-slot:nb-j-leave></template>
                                            </ParametreRh>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import *as service from "../../../../services/employeeService";
    import PublicInformation from "./EmployeeDocs/PublicInformation";
    import PersonnalInformation from "./EmployeeDocs/PersonnalInformation";
    import ParametreRh from "./EmployeeDocs/ParametreRh";
    export default {
        name: "EmployeeInfos",
        data () {
            return {
                employeeData: {},
                department: {},
                speciality: {},
                loading: false
            }
        },
        components:{ParametreRh, PersonnalInformation, PublicInformation},
       mounted () {
           this.getEmployee();
       },
        methods: {
            getEmployee () {
                this.loading = true;
                service.details_employee(this.$route.params.id)
                    .then(response => {
                        setTimeout(() => {
                            console.log(response)
                            this.loading = false;
                            this.employeeData = response.data.employee;
                            this.department = response.data.department;
                            this.speciality = response.data.speciality;
                        },3000);
                });
            }
        }
    }
</script>

<style scoped>
    .profil{
        display: flex;
        align-items: flex-start;
    }
    .info_profil{
        margin-left: 15px;
    }
    .infos_pro{
        display: flex;
    }
    .pro_element{
        border: 1px solid #b0f0ff;
        padding-top: .5rem;
        margin: 0;
        width: 33%;
        height: 60px;
    }
    .menu{
        margin: 1.5rem 0;
    }
    .menu-item{
        width: 100%;
        padding: .5rem;
        border: 1px solid transparent;
        border-bottom: 1px solid rgba(0,0,0,.125);
    }

    .menu .menu-item{
        padding: 1.5rem;
        color: rgba(0,0,0,0.85);
    }

    a.router-link-exact-active.router-link-active,  .menu-item:hover
    {
        background-color: #7fb2d4;
        border-bottom: 3px solid #223C61;
        border-left: 3px solid transparent;
        color: white !important;
        font-weight: bold;
    }
</style>
