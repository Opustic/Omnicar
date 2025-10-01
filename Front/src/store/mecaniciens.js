import { defineStore } from "pinia";
import { computed, ref } from "vue";
import { api } from "@/services/api";
import { useToast } from "vue-toastification";
import { useUiStore } from "./ui";




export const useMecaniciensStore = defineStore("mecaniciens", ()=> {

    const toast = useToast()
    const ui = useUiStore()


    // tous les mécaniciens
    const items = ref(null)


    // mécanicien particulier
    const item = ref(null)



    // récupérer tous les mécaniciens
    const fetchAll = async () => {
        try {
            ui.globalLoading = true
            const response = await api.get("/mecanicien")
            items.value = response.data
        }
        catch (exception) {
            toast.error("Erreur lors de la récupération des mécaniciens")
            console.log("Erreur lors de la récupération des mécaniciens : ", exception)
        }
        finally {
            ui.globalLoading = false
        }
    }



    // récupérer un mécanicien par son id
    const getMecanicien = async (id) => {

        try {
            ui.globalLoading = true
            const response = await api.get(`/mecanicien/${id}`)
            item.value = response.data
        }

        catch (exception) {
            toast.error("Erreur lors de la récupération du mécanicien")
            console.log("Erreur lors de la récupération du mécanicien : ", exception)
        }
        
        finally {
            ui.globalLoading = false
        }
    }



    // ajouter un mécanicien
    const addMecanicien = async (formData) => {
        try {
            ui.globalLoading = true
            const response = await api.post("/mecanicien/create", formData, {headers:{"Content-Type":"multipart/form-data"}})
            toast.success("Mécanicien ajouté avec succès")
            await fetchAll()
        }
        catch (exception) {
            toast.error("Erreur lors de l'ajout du mécanicien")
            console.log("Erreur lors de l'ajout du mécanicien : ", exception)
        }
        finally {
            ui.globalLoading = false
        }
    }



    // mettre à jour les informations du mécanicien
    const updateMecanicien = async (formData, id) => {
        try {
            ui.globalLoading = true
            const response = await api.post(`/mecanicien/${id}`, formData, {headers:{"Content-Type":"multipart/form-data"}})
            toast.success("Mécanicien modifié avec succès")
            await fetchAll()
        }
        catch (exception) {
            toast.error("Erreur lors de la modification du mécanicien")
            console.error("Erreur lors de la modification du mécanicien : ", exception)
        }
        finally {
            ui.globalLoading = false
        }
    }


    // supprimer un mécanicien
    const destroyMecanicien = async (id) => {

        try {

            ui.globalLoading = true
            const response = await api.delete(`/mecanicien/${id}/delete`)
            toast.success("Mecanicien supprimé avec succès")

        }catch (exception) {

            console.error("Impossible de supprimer le mécanicien")
            toast.error("Impossible de supprimer le mécanicien")
            
        }finally {

            ui.globalLoading = false

        }

    }


    return {
        items, 
        item,
        fetchAll,
        getMecanicien,
        addMecanicien,
        updateMecanicien,
        destroyMecanicien
    }

})