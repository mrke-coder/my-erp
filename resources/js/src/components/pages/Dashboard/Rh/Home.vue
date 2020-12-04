<template>
    <div id="home">
        <div class="row">
            <div class="col-12 py-5">
                <h4>Dashboard</h4>
                <p class="text-gray" >Bienvenue à bord, {{$store.state.userProfile.firstName+' '+$store.state.userProfile.lastName}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-6 equel-grid">
                <div class="grid">
                    <div class="grid-body text-gray">
                        <div class="d-flex justify-content-between">
                            <p></p>
                            <p></p>
                        </div>
                        <p class="text-black">DEPARTEMENTS</p>
                        <div class="wrapper w-50 mt-4">
                            <canvas height="45" id="stat-line_1"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-6 equel-grid">
                <div class="grid">
                    <div class="grid-body text-gray">
                        <div class="d-flex justify-content-between">
                            <p></p>
                            <p></p>
                        </div>
                        <p class="text-black">SPACIALITE</p>
                        <div class="wrapper w-50 mt-4">
                            <canvas height="45" id="stat-line_2"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-6 equel-grid">
                <div class="grid">
                    <div class="grid-body text-gray">
                        <div class="d-flex justify-content-between">
                            <p></p>
                            <p></p>
                        </div>
                        <p class="text-black">EMPLOYES</p>
                        <div class="wrapper w-50 mt-4">
                            <canvas height="45" id="stat-line_3"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-6 equel-grid">
                <div class="grid">
                    <div class="grid-body text-gray">
                        <div class="d-flex justify-content-between">
                            <p></p>
                            <p></p>
                        </div>
                        <p class="text-black">EMPLOYES</p>
                        <div class="wrapper w-50 mt-4">
                            <canvas height="45" id="stat-line_4">

                            </canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Home",
        mounted (){
            this.$store.state.pageName ="Ressources Humaines - Accueil";
        }
    }
</script>

<style scoped>

</style>
