<template>
    <div id="email">
         <div class="grid">
            <div class="grid-body">
                <h3>Modifier Adresse email de connexion</h3>
                <form>
                    <div class="form-group">
                        <label>E-mail Actuel</label>
                        <input type="email" class="form-control" :value="$store.state.userProfile.email" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nouvelle Adresse E-mail</label>
                        <input :class="{'is-invalid':invalid || errors.email}" placeholder="Entrez adresse email" type="email" class="form-control" v-model="user.email">
                        <div class="invalid-feedback" v-if="errors.email">{{errors.email[0]}}</div>
                    </div>
                    <div class="form-group">
                        <button @click.prevent="showModal()" class="btn btn-primary" type="button">Continuer</button>
                    </div>
                </form>
                <b-modal id="confirm" hide-footer title="Saisissez votre mot de passe pour confirmer la modification ?">
                     <div class="form-group">
                        <label>Mot de passe</label>
                        <input :class="{'is-invalid':errors.password}"
                        placeholder="Entrez mot de passe"
                        type="password"
                        class="form-control"
                        v-model="user.password"
                        >
                        <div class="invalid-feedback" v-if="errors.password">{{errors.password[0]}}</div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" type="button" @click.prevent="onSaveEmail()">Enregistrer</button>
                    </div>
                </b-modal>
            </div>
        </div>
    </div>
</template>

<script>
import *as auth from '../../../../services/authService';
    export default {
        name: "Email",
        data (){
             return {
                 user: {
                     id: '',
                     email: '',
                     password: ''
                 },
                 invalid: false,
                 errors: ''
             }
        },
        methods: {
             onSaveEmail: async function () {
                try {
                    this.user.id = this.$store.state.userProfile.id;
                    const response = await auth.update_email(this.user);
                    this.$toastr.success("Votre adresse email d'authentification a été modifiée", "MODIFICATION")
                    this.$store.state.userProfile = response.data;
                    this.hideModal();
                } catch (e) {
                    switch(e.response.status){
                        case 422:
                            this.errors = e.response.data;
                            this.errors.email ? this.$bvModal.hide('confirm'): null;
                            break;
                        case 404:
                            this.$toastr.error(e.response.data, "ERREUR VALIADATION");
                            break;
                        default:
                            this.$toastr.warning("Quelque chose s'est mal passé", "ERREUR INCONNUE")
                    }
                }
            },
            showModal () {
                if(this.user.email.length === 0){
                    this.$toastr.error('Renseignez le champ adresse email', "OBLIGATOIRE");
                    this.invalid = true;
                } else if(this.user.email === this.$store.state.userProfile.email){
                    this.$toastr.error('Désolé, vous devez saisir une adresse email différente.', "ERREUR");
                    this.invalid = true;
                } else{
                    this.invalid=false;
                    this.$bvModal.show('confirm');
                }
            },
            hideModal () {
                this.user = {};
                this.errors = ''
                this.$bvModal.hide('confirm');
            }
        }
    }
</script>

<style scoped>

</style>
