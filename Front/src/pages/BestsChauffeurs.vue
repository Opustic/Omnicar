<template>



    <div class="container-fluid mt-5">

        <!-- Bouton pour retourner en arrière -->
        <RouterLink to="/menu" class="btn btn-return mb-3" style="text-decoration: none;">
            <i class="bi bi-caret-left-fill"></i> Retour
        </RouterLink>

        <div class="py-2">
            <h2 class="text-dark">
                Classement des chauffeurs
            </h2>
        </div>
    </div>


    <!-- Listing des infos des chauffeurs -->
    <div class="container-fluid">
        <TeamStats
            v-for="meilleur_chauffeur in total_versements_par_chauffeur"
            :key="meilleur_chauffeur?.telephone"
            :photo="meilleur_chauffeur?.photo"
            :nom="meilleur_chauffeur?.nom"
            :total="meilleur_chauffeur?.total_versements"
        />

    </div>




</template>




<script setup>

import { ref, onMounted, computed } from 'vue';
import TeamStats from '@/components/TeamStats.vue';
import { useVersementsStore } from '@/store/versements';



// =================================
// STORES PINIA
// =================================
const versementsStore = useVersementsStore()


// total des versements par chauffeur
const total_versements_par_chauffeur = computed(()=>versementsStore.total_versements_par_chauffeur)




// au montage du composant
onMounted(async()=> {

    await versementsStore.fetchTotalVersementParChauffeur()

})




</script>