<template>
    <div id="rh-params">
        <div class="row">
            <div class="col-lg-6">
                <h3 class="title">Congés</h3>
                <div class="row">
                    <div class="col-lg-6 first-col">
                        <b>Jours de congés restants</b><br>
                        <b>Distance domicile - travail</b><br>
                    </div>
                    <div class="col-lg-6">
                        <span class="rh-p"><slot name="nb-j-leave">04</slot></span>&nbsp;jours
                        <span class="rh-p"><slot name="distance">15</slot></span>&nbsp;KM
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h3 class="title">Contrats</h3>
                <div class="row">
                    <div class="col-lg-6 first-col">
                        <b>Visite médicale</b><br>
                        <b>Véhicule de service</b><br>
                    </div>
                    <div class="col-lg-6">

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ParametreRh"
}
</script>

<style scoped>
.first-col{
    border-right: 1px solid rgba(0,0,0,.125);
}
.rh-p{
    border: 1px solid rgba(0,0,0,0.1);
    border-radius: 3px;
    padding: .1rem 10rem .1rem .1rem;
    margin-bottom: .5rem;
}
</style>
