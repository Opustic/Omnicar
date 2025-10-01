import { defineStore } from "pinia";
import { computed, ref } from "vue";
import { api } from "@/services/api";
import { useToast } from "vue-toastification";
import { useUiStore } from "./ui";






export const useControleursStore = defineStore("controleur", ()=> {
    

    // le toaster
    const toast  = useToast()

    const ui = useUiStore()


    // controleurs disponibles
    const items = ref(null)


    // les controleurs dispo et indispo
    const disponibles = computed(()=> items.value?.filter(n => !n.equipe_id))
    const indisponibles = computed(()=> items.value?.filter(n=>n.equipe_id))



    // récupération de tous les controleurs
    const fetchAll = async () => {

        try {
            ui.globalLoading = true
            const response = await api.get("/controleur")

            items.value = response.data

            console.log("Controleurs from store check")

        }catch (exception) {
            console.log("Erreur lors de la récupération des controleurs : ", exception)
            toast.error("Impossible de récupérer les controleurs")


        }finally {
            ui.globalLoading = false
        }
    }



    // insérer un controleur
    const addControleur = async (formData) => {


        try {
            ui.globalLoading = true
            const response = await api.post("/controleur/create", formData, {headers:{"Content-Type":"multipart/form-data"}})
            toast.success("Controleur ajouté avec succès")
            await fetchAll()

        }catch (exception) {
            toast.error("Erreur : ", exception.message)

        }finally {
            ui.globalLoading = false

        }

    }


    // modifier les informations d'un chauffeur
    const updateControleur = async (formData, id) => {

        try {

            ui.globalLoading = true
            
            const response = await api.post(`/controleur/${id}/edit`, formData, {headers:{"Content-Type":"multipart/form-data"}})
            await fetchAll()
            toast.success("Modifié avec succès")


        }catch (exception) {

            console.log("Erreur lors de la modification du chauffeur : ", exception)
            toast.error("Une erreur est survenue")

        }finally {

            ui.globalLoading = false

        }

    }



    // suppression
    const deleteControleur = async (id) => {

        try {

            const response = await api.delete(`/controleur/${id}/delete`)
            toast.success("Controleur supprimé avec succès")

        }catch (exception) {

            console.error ("Impossible de supprimer ce controleur : ", exception)
            toast.error ("Impossible de supprimer ce controleur")

        }

    }



    return {
        items, 
        disponibles,
        indisponibles,
        fetchAll,
        addControleur, 
        updateControleur,
        deleteControleur
    }



})