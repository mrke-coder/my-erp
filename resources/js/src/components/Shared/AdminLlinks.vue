<template>
    <ul class="navigation-menu">
        <li class="nav-category-divider">MENU PRINCIPAL</li>
        <li>
            <router-link to="/dashboard/admin" exact>
                <span class="link-title">Tableau de bord</span>
                <i class="mdi mdi-gauge link-icon"></i>
            </router-link>
        </li>
        <li>
            <router-link to="/dashboard/admin/rh" exact>
                <span class="link-title">Ressources Humaines</span>
                <i class="mdi mdi-certificate link-icon"></i>
            </router-link>
        </li>

        <li>
            <router-link to="#" exact>
                <span class="link-title">Gestion Commerciale</span>
                <i class="mdi mdi-network link-icon"></i>
            </router-link>
        </li>

        <li>
            <router-link to="#" exact>
                <span class="link-title">Gestion Des Stock</span>
                <i class="mdi mdi-network link-icon"></i>
            </router-link>
        </li>

        <li>
            <router-link to="#" exact>
                <span class="link-title">Comptabilité</span>
                <i class="mdi mdi-network link-icon"></i>
            </router-link>
        </li>

        <li class="nav-category-divider">ADMINISTRATION</li>
        <li>
            <router-link to="/dashboard/admin/utilisateurs" exact>
                <span class="link-title">Utilisateurs</span>
                <i class="fa fa-users link-icon"></i>
            </router-link>
        </li>
        <li>
            <router-link to="#" exact>
                <span class="link-title">Habilitations</span>
                <i class="mdi mdi-account-key link-icon"></i>
            </router-link>
        </li>
    </ul>
</template>

<script>
    export default {
        name: "admin_links"
    }
</script>

<style scoped>

</style>
