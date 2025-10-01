import { defineStore } from "pinia";
import { computed, ref } from "vue";
import { api } from "@/services/api";
import { useToast } from "vue-toastification";
import { useUiStore } from "./ui";




export const useReparationsStore = defineStore("reparations", ()=> {

    // 

    const toast = useToast()
    const ui = useUiStore()


    const items = ref(null)


    // total par motif
    const total_par_motif = ref(null)


    // récupérer toutes les pannes
    const fetchAll = async (id) => {


        try {


            ui.globalLoading = true
            const response = await api.get(`/reparation/${id}`)
            items.value = response.data
            console.log("Pannes from store check")

        }catch (exception) {

            toast.error("Impossible de récupérer les réparation")

        }finally {

            ui.globalLoading = false

        }

    }


    // récupérer le nombre total des réparations pour un vehicule
    const fetchTotalParMotif = async (id) => {

        try {

            const response = await api.get (`/reparation/${id}/total-par-motif`)
            total_par_motif.value = response.data

        }catch (exception) {

            toast.error ("Erreur lors de la récupération des totaux par motif des réparations")
            cpnsole.error ("Erreur lors de la récupération des totaux par motif des réparations : ", exception)


        } 

    }


    // ajouter une nouvelle réparation
    const addNewReparation = async (formData, id) => {

        try {

            ui.globalLoading = true
            const response = await api.post (`/reparation/${id}`, formData)
            toast.success ("Réparation ajoutée avec succès")

        }catch (exception) {

            toast.error ("Impossible d'ajouter cette réparation")
            console.log ("Impossible d'ajouter cette réparation : ", exception)

        }finally {

            ui.globalLoading = false

        }

    }


    // changer le statut d'une réparation
    const updateStatus = async (formData, reparation_id) => {

        try {

            ui.globalLoading = true
            const response = await api.put (`/reparation/${reparation_id}/update`, formData)
            toast.success("Modifier avec succès")

        }catch (exception) {

            toast.error("Erreur inattendue")
            console.log ("Une erreur est survenue : ", exception)

        }finally {

            ui.globalLoading = false

        }

    }



    return {

        items,
        total_par_motif,
        fetchAll,
        addNewReparation,
        updateStatus,
        fetchTotalParMotif


    }

})