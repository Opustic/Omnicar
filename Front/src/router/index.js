// importation des librairies
import BestsControleurs from "@/pages/BestsControleurs.vue";
import BestsChauffeurs from "@/pages/BestsChauffeurs.vue";
import BusInfos from "@/pages/BusInfos.vue";
import Employes from "@/pages/Employes.vue";
import Historique from "@/pages/Historique.vue";
import Menu from "@/pages/Menu.vue";
import MesBus from "@/pages/MesBus.vue";
import TopTeam from "@/pages/TopTeam.vue";
import VueGlobale from "@/pages/VueGlobale.vue";
import { createRouter, createWebHistory } from "vue-router";
import Parametres from "@/pages/Parametres.vue";
import Login from "@/pages/Login.vue";
import Notifications from "@/pages/Notifications.vue";
import Depenses from "@/pages/Depenses.vue";
import Recettes from "@/pages/Recettes.vue";
import BestVehicules from "@/pages/BestsVehicules.vue";



// définition du router 
const router = createRouter({
    history : createWebHistory(),
    routes : [
        {
            // Route d'entrée par défaut
            path : "/",
            component : Login
        },
        {
            // Route d'entrée par défaut
            path : "/menu",
            component : Menu
        },
        {
            // Route pour le menu
            path : "/meilleurschauffeurs",
            component : BestsChauffeurs
        },
        {
            // Route pour le menu
            path : "/meilleurscontroleurs",
            component : BestsControleurs
        },
        {
            // Route pour le menu
            path : "/meilleursvehicules",
            component : BestVehicules
        },
        {
            // Route pour le menu
            path : "/meilleuresequipes",
            component : TopTeam
        },
        {
            // Route pour les bus
            path : "/mesbus",
            component: MesBus
        },
        {
            // Route pour les informations spécialisées du bus
            name : "businfos",
            path : "/mesbus/:id",
            component: BusInfos,
            props:true
        },
        {
            // Route pour voir l'historique des bus
            name:"historique",
            path : "/historique/:id",
            component: Historique,
            props:true
        },
        {
            // Route de la vue globale
            path : "/vueglobale",
            component: VueGlobale
        },
        {
            // Route de la vue globale
            path : "/depenses",
            component: Depenses
        },
        {
            // Route de la vue globale
            path : "/recettes",
            component: Recettes
        },
        {
            // Route de la réparation
            path : "/employes",
            component: Employes
        },
        {
            // Route pour la section paramètres
            path : "/parametres",
            component: Parametres
        },
        {
            // Route pour les notifications
            path : "/notifications",
            component: Notifications
        }
    ]
})



export default router;