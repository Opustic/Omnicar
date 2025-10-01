import { defineStore } from "pinia";
import { computed, ref } from "vue";
import { api } from "@/services/api";
import { useToast } from "vue-toastification";
import { useUiStore } from "./ui";




export const useArretsStore = defineStore("arrets", ()=> {


    const ui = useUiStore()
    const toast = useToast()


    // tous les arrêts
    const items = ref(null)


    // total par motif
    const total_par_motif = ref(null)


    const fetchAll = async (id) => {


        try {

            ui.globalLoading = true
            const response = await api.get(`arret/${id}`)
            items.value = response.data

            console.log("Arrêts from store check")

        }catch (exception) {

            toast.error("Impossible de récupérer les mises en arrêt")
            console.error("Impossible de récupérer les mises en arrêt : ", exception)

        }finally {

            ui.globalLoading = false

        }


    }



    const fetchTotalParMotif = async (id) => {

        try {

            const response = await api.get(`/arret/${id}/total-par-motif`)
            total_par_motif.value = response.data

        }catch (exception) {

            toast.error ("Erreur de récupérer des totaux par motifs")
            console.error("Erreur de récupérer des totaux par motifs : ", exception)

        }

    }


    const addNewArret = async (formData, id) => {
        try {
            ui.globalLoading = true
            const data = Object.fromEntries(formData)
            const response = await api.post(`/arret/${id}`, data)
            items.value.push(response.data.data)  // mettre à jour la liste
            toast.success("Arrêt ajouté avec succès")
        } catch (exception) {
            toast.error("Impossible d'ajouter un nouvel arrêt")
            console.error("Impossible d'ajouter un nouvel arrêt : ", exception)
        } finally {
            ui.globalLoading = false
        }
    }
    


    
    return {

        items,
        total_par_motif,
        fetchAll,
        fetchTotalParMotif,
        addNewArret

    }

});