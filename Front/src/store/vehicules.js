import { defineStore } from "pinia";
import { computed, ref } from "vue";
import { api } from "@/services/api";
import { useToast } from "vue-toastification";
import { useUiStore } from "./ui";



export const useVehiculesStore = defineStore("vehicules", ()=> {


    // toaster et ui
    const toast = useToast()
    const ui = useUiStore()


    // tous les vehicules
    const items = ref(null)

    // vehicule particulier
    const item = ref(null)


    // équipe liée à un bus
    const item_equipe = computed(()=> item.value?.equipes)


    // vehicules actifs et inactifs
    const actifs = computed (()=> items.value?.filter(n=> n.statut==="actif"))
    const inactifs = computed(()=> items.value?.filter(n=>n.statut==="inactif"))



    // récupérer tous les vehicules
    const fetchAll = async () => {

        try {

            ui.globalLoading = true
            const response = await api.get("/vehicule")
            items.value = response.data
            console.log("Vehicules from store check")

        }catch (exception) {

            toast.error("Impossible de récupérer les bus")
            console.log("Erreur lors de la récupération des bus : ", exception)

        }finally {
            
            ui.globalLoading = false
            
        }

    }


    // récupérer un véhicule particulier par son id
    const fetchVehicule = async (id) => {

        try {
            
            ui.globalLoading =  true
            const response = await api.get(`/vehicule/${id}`)
            item.value = response.data

        }catch (exception) {

            toast.error ("Impossible de récupérer les informations du véhicule")
            console.log ("Erreur lors de la récupération du véhicule : ", exception)

        }finally {

            ui.globalLoading = false

        }

    }


    // ajouter un véhicule
    const addVehicule = async (formData) => {
        try {
            ui.globalLoading = true;

            // petite normalisation avant envoi
            if (formData.get("equipe")) {
                formData.set("equipe", Number(formData.get("equipe")));
            } else {
                formData.set("equipe", null);
            }

            const response = await api.post("/vehicule/create", formData, {
                headers: { "Content-Type": "multipart/form-data" }
            });

            toast.success("Véhicule ajouté avec succès");

            // recharger la liste après ajout
            await fetchAll();

            return response.data; // permet au composant d’utiliser la réponse si besoin

        } catch (exception) {
            toast.error("Une erreur est survenue");
            console.error("Erreur lors de l'ajout du véhicule :", exception);
            throw exception; // on relance l'erreur pour gérer côté composant si besoin
        } finally {
            ui.globalLoading = false;
        }
    };



    // modifier un vehicule
    const updateVehicule = async (formData, id) => {

        try {

            ui.globalLoading = true
            const response = await api.post (`/vehicule/${id}`, formData, {headers:{"Content-Type":"multipart/form-data"}})
            toast.success ("Modifié avec succès")

        } catch (exception) {

            toast.error ("Une erreur est survenue")
            console.log ("Erreur lors de la mise à jour des informations de ce bus : ", exception)

        }finally {

            ui.globalLoading = false

        }

    }


    // changer le statut d'un vehicule
    const updateStatus = async (formData, id) => {

        try {
            
            ui.globalLoading = true
            const response = await api.post(`/vehicule/${id}/changerStatut`, formData)
            toast.success("Modifié avec succès")

        }catch (exception) {

            toast.error("Impossible de modifier le statut")
            console.log("Impossible de modifier le statut car : ", exception)

        }finally {

            ui.globalLoading = false

        }

    }

    
    return {
        items,
        item, 
        item_equipe,
        actifs,
        inactifs,
        fetchAll,
        fetchVehicule,
        addVehicule,
        updateVehicule,
        updateStatus
    }

})