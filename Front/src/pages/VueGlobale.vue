<template>

    <div class="container mt-4">


        

        <!-- Ligne 1 : Revenus du jour -->
        <div>

            <h4 class="text-dark">
                Revenus du jour
            </h4>

            <!-- Informations ligne 1 -->
            <div class="row gy-2">
                <div class="col-md-4">
                    <VueGlobaleCard label="Chauffeur" :montant="total_chauffeurs_today?.total" statut="hausse" pourcentage=5 />
                </div>

                <div class="col-md-4">
                    <VueGlobaleCard label="Controleur" :montant="total_controleurs_today?.total" statut="baisse" pourcentage=5 />
                </div>

                <div class="col-md-4">
                    <VueGlobaleCard label="Total" :montant="total_chauffeurs_today?.total + total_controleurs_today?.total" statut="hausse" pourcentage=15 />
                </div>
                
            </div>

            
        </div>


        <!-- Ligne de séparation -->
        <hr>


        <!-- Ligne 2 : Revenus de la semaine en cours -->
        <div>


            <h4 class="text-dark">
                Revenus de la semaine en cours
            </h4>

            <!-- Informations ligne 1 -->
            <div class="row gy-2">
                <div class="col-md-4">
                    <VueGlobaleCard label="Chauffeur" :montant="total_chauffeurs_this_week?.total" statut="hausse" pourcentage=5 />
                </div>

                <div class="col-md-4">
                    <VueGlobaleCard label="Controleur" :montant="total_controleurs_this_week?.total" statut="baisse" pourcentage=5 />
                </div>

                <div class="col-md-4">
                    <VueGlobaleCard label="Total" :montant="total_chauffeurs_this_week?.total + total_controleurs_this_week?.total" statut="hausse" pourcentage=15 />
                </div>
                
            </div>

            
        </div>



        <!-- Ligne de séparation -->
        <hr>


        <!-- Ligne 3 : Revenus du mois -->
        <div class="mt-2 mb-4">

            <h4 class="text-dark">
                Revenus du mois en cours
            </h4>

            <!-- Informations ligne 1 -->
            <div class="row gy-2">
                <div class="col-md-4">
                    <VueGlobaleCard label="Chauffeur" :montant="total_chauffeurs_this_month?.total" statut="hausse" pourcentage=5 />
                </div>

                <div class="col-md-4">
                    <VueGlobaleCard label="Controleur" :montant="total_controleurs_this_month?.total" statut="baisse" pourcentage=5 />
                </div>

                <div class="col-md-4">
                    <VueGlobaleCard label="Total" :montant="total_chauffeurs_this_month?.total + total_controleurs_this_month?.total" statut="hausse" pourcentage=15 />
                </div>
                
            </div>

        </div>


    </div>

    <br>
    
</template>

<script setup>
import VueGlobaleCard from '@/components/VueGlobaleCard.vue';
import { useVersementsStore } from '@/store/versements';
import { computed, onMounted } from 'vue';

const versementsStore = useVersementsStore();

const total_chauffeurs_today = computed(()=>versementsStore.total_versements_chauffeurs_today);
const total_controleurs_today = computed(()=>versementsStore.total_versements_controleurs_today);

const total_chauffeurs_this_week = computed(()=>versementsStore.total_versements_chauffeurs_this_week);
const total_controleurs_this_week = computed(()=>versementsStore.total_versements_controleurs_this_week);

const total_chauffeurs_this_month = computed(()=>versementsStore.total_versements_chauffeurs_this_month);    
const total_controleurs_this_month = computed(()=>versementsStore.total_versements_controleurs_this_month);





onMounted(async () => {

    await versementsStore.fetchTotalVersementsChauffeursToday();
    await versementsStore.fetchTotalVersementsControleursToday(),
    await versementsStore.fetchTotalVersementsChauffeursThisWeek(),
    await versementsStore.fetchTotalVersementsControleursThisWeek(),
    await versementsStore.fetchTotalVersementsChauffeursThisMonth(),
    await versementsStore.fetchTotalVersementsControleursThisMonth()

    
});



</script>


<style scoped>

h4 {
    font-family: var(--font-title-small);
}

</style>