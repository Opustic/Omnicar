<template>
    

    <!-- Modale Bootstrap -->
    <Modale
        title="Ajouter un nouveau vehicule"
        :show="showAddNewVehicle"
        @close="showAddNewVehicle=false"
    >
        <div>
            <form @submit.prevent="submitAddNewVehicle"  enctype="multipart/form-data">


                <!-- entrer l'immatriculation du vehicule -->
                <input type="text" placeholder="Entrez l'immatriculation..." name="immatriculation" id="immatriculation" class="form-control mb-2" required>


                <!-- Photo du vehicule -->
                <input class="form-control mt-2" type="file" id="photo" name="photo" accept="image/*" required>



                <!-- Soumettre le formulaire -->
                <button type="submit" class="btn btn-primary mt-2">Envoyer</button>
            </form>
        </div>

    </Modale>



    <div class="container">
        
        <!-- Barre de recherche -->
        <div class="mt-4">
    
    
            <!-- Ajouter un vehicule -->
            <button class="btn btn-primary mt-3 mb-3 btn-add-vehicle" @click="showAddNewVehicle=true">
                <i class="bi bi-plus-circle-fill"></i> Nouveau vehicule
            </button>
    
            <!-- Centrer la barre de recherche -->
            <div class="d-flex justify-content-center align-items-center">
    
                <!-- Searchbar -->
                <div class="searchbar p-4">
    
                    <!-- Section filtre -->
                    <div class="d-flex gx-1">
                        <h5 class="fw-bold text-dark mt-2">Filtrer </h5>
                        <div class="d-flex ms-1">
                            <!-- navigation -->
                            <Button
                                label="Actifs"
                                severity="primary"
                                @click="()=>filtred_vehicules_actifs()" 
                                :outlined="value !== '0'"
                            />
    
                            <Button
                                class="ms-1"
                                label="Inactifs"
                                severity="danger"
                                @click="()=>filtred_vehicules_inactifs()" 
                                :outlined="value !== '1'"
                            />
    
                        </div>
                    </div>
    
    
                    <!-- formulaire pour la recherche -->
                    <div class="mt-2 ms-2">
                        <form class="form-recherche-bus">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <input type="text" class="col-8 form-control w-100" placeholder="Rechercher ici ... (immatriculation)" v-model="searchQuery">
                                </div>
    
                            </div>
                        </form>
    
                    </div>
    
    
                </div>
            </div>
        </div>
    
    
        <!-- Liste de tous les vehicules -->
        <div class="mt-4">
    
    
            <div class="">
                <div v-if="vehicules" class="row">
                    
                    <template v-for="vehicule in vehicules_to_display">
        
                        <div class="col-12 col-md-6 col-lg-4">
        
                            <BusCard
                                :busId="vehicule.id"
                                :busImmatriculation="vehicule.immatriculation"
                                :status="vehicule.statut"
                                :busImage="vehicule.photo"
                                :revenue="vehicule.total_versements_jour"
                                :repairs="vehicule.nombre_reparations"
                            />
        
                        </div>
        
        
                    </template>
        
        
                
                </div>
    
                <div v-else>
    
                    <h2 class="text-dark">
                        Aucun véhicule présent
                        Veuillez en ajouter
                    </h2>
    
                </div>
            </div>
    
    
    
        </div>

    </div>




</template>


<script setup>
import BusCard from '@/components/BusCard.vue';
import { ref, computed, onMounted } from 'vue'
import Modale from '@/components/Modale.vue';
import { useToast } from 'vue-toastification';
import { useVehiculesStore } from '@/store/vehicules';


import Button from 'primevue/button';




// toaster
const toast  = useToast()



// ======================================
// STORES PINIA
// ======================================
const vehiculesStore = useVehiculesStore()




// ======================================
// ENTITÉS
// ======================================


// valeur du filtre
const value = ref(null);



// recherche en temps réel
const searchQuery = ref('')


// récupérer la liste brute
const vehicules = computed(() => vehiculesStore.items)


// filtre combiné
const vehicules_to_display = computed(() => {

    
    let result = vehicules.value

    // 1) Filtre statut
    if (value.value === "0") {
        result = result.filter(v => v.statut === "actif")
    } else if (value.value === "1") {
        result = result.filter(v => v.statut === "inactif")
    }

    // 2) Filtre recherche
    if (searchQuery.value.trim() !== "") {
        const q = searchQuery.value.toLowerCase()
        result = result.filter(v => 
        v.immatriculation.toLowerCase().includes(q))
    }

    return result
})
// fonctions de toggle
const filtred_vehicules_actifs = () => {
    value.value = value.value === "0" ? null : "0"
}

const filtred_vehicules_inactifs = () => {
    value.value = value.value === "1" ? null : "1"
}


// Variables réactives pour modale
const showAddNewVehicle = ref(false)







// ============================================= //
// SOUMISSION DE FORMULAIRES                     
//============================================== //

const submitAddNewVehicle = async (event) => {


    // formulaire
    const form = event.target


    // formData
    const formData = new FormData(form)


    try {
        
        vehiculesStore.addVehicule(formData)
        showAddNewVehicle.value = false

    }catch (exception) {

        console.log("Erreur lors de l'ajout du vehicule : ", exception)

    }

    
}






onMounted(async () => {
    await vehiculesStore.fetchAll()
})






</script>



<style scoped>

    /* SearchBar */
    .searchbar {
        height: auto;
        width: 100%;
        background-color: white;
        border-radius: 10px;
        border: 1px solid var(--color-primary);
    }



    

    /* Réglage pour le formulaire */
    .form-recherche-bus input {
        color : var(--color-primary);
        font-family: "Ubuntu";
        
    }




    .form-recherche-bus input::placeholder {
        color: #d9d9d9;
        font-family: 'Ubuntu';
    }



    /* Boutons de la searchbar */

    .btn-search {
        background-color: var(--color-primary);
        color: white;
        font-weight: bold;
        width: 150px;
        font-size: 19px;
    }

    .btn-search:hover {
        background-color: var(--color-primary);
        color: white;
        font-weight: bold;
    }


    .btn-ajouter {
        background-color: var(--color-accent-green);
        color: white;
    }



    .btn-add-vehicle:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
</style>