import * as auth from "./services/authService";
import Login from "./components/pages/auth/Login";
import Dashboard from "./components/pages/Dashboard"
import Admin from "./components/pages/Dashboard/Admin";
import Admin_Home from "./components/pages/Dashboard/Admin/Home";
import Users from "./components/pages/Dashboard/Admin/Users";
import Rh from "./components/pages/Dashboard/RH"
import Home from "./components/pages/Dashboard/Rh/Home";
import Departement from "./components/pages/Dashboard/Rh/Departement"
import Speciality from "./components/pages/Dashboard/Rh/Speciality";
import Employee from "./components/pages/Dashboard/Rh/Employee";
import EmployeeInfos from "./components/pages/Dashboard/Rh/EmployeeInfos";
import Wages from "./components/pages/Dashboard/Rh/Wages";
import PrimesEmployees from "./components/pages/Dashboard/Rh/PrimesEmployees";
import Absence from "./components/pages/Dashboard/Rh/Absence";
import Permission from "./components/pages/Dashboard/Rh/Permission.vue";
import AdditionalHour from "./components/pages/Dashboard/Rh/AdditionalHour";
import Training from "./components/pages/Dashboard/Rh/Training";
import JobApplication from "./components/pages/Dashboard/Rh/JobApplication";
import Mission from "./components/pages/Dashboard/Rh/Mission";
export default {
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'Authentification',
            component: Login,
             beforeEnter(to, from, next) {
                 if (!auth.isLoggedIn()) {
                     next();
                 } else {
                     next('/dashboard/admin')
                 }
             }
        },
        {
           path: '/dashboard',
           name: 'Dashboard',
           component: Dashboard,
            children:[
                {
                    path:'/dashboard/admin',
                    name: 'Admin',
                    component: Admin,
                    children: [
                        {
                            path:'/dashboard/admin',
                            name: "Home",
                            component: Admin_Home
                        },
                        {
                            path:'/dashboard/admin/utilisateurs',
                            name:'Users_Home',
                            component: Users
                        },
                        {
                            path:'/dashboard/admin/rh',
                            name: 'RH',
                            component: Rh,
                            children:[
                                {
                                    path:'/dashboard/admin/rh',
                                    name: 'Home',
                                    component: Home
                                },
                                {
                                  path:'/dashboard/admin/rh/departements',
                                  name:'Departement',
                                  component: Departement
                                },
                                {
                                  path:'/dashboard/admin/rh/specialites',
                                  name: 'Speciality',
                                  component: Speciality
                                },
                                {
                                    path:'/dashboard/admin/rh/employes',
                                    name: 'Employee',
                                    component: Employee
                                },
                                {
                                    path:'/dashboard/admin/rh/employes/:id/informations',
                                    name: 'EmployDetails',
                                    component: EmployeeInfos
                                },
                                {
                                    path: "/dashboard/admin/rh/salaires",
                                    name: "Wages",
                                    component: Wages
                                },
                                {
                                    path: '/dashboard/admin/rh/primes',
                                    name: 'PrimesEmployees',
                                    component: PrimesEmployees
                                },
                                {
                                    path: '/dashboard/admin/rh/absences',
                                    name: 'Absence',
                                    component: Absence
                                },
                                {
                                    path: '/dashboard/admin/rh/permissions',
                                    name: 'Permission',
                                    component: Permission
                                },
                                {
                                    path: '/dashboard/admin/rh/heures-supplementaires',
                                    name: 'Additional-Hour',
                                    component: AdditionalHour
                                },
                                {
                                    path: "/dashboard/admin/rh/formation-des-employes",
                                    name: "Training",
                                    component: Training
                                },
                                {
                                    path: "/dashboard/admin/rh/demandes-d-emploi",
                                    name: "Job",
                                    component: JobApplication
                                },
                                {
                                    path: "/dashboard/admin/rh/missions-employes",
                                    name: "Mission",
                                    component: Mission
                                }
                            ]
                        }
                    ]
                },

            ],
            beforeEnter(to, from, next){
               if(!auth.isLoggedIn()){
                   next('/')
               } else {
                   next()
               }
            }
        },
        {
            path: '*',
            redirect: '/'
        }

    ]
}
