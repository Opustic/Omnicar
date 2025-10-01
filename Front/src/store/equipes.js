import { defineStore } from "pinia";
import { computed, ref } from "vue";
import { api } from "@/services/api";
import { useToast } from "vue-toastification";
import { useUiStore } from "./ui";
import { useChauffeursStore } from "./chauffeurs";
import { useControleursStore } from "./controleurs";




export const useEquipeStore = defineStore("equipe", ()=> {
    
    const toast = useToast()
    const ui = useUiStore()


    const chauffeurs = useChauffeursStore()
    const controleurs = useControleursStore()

    // toutes les équipes
    const items = ref(null)


    // equipes disponibles
    const disponibles = computed(()=>items.value?.filter(n=>!n.vehicule_id))


    // récupérer toutes les équipes
    const fetchAll = async ()=> {
        try {
            ui.globalLoading = true
            const response =  await api.get("/equipe")

            items.value = response.data

            console.log("Equipes from store check")

        }catch (exception) {
            toast.error ("Erreur lors de la récupération des équipes")
            console.log("Erreur lors de la récupération des équipes ", exception)

        }finally {
            ui.globalLoading = false

        }
    }



    // ajouter une équipe
    const addEquipe = async (formData) => {

        try {
            
            ui.globalLoading = true
            const response = await api.post("/equipe/create", formData)
            await fetchAll()

            // on refresh les chauffeurs et les controleurs
            await chauffeurs.fetchAll()
            await controleurs.fetchAll()

            toast.success("Equipe ajoutée avec succès")

        }catch (exception) {
            
            toast.error("Une erreur est survenue")
            console.log("Erreur : ", exception)

        }finally {
            ui.globalLoading = false
        }

    }


    // modifier une équipe
    const updateEquipe = async (formData, id) => {

        try {

            ui.globalLoading = true
            const response = await api.post (`/equipe/${id}`, formData)
            toast.success("Modifié avec succès")
            await fetchAll()

        }catch (exception) {

            toast.error ("Une erreur est survenue")
            console.log("Erreur lors de la mise à jour de l'équipe : ", exception)

        }finally {
            
            ui.globalLoading = false
            
        }



    }


    // assigner une équipe à un véhicule
    const assignEquipe =  async (formData, equipeID, vehiculeID) => {

        try {

            ui.globalLoading = true
            const response = await api.post (`/equipe/${equipeID}/assigner/${vehiculeID}`, formData)
            toast.success("Equipe assignée avec succès")

        }catch (exception) {

            toast.error ("Une erreur est survenue")
            console.log ("Erreur lors de l'assignation de l'équipe : ", exception)

        }finally {

            ui.globalLoading = false

        }
        
    }



    // modifier une équipe assignée à un autre vehicule
    const updateAssignedEquipe = async (formData, id)=> {


        try {

            ui.globalLoading = true
            const response = await api.post (`/equipe/${id}`, formData, {headers:{"Content-Type":"multipart/form-data"}})

        }catch (exception) {

            toast.error ("Erreur inattendue")
            console.log ("Une erreur est survenue lors de la modification de l'équipe")
            
        }finally {

            ui.globalLoading = false

        }

    }


    // créer l'équipe à assigner au vehicule
    const createToAssign = async (formData, vehicule_id) => {

        try {

            ui.globalLoading = true
            const response = await api.post (`/equipe/${vehicule_id}/create`, formData, {headers:{"Content-Type":"multipart/form-data"}})
            toast.success("Créée avec succès")

        }catch (exception) {

            toast.error("Une erreur est survenue")
            console.error("Erreur lors de la création de l'équipe : ", exception)

        }finally {

            ui.globalLoading = false

        }

    }


    return {
        items, 
        disponibles,
        fetchAll, 
        addEquipe,
        updateEquipe,
        assignEquipe,
        createToAssign,
        updateAssignedEquipe
    }

})