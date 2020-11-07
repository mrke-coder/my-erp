<template>
    <div id="public-infos">
        <div class="row">
            <div class="col-lg-6">
                <h3 class="title">Informations sur le contact</h3>
                <div class="row">
                    <div class="col-lg-6 first-col">
                        <b>Ville / Commune</b><br>
                        <b>Adresse</b><br>
                        <b>Tél/contact</b>
                    </div>
                    <div class="col-lg-6">
                        <slot name="city"></slot>
                        <slot name="address"></slot>
                        <slot name="contact"></slot>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h3 class="title">Poste</h3>
                <div class="row">
                    <div class="col-lg-6 first-col">
                        <b>Département</b><br>
                        <b>Poste</b><br>
                        <b>Responsable</b><br>
                        <b>Mentor</b><br>
                        <b>Est un responsable</b>
                    </div>
                    <div class="col-lg-6">
                        <slot name="department"/>
                        <slot name="poste"/>
                        <slot name="responsable">Nom responsable</slot>
                        <slot name="mentor">Nom Du mentor</slot>
                        <slot name="is-chief">oui / non</slot>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
name: "PublicInformation"
}
</script>

<style scoped>
    .first-col{
        border-right: 1px solid rgba(0,0,0,.125);
    }
</style>
