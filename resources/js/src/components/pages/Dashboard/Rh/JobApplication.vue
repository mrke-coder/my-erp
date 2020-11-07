<template>
    <div id="job">
        <div class="row">
            <div class="col-lg-12 equel-grid">
                <div class="grid">
                    <p class="grid-header">BASE DE DONN&Eacute;E DES DEMANDES D'EMPLOI</p>
                    <div class="grid-body">
                        <div class="item-wrapper">
                            <table class="table table-stiped">
                               <thead>
                               <tr>
                                   <th>TYPE D'EMPLOI DEMAND&Eacute;</th>
                                   <th>TYPE DE CONTRAT</th>
                                   <th>POSTE DEMAND&Eacute;</th>
                                   <th>LETTRE DE MOTIVATION</th>
                                   <th>CV</th>
                                   <th>DATE DE LA DEMANDE</th>
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
                                    <tr v-for="job in jobApplications.data" :key="job.id">
                                        <td>{{job.request_type}}</td>
                                        <td>{{job.contract_type}}</td>
                                        <td>{{job.requested_position}}</td>
                                        <td>{{job.cover_letter}}</td>
                                        <td>{{job.cv}}</td>
                                        <td>{{job.created_at | moment("D MMM YYYY")}}</td>
                                        <td><button class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import * as service from "../../../../services/jobApplicationService";
    export default {
        name: "JobApplication",
        data (){
            return{
                jobApplications:{},
                loading: false
            }
        },
        mounted() {
            this.getJobApplications();
        },
        methods: {
            getJobApplications (page = 1){
                this.loading = true;
                service.jobApplications(page)
                    .then(response => {
                        this.loading = false;
                        this.jobApplications = response.data
                    }, 2000);
            }
        }
    }
</script>

<style scoped>

</style>
