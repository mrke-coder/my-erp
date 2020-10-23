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
                                    <tr v-for="job in jobApplications" :key="job.id">
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
                jobApplications:{}
            }
        },
        mounted() {
            this.getJobApplications();
        },
        methods: {
            getJobApplications (page = 1){
                service.jobApplications(page)
                    .then(response => this.jobApplications = response.data);
            }
        }
    }
</script>

<style scoped>

</style>
