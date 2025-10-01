import { ref, computed } from "vue";
import { defineStore } from "pinia";
import { api } from "@/services/api";
import { useToast } from "vue-toastification";
import { useUiStore } from "./ui";



// le toaster
const toast = useToast()



export const useChauffeursStore = defineStore ("chauffeur", ()=> {

    const ui = useUiStore()


    // tous les chauffeurs
    const items = ref(null)


    // les chauffeurs dispo et indispo
    const disponibles = computed(()=>items.value?.filter(n=>!n.equipe_id))
    const indisponibles = computed(()=>items.value?.filter(n=>n.equipe_id))



    // récupérer tous les chauffeurs
    const fetchAll = async ()=> {

        try {

            ui.globalLoading = true

            const response = await api.get("/chauffeur")
            items.value = response.data

            console.log("Chauffeurs from store check")
            
        }catch (exception) {
            console.log("Une erreur s'est produite lors du chargement de tous les chauffeurs", exception)
            toast.error("Une erreur est survenue ", exception.message)

        }finally {
    
            ui.globalLoading = false
        }
        

    }



    // insérer un chauffeur
    const addChauffeur = async (formData) => {

        try {
            ui.globalLoading = true
            const response = await api.post ("/chauffeur/create", formData, {headers:{"Content-Type":"multipart/form-data"}})
            toast.success("Chauffeur ajouté avec succès")
            await fetchAll()

        }catch (exception) {
            toast.error("Erreur : ", exception.message)
            
        }finally {
            ui.globalLoading = false
        }

    }



    // modifier les informations d'un chauffeur
    const updateChauffeur = async (formData, id) => {

        
        try {
            
            ui.globalLoading = true
            formData.append ("_method", "PUT")

            const response = await api.post (`/chauffeur/${id}/edit`, formData, {headers:{"Content-Type":"multipart/form-data"}})

            await fetchAll()

            toast.success("Modifié avec succès")

        }catch (exception) {

            toast.error ("Une erreur est survenue")
            console.log("Erreur : ", exception)

        }finally {

            ui.globalLoading = false

        }
    
    }


    // suppression
    const deleteChauffeur = async (id) => {

        try {

            ui.globalLoading = true
            const response = await api.delete(`/chauffeur/${id}/delete`)
            toast.success("Chauffeur supprimé avec succès")

        }catch (exception) {

            console.error("Impossible de supprimer ce chauffeur : ", exception)
            toast.error("Impossible de supprimer ce chauffeur")

        }finally {

            ui.globalLoading = false
            
        }

    
    }



    // on retourne ce qu'il faut retourner
    return {
        items, 
        disponibles, 
        indisponibles,
        fetchAll, 
        addChauffeur,
        updateChauffeur,
        deleteChauffeur
    }

})