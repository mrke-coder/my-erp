<template>
    <div id="account">
        <div class="grid">
            <div class="grid-body">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3>Mes informations</h3>
                    <button type="button"
                    :class="`btn ${editing ?'btn-danger': 'btn-success'}`" @click.prevent="editing=!editing">
                    <i  :class="`fa ${editing ? 'fa-close': 'fa-edit'}`"></i>
                    &nbsp;{{editing ? 'Annuler':'Modifier'}}
                    </button>
                </div>
                <form @submit.prevent="onSave()" autocomplete="off">
                    <div class="form-group">
                        <label>Prénoms</label>
                        <input type="text" class="form-control"
                        v-model="admin.firstName" autofocus :readonly="!editing"
                        :class="{'is-invalid':errors.firstName}"
                        />
                        <div class="invalid-feedback" v-if="errors.firstName">{{errors.firstName[0]}}</div>
                    </div>
                    <div class="form-group">
                        <label>Nom De Famille</label>
                        <input type="text"
                        class="form-control"
                        v-model="admin.lastName"
                        :readonly="!editing"
                        :class="{'is-invalid':errors.lastName}"/>
                        <div class="invalid-feedback" v-if="errors.lastName">{{errors.lastName[0]}}</div>
                    </div>
                    <div class="form-group">
                        <label>Capacités</label>
                        <input type="text" class="form-control" v-model="admin.capacity" :readonly="!editing"/>
                    </div>
                    <div class="form-group">
                        <label>Hobbies / séparer de virgule(,)</label>
                        <input type="text" class="form-control" v-model="admin.hobby" :readonly="!editing"/>
                    </div>
                    <div class="form-group">
                        <label>A props de moi </label>
                        <textarea v-model="admin.bio" rows="5" class="form-control" :readonly="!editing"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" v-show="editing">
                        <i class="fa fa-save"></i>&nbsp;
                        Sauvegarder
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import {mapState} from 'vuex';
import *as auth from '../../../../services/authService';
export default {
name: "Account",
    data() {
        return {
            editing: false,
            errors: ''
        }
    },
    mounted() {
        this.$store.state.pageName = "Compte"
    },
    computed: mapState({
        admin: state => state.userProfile
    }),

    methods: {
        onSave: async function () {
            try {
                const response = await auth.update_profile(this.admin);
                auth.getProfil();
                this.editing = false;
                this.$toastr.success("Vos informations ont été mis à jour avec succès")
            } catch (error) {
                 switch(e.response.status){
                        case 422:
                            this.errors = e.response.data;
                            break;
                        case 500:
                            this.$toastr.error("Erreur lors de la connexon au serveur", "ERREUR SERVEUR");
                            break;
                        default:
                            this.$toastr.warning("Quelque chose s'est mal passé", "ERREUR INCONNUE")
                    }
            }
        }
    }
}
</script>

<style scoped>

</style>
