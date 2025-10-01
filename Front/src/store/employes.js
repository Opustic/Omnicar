import { defineStore } from "pinia";
import { computed, ref } from "vue";
import { api } from "@/services/api";
import { useToast } from "vue-toastification";
import { useUiStore } from "./ui";



export const useEmployesStore = defineStore("employes", ()=> {

    // UX
    const ui = useUiStore()
    const toast = useToast()


    // tous les employés
    const items = ref(null)



    // récupérer tous les employés
    const fetchAll = async () => {

        try {

            ui.globalLoading = true
            const response = await api.get ("/employe")
            items.value = response.data
            console.log("Employés from store check")

        }catch (exception) {

            toast.error ("Une erreur est survenue")
            console.log ("Impossible de récupérer les employés : ", exception)

        }finally {

            ui.globalLoading = false

        }

    }



    // 
    return {

        items,
        fetchAll

    }

}) 