import { ref, computed } from "vue";
import { defineStore } from "pinia";
import { api } from "@/services/api";
import { useToast } from "vue-toastification";
import { useUiStore } from "./ui";




export const useDepensesStore = defineStore("depenses", () => {

    // UX
    const ui = useUiStore()
    const toast = useToast()



    // depenses
    const items = ref(null)
    const today_depenses = ref(null)
    const thisMonth = ref(null)
    const total_today = ref(null)
    const total_by_month_and_category = ref(null)
    const total_annuel_par_categorie = ref(null)
    const chartMonthlyComparisonData = ref(null)

    
    // historique des salaires d'un employé particulier
    const historique_salaire = ref(null)


    // evolution dépenses 7 derniers jours
    const evolution_derniers_jours = ref(null)



    // historique des salaires d'un employé particulier
    const fetchHistoriqueSalaire = async (employe_id) => {

        try {

            ui.globalLoading = true
            const response = await api.get(`/depense/historique-salaire/${employe_id}`)
            historique_salaire.value = response.data
            console.log ("Historique salaire : ", historique_salaire.value)

        }catch (exception) {

            toast.error("Impossible de récupérer l'historique des salaires")
            console.error("Erreur : ", exception)

        }finally {

            ui.globalLoading = false

        }

    }


    // récupérer les versements d'ajourd'hui
    const fetchTodaysDepenses = async () => {

        try {

            ui.globalLoading = true
            const response = await api.get("/depense/todaysDepenses")
            today_depenses.value = response.data

        }catch(exception) {

            toast.error("Une erreur est survenue")
            console.log ("Exception")

        }finally {

            ui.globalLoading = false

        }

    }



    // récupérer le total des dépenses de jour
    const fetchToday = async () => {

        try {

            const response = await api.get("/depense/today")
            total_today.value = response.data

        }catch (exception) {

            toast.error ("Une erreur est survenue")
            console.log ("Erreur : ", exception)

        }
        
    }


    // récupérer les dépenses du mois courant
    const fetchThisMonth = async () => {

        try {

            ui.globalLoading = true
            const response = await api.get("/depense/thisMonth")
            thisMonth.value = response.data
            console.log ("Dépenses du mois : ", thisMonth.value)

        }catch (exception) {

            toast.error ("Une erreur est survenue")
            console.log ("Erreur : ", exception)

        }finally {  
            ui.globalLoading = false
        }

    }


    // dépenses par mois et par catégorie
    const fetchTotalByCategoryAndMonth  = async (year, month) => {

        try {

            ui.globalLoading = true
            const response = await api.get (`/depense/stats/byCategory/${year}/${month}`)
            total_by_month_and_category.value = response.data

        }catch (exception) {

            toast.error ("Une erreur est survenue")
            console.log ("Impossible de récupérer les dépenses par mois et par categorie : ", exception)
        
        }finally {

            ui.globalLoading = false

        }
        
    }


    // total par catégorie pour l'année
    const fetchTotalAnnuelParCategorie = async () => {

        try {

            ui.globalLoading = true
            const response = await api.get(`/depense/total-annuel/`)
            total_annuel_par_categorie.value = response.data

        }catch (exception) {

            toast.error("Impossible de récupérer le total annuel par catégorie")
            console.error("Erreur : ", exception)

        }finally {

            ui.globalLoading = false

        }

    }


    // données pour le graphique de comparaison entre l'année et n-1
    const fetchMonthlyComparison = async () => {

        try {

            const response = await api.get("/depense/monthly-comparison")
            chartMonthlyComparisonData.value = response.data

        }catch (exception) {

            toast.error("Impossible de faire ce graphique")
            console.error ("Erreur : ", exception)

        }

    }



    // evolution des depenses sur les 7 derniers jours
    const fetchEvolutionDerniersJours = async () => {

        try {

            const response = await api.get("/depense/evolution/derniers-jours")
            evolution_derniers_jours.value = response.data

        }catch (exception) {

            toast.error("Erreur inattendue")
            console.error("Impossible de récupére l'évolution sur les 7 derniers jours : ", exception)

        }
        
    }




    // faire une dépense
    const depenser = async (formData) => {

        try {

            ui.globalLoading = true
            const response = api.post("/depense/create", formData)
            toast.success ("Effectué avec succès")

        }catch (exception) {

            toast.error ("Une erreur est survenue")
            console.log ("Erreur lors de la dépense : ", exception)

        }finally {

            ui.globalLoading = false

        }


    }


    // on retourne 
    return {

        items,
        historique_salaire,
        thisMonth,
        total_today,
        today_depenses,
        chartMonthlyComparisonData,
        total_by_month_and_category,
        evolution_derniers_jours,
        total_annuel_par_categorie,
        fetchHistoriqueSalaire,
        fetchTotalAnnuelParCategorie,
        fetchTotalByCategoryAndMonth,
        fetchMonthlyComparison,
        fetchTodaysDepenses,
        fetchToday,
        fetchThisMonth,
        fetchEvolutionDerniersJours,
        depenser

    }


})  