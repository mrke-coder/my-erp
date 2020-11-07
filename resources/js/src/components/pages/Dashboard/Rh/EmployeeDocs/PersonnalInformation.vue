<template>
    <div id="infos-perso">
        <div class="row">
            <div class="col-lg-6">
                <h3 class="title">Nationalité & Autres infos</h3>
                <div class="row">
                    <div class="col-lg-6 first-col">
                        <b>Nationalité</b><br>
                        <b>N° Carte Nationale d'Identitité</b><br>
                        <b>N° de Passeport</b><br>
                        <b>N° de Sécurité Sociale</b>
                    </div>
                    <div class="col-lg-6">
                        <slot name="nationality"/>
                        <slot name="cni"/>
                    </div>
                </div>
                <h3 class="title">&Eacute;tat</h3>
                <div class="row">
                    <div class="col-lg-6 first-col">
                        <b>Genre</b><br>
                        <b>&Eacute;tat civle</b><br>
                        <b>Nombre d'enfant</b>
                    </div>
                    <div class="col-lg-6">
                        <slot name="genre"/>
                        <slot name="etat-civil"/>
                        <slot name="nb-enfant"/>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h3 class="title">Informations sur le contact</h3>
                <div class="row">
                    <div class="col-lg-6 first-col">
                        <b>Adresse Personnelle</b>
                    </div>
                    <div class="col-lg-6"></div>
                </div>
                <h3 class="title">Naissance</h3>
                <div class="row">
                    <div class="col-lg-6 first-col">
                        <b>Date de naissance</b><br>
                        <b>Lieu de naissance</b>
                    </div>
                    <div class="col-lg-6">
                        <slot name="birthday"/>
                        <slot name="born-city"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
name: "PersonnalInformation"
}
</script>

<style scoped>
.first-col{
    border-right: 1px solid rgba(0,0,0,.125);
}
</style>
