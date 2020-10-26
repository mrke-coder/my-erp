<template>
    <ul class="navigation-menu">
        <li class="nav-category-divider">MENU PRINCIPAL</li>

        <li>
            <router-link to="/dashboard/admin/rh" exact>
                <span class="link-title">Accueil</span>
                <i class="mdi mdi-home link-icon"></i>
            </router-link>
        </li>

        <li>
            <router-link to="/dashboard/admin/rh/departements" exact>
                <span class="link-title">Départements</span>
                <i class="mdi mdi-home-assistant link-icon"></i>
            </router-link>
        </li>

        <li>
            <router-link to="/dashboard/admin/rh/specialites" exact>
                <span class="link-title">Spécialités</span>
                <i class="mdi mdi-hexagon-multiple link-icon"></i>
            </router-link>
        </li>

        <li>
            <router-link to="/dashboard/admin/rh/employes" exact>
                <span class="link-title">Employées</span>
                <i class="mdi mdi-account-star link-icon"></i>
            </router-link>
        </li>

        <li>
            <router-link to="/dashboard/admin/rh/salaires" exact>
                <span class="link-title">Salaires</span>
                <i class="mdi mdi-coins link-icon"></i>
            </router-link>
        </li>
        <li>
            <router-link to="/dashboard/admin/rh/primes" exact>
                <span class="link-title">Primes des employés</span>
                <i class="mdi mdi-coin link-icon"></i>
            </router-link>
        </li>
        <li>
            <router-link to="/dashboard/admin/rh/absences" exact>
                <span class="link-title">Absences</span>
                <i class="mdi mdi-alarm-off link-icon"></i>
            </router-link>
        </li>
        <li>
            <router-link to="/dashboard/admin/rh/permissions" exact>
                <span class="link-title">Permissions</span>
                <i class="mdi mdi-account-key link-icon"></i>
            </router-link>
        </li>
        <li>
            <router-link to="/dashboard/admin/rh/heures-supplementaires" exact>
                <span class="link-title">Heures Supplementaires</span>
                <i class="mdi mdi-alarm-plus link-icon"></i>
            </router-link>
        </li>
        <li>
            <router-link to="/dashboard/admin/rh/formation-des-employes" exact>
                <span class="link-title">Formations des employés</span>
                <i class="mdi mdi-speedometer link-icon"></i>
            </router-link>
        </li>
        <li>
            <router-link to="/dashboard/admin/rh/demandes-d-emploi" exact>
                <span class="link-title">Demandes d'emploi</span>
                <i class="mdi mdi-briefcase-check link-icon"></i>
            </router-link>
        </li>
        <li>
            <router-link to="/dashboard/admin/rh/missions-employes" exact>
                <span class="link-title">Missions</span>
                <i class="mdi mdi-airplane link-icon"></i>
            </router-link>
        </li>
        <li>
            <router-link to="#" exact>
                <span class="link-title">Congés</span>
                <i class="mdi mdi-alarm-snooze link-icon"></i>
            </router-link>
        </li>
        <li>
            <router-link to="#" exact>
                <span class="link-title">Démissions</span>
                <i class="mdi mdi-transit-transfer link-icon"></i>
            </router-link>
        </li>
    </ul>
</template>

<script>
    export default {
        name: "RhLinks"
    }
</script>

<style scoped>

</style>
