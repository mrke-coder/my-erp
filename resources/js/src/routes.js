import * as auth from "./services/authService";
import Login from "./components/pages/auth/Login";
import Dashboard from "./components/pages/Dashboard"
import Admin from "./components/pages/Dashboard/Admin";
import Admin_Home from "./components/pages/Dashboard/Admin/Home";
import Users from "./components/pages/Dashboard/Admin/Users";

//  RH LINKS
import Rh from "./components/pages/Dashboard/Rh"
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
import Departure from "./components/pages/Dashboard/Rh/Departure";

// END RH LINKS

import Profile from './components/pages/Dashboard/Profile';
import Account from "./components/pages/Dashboard/Profile/Account";
import Settings from './components/pages/Dashboard/Profile/Settings';
import Helps from './components/pages/Dashboard/Profile/Helps';

export default {
    mode: 'history',
    routes: [
        {
            path: '/:session?',
            name: 'Authentification',
            component: Login,
             beforeEnter(to, from, next) {
                 if (!auth.isLoggedIn()) {
                     next();
                 } else {
                     if (auth.whoIsConnected('admin')){
                         next(`/dashboard/admin/${auth.hashUrlParams(auth.someOneConnectedData().id)}`);
                     } else if (auth.whoIsConnected('Rh')){
                         next(`/dashboard/rh/${auth.hashUrlParams(auth.someOneConnectedData().id)}/rh`);
                         //console.log(auth.someOneConnectedData())
                     }
                 }
             }
        },
        {
           path: '/dashboard/:role/:id/',
           name: 'Dashboard',
           component: Dashboard,
            children:[
                {
                    path:'/',
                    name: 'Admin',
                    component: Admin,
                    children: [
                        {
                            path:'/',
                            name: "AdminHome",
                            component: Admin_Home
                        },
                        {
                            path:'/utilisateurs',
                            name:'Users_Home',
                            component: Users
                        },
                    ]
                },
                {
                    path:'rh/',
                    name: 'RH',
                    component: Rh,
                    children:[
                        {
                            path:'/',
                            name: 'Home',
                            component: Home
                        },
                        {
                            path:'departements',
                            name:'Departement',
                            component: Departement
                        },
                        {
                            path:'specialites',
                            name: 'Speciality',
                            component: Speciality
                        },
                        {
                            path:'employes',
                            name: 'Employee',
                            component: Employee
                        },
                        {
                            path: 'employes/:id/informations/:page',
                            name: 'EmployDetailsPage',
                            component: EmployeeInfos
                        },
                        {
                            path: "salaires",
                            name: "Wages",
                            component: Wages
                        },
                        {
                            path: 'primes',
                            name: 'PrimesEmployees',
                            component: PrimesEmployees
                        },
                        {
                            path: 'absences',
                            name: 'Absence',
                            component: Absence
                        },
                        {
                            path: 'permissions',
                            name: 'Permission',
                            component: Permission
                        },
                        {
                            path: 'heures-supplementaires',
                            name: 'Additional-Hour',
                            component: AdditionalHour
                        },
                        {
                            path: "formation-des-employes",
                            name: "Training",
                            component: Training
                        },
                        {
                            path: "demandes-d-emploi",
                            name: "Job",
                            component: JobApplication
                        },
                        {
                            path: "missions-employes",
                            name: "Mission",
                            component: Mission
                        },
                        {
                            path: "demissions-employes",
                            name: "Departure",
                            component: Departure
                        }
                    ]
                },
                {
                    path: '/dashboard/profile/:user/:id/profil',
                    name: 'Profile',
                    component: Profile,
                    children: [
                        {
                            path: '/dashboard/profile/:user/:id/profil',
                            name: 'Account',
                            component: Account
                        },
                        {
                            path: '/dashboard/profile/:user/:id/parametres',
                            name: 'Settings',
                            component: Settings
                        },
                        {
                            path: '/dashboard/profile/:user/:id/aides',
                            name: 'Helps',
                            component: Helps
                        }
                    ]
                }

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
