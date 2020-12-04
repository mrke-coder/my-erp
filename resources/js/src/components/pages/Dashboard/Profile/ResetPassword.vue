<template>
    <div id="password">
        <div class="grid">
            <div class="grid-body">
                <h3>Modifier le mot de passe</h3>
                <form @submit.prevent="onSavePassword">
                    <div class="form-group">
                        <label>Mot De Passe Actuel</label>
                        <input placeholder="Entrez Le Mot De Passe Actuel"
                         type="password" class="form-control" :class="{'is-invalid': errors.password}" v-model="user.password">
                         <div class="invalid-feedback" v-if="errors.password">{{errors.password[0]}}</div>
                    </div>
                    <div class="form-group">
                        <label>Nouveau Mot De Passe</label>
                        <input :class="{'is-invalid': errors.new_password}" placeholder="Entrez Le Nouveau Mot De Passe" type="password" class="form-control" v-model="user.new_password">
                        <div class="invalid-feedback" v-if="errors.new_password">{{errors.new_password[0]}}</div>
                    </div>
                     <div class="form-group">
                        <label>Confirmez Le Nouveau Mot De Passe</label>
                        <input placeholder="Répétez Le Nouveau Mot De Passe" type="password" class="form-control" v-model="user.new_password_confirmation">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import *as auth from '../../../../services/authService';
    export default {
        name: "ResetPassword",
        data () {
            return {
                user: {
                id: '',
                password: '',
                new_password: '',
                new_password_confirmation: ''
            },
            errors: ''
        }
        },

        methods: {
            onSavePassword: async function () {
                try {
                    this.user.id = this.$store.state.userProfile.id;
                    const response = await auth.update_password(this.user);
                    this.$toastr.success("Mot de passe modififié avec succès", 'SAUVEGARDE REUSSIE');
                    this.errors ='';
                    this.user = {};
                    if(confirm("voulez-vous reconnecter avec le nouveau mot de passe ?")){
                        auth.logout();
                        this.$router.push('/');
                    }
                } catch (e) {
                    console.log(e.response)
                    switch (e.response.status) {

                        case 422:
                            this.errors = e.response.data;
                            this.$toastr.error('Un ou plusieurs champs ont été mal renseignés', "ERREUR CHAMPS")
                            break;
                        case 404:
                            this.$toastr.warning(e.response.data, 'AVERTISSEMENT')
                            this.errors ='';
                            break;
                        case 500:
                            this.$toastr.error('Une erreur survenue lors de la connexion au serveur', 'ERREUR SERVEUR')
                            this.errors ='';
                            break;
                        default:
                            this.$toastr.seconday("Quelque chose s'est mal passé", "ERREUR INCONNUE");
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>
