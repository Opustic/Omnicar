import { defineStore } from "pinia";
import { computed, ref } from "vue";
import { api } from "@/services/api";
import { useToast } from "vue-toastification";
import { useUiStore } from "./ui";




export const useVersementsStore = defineStore("versements", ()=> {


    // store pour UI
    const toast = useToast()
    const ui  = useUiStore()



    // tous les versements du vehicule cible
    const items = ref(null)


    // total versements du jour
    const today = ref(null)


    // versements du jour
    const todays_versements = ref(null)

    const total_versements_par_chauffeur = ref(null)
    const total_versements_par_controleur = ref(null)
    const total_versements_par_vehicule = ref(null)


    // top chauffeurs, controleurs et vehicules
    const topchauffeurs = ref(null)
    const topcontroleurs = ref(null)
    const topvehicules = ref(null)


    // derniers vehicules
    const top_last_chauffeurs = ref(null)
    const top_last_controleurs = ref(null)
    const top_last_vehicules = ref(null)



    // totaux mensuels
    const totaux_mensuels = ref(null)


    // totaux cumulés
    const totaux_cumules = ref(null)


    // comparaison objectifs
    const comparaison_objectifs = ref(null)


    // statistiques par bus
    const stats_par_vehicule = ref(null)


    // evolution annuelle des versements
    const evolution = ref(null)


    // evolution des versements sur les derniers jours
    const evolution_derniers_jours = ref(null)



    // chiffre d'affaire du mois actuel d'un bus
    const ca_du_mois = ref(null)


    // total des versements d'un chauffeur particulier  (mois courant)
    const total_versements_par_chauffeur_mois_courant = ref(null)



    // total des versements d'un controleur particulier  (mois courant)
    const total_versements_par_controleur_mois_courant = ref(null)


    // historique des versements d'un chauffeur
    const historique_chauffeur = ref(null)



    // historique des versements d'un controleur
    const historique_controleur = ref(null)


    // evolution des versements d'un chauffeur
    const evolution_versements_chauffeur = ref(null)


    // evolution des versements d'un controleur
    const evolution_versements_controleur = ref (null)


    // totaux mensuel
    const totaux_mensuels_vehicule = ref(null)

    // cumulés mensuels pour un vehicule particulier
    const totaux_cumules_vehicule = ref(null)


    // comparaison objectif vs realisation
    const comparaison_objectifs_vehicule = ref(null)


    // total des versements des chauffueurs today
    const total_versements_chauffeurs_today = ref(null)

    // total des versements des controleurs today
    const total_versements_controleurs_today = ref(null)

    // total des versements des chauffeurs this week
    const total_versements_chauffeurs_this_week = ref(null)

    // total des versements des controleurs this week
    const total_versements_controleurs_this_week = ref(null)

    // total des versements des chauffeurs this month
    const total_versements_chauffeurs_this_month = ref(null)

    // total des versements des controleurs this month
    const total_versements_controleurs_this_month = ref(null)


    // récupérer les versements d'un vehicule
    const fetchAll = async (id) => {


        try {

            ui.globalLoading = true
            const response = await api.get(`/versement/${id}/getVersementForVehicle`)
            items.value = response.data

        }catch (exception) {

            toast.error ("Impossible de récupérer les versements de ce véhicule")
            console.log ("Impossible de récupérer les versements de ce véhicule : ", exception)


        } finally {

            ui.globalLoading =  false

        }

    }


    // comparaison vs objectifs d'un vehicule
    const fetchComparaisonObjectifsVehicule = async (vehicule_id) => {


        try {

            const response = await api.get(`/versement/vehicule/${vehicule_id}/objectifs`)
            comparaison_objectifs_vehicule.value = response.data

        }catch (exception) {

            console.error("Erreur lors de la récupération de la comparaison avec lec objectifs de ce vehicule : ", exception)
            toast.error("Erreur lors de la récupération de la comparaison avec lec objectifs de ce vehicule")


        }

    }


    // récupérer les totaux mensuels pour un vehicule particulier
    const fetchTotauxMensuelsVehicule = async (id) => {

        try {

            const response = await api.get(`versement/vehicule/${id}/totaux-mensuels`)
            totaux_mensuels_vehicule.value = response.data

        }catch (exception) {

            console.error ("Erreur lors de la récupération des totaux mensuels du vehicules : ", exception)
            toast.error ("Erreur lors de la récupération des totaux mensuels du vehicules")


        }

    }


    // récupérer les cumulés mensuels pour un vehicule particulier
    const fetchCumuleVehicule = async (id) => {

        try {

            const response = await api.get(`/versement/vehicule/${id}/totaux-cumules`)
            totaux_cumules_vehicule.value = response.data

        }catch (exception) {

            console.error ("Erreur lors de la récupération des totaux cumulés du vehicules : ", exception)
            toast.error ("Erreur lors de la récupération des totaux cumulés du vehicules")


        }

    }


    // récupérer le total des versements (mois courant) pour un chauffeur
    const fetchTotalVersementsParChauffeurMoisCourant = async (chauffeur_id) => {

        try {

            const response = await api.get (`/versement/${chauffeur_id}/total-versement-chauffeur`)
            total_versements_par_chauffeur_mois_courant.value = response.data

        }catch(exception) {

            console.error ("Erreur lors de la récupération des versements totaux du mois d'un chauffeur : ", exception)
            toast.error ("Erreur lors de la récupération des versements totaux du mois d'un chauffeur")

        }

    }



    // récupérer le total des versements (mois courant) pour un controleur
    const fetchTotalVersementsParControleurMoisCourant = async (controleur_id) => {

        try {

            const response = await api.get (`/versement/${controleur_id}/total-versement-controleur`)
            total_versements_par_controleur_mois_courant.value = response.data

        }catch(exception) {

            console.error ("Erreur lors de la récupération des versements totaux du mois d'un controleur : ", exception)
            toast.error ("Erreur lors de la récupération des versements totaux du mois d'un controleur")
            
        }

    }

    
    // historique des versements des chauffeurs
    const fetchHistoriqueChauffeur = async (chauffeur_id) => {


        try {

            ui.globalLoading = true
            const response = await api.get(`/versement/${chauffeur_id}/historique-chauffeur`)
            historique_chauffeur.value = response.data

        }catch (exception) {

            toast.error ("Impossible de récupérer l'historique du chauffeur")
            console.error ("Impossible de récupérer l'historique du chauffeur : ", exception)

        }finally {

            ui.globalLoading = false

        }

    }


    // historique d'un controleur
    const fetchHistoriqueControleur = async (controleur_id) => {

        try {

            ui.globalLoading = true
            const response = await api.get(`/versement/${controleur_id}/historique-controleur`)
            historique_controleur.value = response.data

        }catch (exception) {

            toast.error ("Impossible de récupérer l'historique du controleur")
            console.error ("Impossible de récupérer l'historique du controleur : ", exception)

        }finally {

            ui.globalLoading = false

        }

    }
    


    // evolution des versements d'un chauffeur
    const fetchEvolutionVersementsChauffeur = async (chauffeur_id)=> {

        try {

            const response = await api.get(`/versement/${chauffeur_id}/evolution-chauffeur`)
            evolution_versements_chauffeur.value = response.data

        }catch (exception) {

            toast.error("Erreur lors de la récupération de l'évolution")
            console.error("Erreur lors de la récupération de l'évolution : ", exception)

        }

    }


    // evolution des versements d'un controleur
    const fetchEvolutionVersementsControleur = async (controleur_id) => {

        try {

            const response =  await api.get(`/versement/${controleur_id}/evolution-controleur`)
            evolution_versements_controleur.value = response.data

        } catch (exception) {

            toast.error("Erreur lors de la récupération de l'évolution")
            console.error("Erreur lors de la récupération de l'évolution : ", exception)

        }

    }


    // faire un versement
    const verser = async (formData, vehicule_id, chauffeur_id, controleur_id) =>  {

        try {

            ui.globalLoading = true
            const response = await api.post (`/versement/${vehicule_id}/${chauffeur_id}/${controleur_id}/insert`, formData)
            toast.success ("Versement effectué avec succès")

        }catch (exception) {

            toast.error ("Une erreur est survenue")
            console.log ("Impossible de faire ce versement car : ", exception)

        }finally {

            ui.globalLoading = false

        }

    }



    // récupérer tous les versements today
    const fetchTodaysVersements = async () => {

        try {

            const response = await api.get("versement/todays-versements")
            todays_versements.value = response.data
            
        }catch (exception) {

            toast.error("Impossible de récupérer les versements du jour")
            console.error("Impossible de récupérer les versements du jour : ", exception)


        }

    }

    

    // versements today
    const fetchToday = async () => {

        try {

            const response = await api.get("versement/today")
            today.value = response.data

        }catch (exception) {
            
            toast.error ("Impossible de récupérer le total des versements du jour")
            console.log ("Une erreur est survenue lors de la récupération des versements du jour : ", exception)

        }

    }


    // récupérer le total des versements du jour par chauffeur
    const fetchTotalVersementsChauffeursToday = async () => {

        try {

            const response = await api.get("/versement/total-journalier-chauffeurs")
            total_versements_chauffeurs_today.value = response.data

        }catch (exception) {

            toast.error ("Une erreur est survenue")
            console.error ("Erreur lors de la récupération du total des versements des chauffeurs today : ", exception)

        }

    }

    // récupérer le total des versements du jour par controleur
    const fetchTotalVersementsControleursToday = async () => {

        try {

            const response = await api.get("/versement/total-journalier-controleurs")
            total_versements_controleurs_today.value = response.data

        }catch (exception) {

            toast.error ("Une erreur est survenue")
            console.error ("Erreur lors de la récupération du total des versements des controleurs today : ", exception)

        }

    }

    // récupérer le total des versements de la semaine par chauffeur
    const fetchTotalVersementsChauffeursThisWeek = async () => {

        try {

            const response = await api.get("/versement/total-semaine-chauffeurs")
            total_versements_chauffeurs_this_week.value = response.data

        }catch (exception) {

            toast.error ("Une erreur est survenue")
            console.error ("Erreur lors de la récupération du total des versements des chauffeurs this week : ", exception)

        }

    }

    // récupérer le total des versements de la semaine par controleur
    const fetchTotalVersementsControleursThisWeek = async () => {

        try {

            const response = await api.get("/versement/total-semaine-controleurs")
            total_versements_controleurs_this_week.value = response.data

        }catch (exception) {

            toast.error ("Une erreur est survenue")
            console.error ("Erreur lors de la récupération du total des versements des controleurs this week : ", exception)

        }

    }   

    // récupérer le total des versements du mois par chauffeur          
    const fetchTotalVersementsChauffeursThisMonth = async () => {
        try {

            const response = await api.get("/versement/total-mensuel-chauffeurs")
            total_versements_chauffeurs_this_month.value = response.data

        }catch(exception) {

            toast.error ("Une erreur est survenue")
            console.error ("Erreur lors de la récupération du total des versements des chauffeurs this month : ", exception)

        }
    }

    // récupérer le total des versements du mois par controleur
    const fetchTotalVersementsControleursThisMonth = async () => {

        try {

            const response = await api.get("/versement/total-mensuel-controleurs")
            total_versements_controleurs_this_month.value = response.data

        }catch (exception) {

            toast.error ("Une erreur est survenue")
            console.error ("Erreur lors de la récupération du total des versements des controleurs this month : ", exception)

        }

    }

    // récupérer le top 3 des chauffeurs
    const fetchTopChauffeurs = async () => {
        // 
        try {

            const response = await api.get("/versement/topchauffeurs")
            topchauffeurs.value = response.data

        } catch (exception) {

            toast.error("Erreur inattendue lors de la récupération du top 3")
            console.log ("Erreur lors de la récupération du top 3 des chauffeurs : ", exception)

        }
    }


    // les derniers chauffeurs
    const fetchTopLastChauffeurs = async () => {

        try {

            const response = await api.get("/versement/derniers-chauffeurs")
            top_last_chauffeurs.value = response.data

        }catch (exception) {

            console.error ("Erreur lors de la récupération des derniers chauffeurs : ", exception)
            toast.error ("Erreur lors de la récupération des derniers chauffeurs")

        }

        
    }

    // récupérer le top 3 des controleurs
    const fetchTopControleurs = async () => {
        // 
        try {

            const response = await api.get("/versement/topcontroleurs")
            topcontroleurs.value = response.data

        }catch (exception) {

            toast.error ("Erreur lors de la récupération du top 3 des chauffeurs")    
            console.log ("Erreur lors de la récupération du top 3 des chauffeurs : ", exception)
        
        }
    }

    // les derniers controleurs
    const fetchTopLastControleurs = async () => {

        try {

            const response = await api.get ("/versement/derniers-controleurs")
            top_last_controleurs.value = response.data

        }catch (exception) {

            toast.error ("Erreur lors de la récupération du top 3 des controleurs")    
            console.log ("Erreur lors de la récupération du top 3 des controleurs : ", exception)
        
        }
    }



    // récupérer le top des vehicules
    const fetchTopVehicules = async () => {

        try {

            const response = await api.get("/versement/top-vehicules")
            topvehicules.value = response.data

        }catch (exception) {

            console.error ("Erreur survenue lors de la récupération du top des vehicules : ", exception)
            toast.error ("Erreur survenue lors de la récupération du top des vehicules")

        }

    }


    // derniers vehicules
    const fetchTopLastVehicules = async () => {

        try {

            const response = await api.get("/versement/derniers-vehicules")
            top_last_vehicules.value = response.data

        }catch (exception) {

            console.error("Erreur lors de la récupération des derniers vehicules : ", exception)
            toast.error ("Erreur lors de la récupération des derniers vehicules")

        }

    }



    // total des versements par chauffeur (en ordre décroissant)
    const fetchTotalVersementParChauffeur = async () => {
        // 
        try { 

            ui.globalLoading = true
            const response = await api.get("/versement/totalVersementsParChauffeur")
            total_versements_par_chauffeur.value = response.data

        }catch (exception) {

            toast.error ("Une erreur est survenue")
            console.log ("Erreur lors de la récupération du total de versements par chauffeur : ", exception)
            
        }finally {

            ui.globalLoading = false

        }
    }


    // total des versements par controleur (en ordre décroissant)
    const fetchTotalVersementParControleur = async () => {
        // 

        try {

            ui.globalLoading = true
            const response = await api.get ("/versement/totalVersementsParControleur")
            total_versements_par_controleur.value = response.data

        }catch (exception) {

            toast.error ("Une erreur est survenue")
            console.log ("Une erreur est survenue lors de la récupération des versements par controleur : ", exception)

        }finally {

            ui.globalLoading = false

        }
    }


    // total des versements par vehicule (en ordre décroissant)
    const fetchTotalVersementParVehicule = async () => {
        // 
        try {

            ui.globalLoading = true
            const response = await api.get ("/versement/totalVersementsParVehicule")
            total_versements_par_vehicule.value = response.data

        }catch (exception) {

            toast.error ("Une erreur est survenue")
            console.log ("Une erreur est survenue lors de la récupération des versements par vehicule : ", exception)

        }finally {

            ui.globalLoading = false

        }
    }


    // récupérer les totaux mensuels des versements
    const fetchTotauxVersementsMensuels = async () => {

        try {

            ui.globalLoading = true
            const response = await api.get("/versement/totaux-mensuels")
            totaux_mensuels.value = response.data

        }catch (exception) {

            toast.error("Une erreur est survenue")
            console.error("Erreur lors de la récupération des totaux des versement par mois : ", exception)

        }finally {

            ui.globalLoading = false

        }

    }


    // récupérer les totaux cumulés mensuels
    const fetchTotauxCumules = async () => {

        try {

            ui.globalLoading = true
            const response = await api.get("/versement/totaux-cumules")
            totaux_cumules.value = response.data

        }catch (exception) {

            toast.error ("Une erreur est survenue")
            console.error("Erreur : ", exception)

        }finally {

            ui.globalLoading = false

        }


    }


    // comparaison temporelle
    const fetchComparaisonObjectifs = async () => {

        try {

            ui.globalLoading = true
            const response = await api.get("/versement/objectifs")
            comparaison_objectifs.value = response.data

        }catch (exception) {

            toast.error ("Une erreur est survenue")
            console.error ("Erreur lors de la récupération des objectifs : ", exception)

        }finally {

            ui.globalLoading = false

        }

    }


    // statistiques par vehicule
    const fetchStatsParVehicule = async () => {

        try {

            const response = await api.get("/versement/stats-par-vehicule")
            stats_par_vehicule.value = response.data

        }catch (exception) {

            toast.error ("Impossible de récupérer les stats des vehicules")
            console.error("Impossible de récupérer les stats des vehicules : ", exception)

        }

    }



    // evolution des versements
    const fetchEvolutionVersements = async () => {

        try {

            const response = await api.get("/versement/evolution")
            evolution.value = response.data

        }catch (exception) {

            toast.error("Impossible de récupérer l'évolution des versements")
            console.error("Erreur lors de la récupération de l'évolution des versements : ", exception)

        }

    }



    // evolution des versements sur les derniers jours
    const fetchEvolutionDerniersJours = async () => {

        try {

            const response = await api.get ("/versement/evolution-derniers-jours")
            evolution_derniers_jours.value = response.data

        }catch(exception) {

            toast.error ("Erreur inattendue")
            console.error ("Erreur lors de la récupération de l'évolution des 7 derniers jours des versements : ", exception)

        }
        
    }



    // récupérer le chiffre d'affaire du mois
    const fetchTotalVersement = async (vehicule_id) => {

        try {

            const response = await api.get(`/versement/${vehicule_id}/total-versement`)
            ca_du_mois.value = response.data
            
        }catch (exception) {

            console.error("Erreur lors de la récupération du total du mois : ", exception)
            toast.error("Erreur lors de la récupération du total du mois")

        }

    }





    return {

        items, //versements d'un vehicule particulier
        today,  //somme des versements effectués aujourd'hui



        // chiffre d'affaire du mois
        ca_du_mois,


        todays_versements, //tous les versements du jour
        total_versements_par_chauffeur,
        total_versements_par_controleur,
        total_versements_par_vehicule,


        total_versements_par_chauffeur_mois_courant,
        total_versements_par_controleur_mois_courant,
        fetchTotalVersementsParChauffeurMoisCourant,
        fetchTotalVersementsParControleurMoisCourant,


        fetchTotalVersementsChauffeursToday,
        fetchTotalVersementsControleursToday,
        fetchTotalVersementsChauffeursThisWeek,
        fetchTotalVersementsControleursThisWeek,
        fetchTotalVersementsChauffeursThisMonth,
        fetchTotalVersementsControleursThisMonth,
        total_versements_chauffeurs_today,
        total_versements_controleurs_today,
        total_versements_chauffeurs_this_week,
        total_versements_controleurs_this_week,
        total_versements_chauffeurs_this_month,
        total_versements_controleurs_this_month,
        

        // top des chauffeurs, controleurs, et vehicules
        topchauffeurs,
        topcontroleurs,
        topvehicules, 
        top_last_chauffeurs,
        top_last_controleurs,
        top_last_vehicules,




        totaux_mensuels,
        totaux_cumules,
        comparaison_objectifs,
        stats_par_vehicule,


        // evolution
        evolution,
        evolution_derniers_jours,


        // evolution des chauffeur, controleur
        evolution_versements_chauffeur,
        evolution_versements_controleur,


        // historique
        historique_chauffeur,
        historique_controleur,



        // tout récupérer
        fetchAll,



        // comparaison des objectifs des vehicules
        comparaison_objectifs_vehicule,
        fetchComparaisonObjectifsVehicule,


        // récupérer l'historique du chauffeur et celui du controleur
        fetchHistoriqueChauffeur,
        fetchHistoriqueControleur,



        // récupérer les évolutions
        fetchEvolutionVersementsChauffeur,
        fetchEvolutionVersementsControleur,


        // récupérer le chiffre d'affaire du mois
        fetchTotalVersement,

        
        // versements du jour et total des versements du jour
        fetchToday,
        fetchTodaysVersements,
        fetchTotalVersementParChauffeur,
        fetchTotalVersementParControleur,
        fetchTotalVersementParVehicule,


        // top des chauffeurs, des controleurs et des vehicules
        fetchTopChauffeurs,
        fetchTopControleurs,
        fetchTopVehicules,

        fetchTopLastChauffeurs,
        fetchTopLastControleurs,
        fetchTopLastVehicules,



        fetchTotauxVersementsMensuels,
        fetchTotauxCumules,
        fetchComparaisonObjectifs,
        fetchStatsParVehicule,
        fetchEvolutionVersements,
        fetchEvolutionDerniersJours,



        // suivi de performances d'un vehicule
        totaux_mensuels_vehicule,
        totaux_cumules_vehicule,
        fetchTotauxMensuelsVehicule,
        fetchCumuleVehicule,




        // faire un versement
        verser


    }



})